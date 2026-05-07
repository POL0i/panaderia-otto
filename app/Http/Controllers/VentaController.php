<?php
// app/Http/Controllers/VentaController.php

namespace App\Http\Controllers;

use App\Models\NotaVenta;
use App\Models\Almacen;
use App\Models\Item;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\AlmacenItem;
use App\Models\CategoriaProducto;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\TransaccionLibelula;
use App\Services\LibelulaService;

class VentaController extends Controller
{
    protected $libelulaService;

    public function __construct(LibelulaService $libelulaService)
    {
        $this->libelulaService = $libelulaService;
    }

    public function index()
    {
        $almacenes = Almacen::all();

        $items = Item::where('tipo_item', 'producto')
            ->with(['almacenItems'])
            ->get();

        $clientes = Cliente::all();

        $notasVenta = NotaVenta::with(['cliente', 'empleado', 'detalles'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $detallesVenta = DB::table('detalles_venta')
            ->join('notas_venta', 'detalles_venta.id_nota_venta', '=', 'notas_venta.id_nota_venta')
            ->join('almacenes', 'detalles_venta.id_almacen', '=', 'almacenes.id_almacen')
            ->join('items', 'detalles_venta.id_item', '=', 'items.id_item')
            ->select(
                'detalles_venta.*',
                'notas_venta.id_nota_venta',
                'almacenes.nombre as almacen_nombre',
                'items.nombre as producto_nombre'
            )
            ->orderBy('detalles_venta.created_at', 'desc')
            ->take(20)
            ->get();

        $categoriasProducto = CategoriaProducto::all();

        return view('seccion-ventas.index', compact(
            'almacenes', 'items', 'clientes', 'notasVenta', 'detallesVenta', 'categoriasProducto'
        ));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_cliente' => 'required|exists:clientes,id_cliente',
                'detalles'   => 'required|array|min:1',
                'detalles.*.id_almacen' => 'required|exists:almacenes,id_almacen',
                'detalles.*.id_item'    => 'required|exists:items,id_item',
                'detalles.*.cantidad'   => 'required|integer|min:1',
                'detalles.*.precio'     => 'required|numeric|min:0.01',
            ]);

            DB::beginTransaction();

            $usuario = Auth::user();

            if (!$usuario) {
                throw new \Exception('Usuario no autenticado - La sesión puede haber expirado');
            }

            if ($usuario->empleado) {
                $idEmpleado = $usuario->empleado->id_empleado;
            } else {
                $empleado = Empleado::first();
                if (!$empleado) {
                    throw new \Exception('No hay empleados registrados en el sistema');
                }
                $idEmpleado = $empleado->id_empleado;
            }

            $montoTotal = collect($validated['detalles'])->sum(fn($d) => $d['cantidad'] * $d['precio']);

            $notaVenta = NotaVenta::create([
                'fecha_venta' => now(),
                'monto_total' => $montoTotal,
                'estado'      => 'completado',
                'id_cliente'  => $validated['id_cliente'],
                'id_empleado' => $idEmpleado,
            ]);

            foreach ($validated['detalles'] as $detalle) {
                $almacenItem = AlmacenItem::where('id_almacen', $detalle['id_almacen'])
                    ->where('id_item', $detalle['id_item'])
                    ->lockForUpdate()
                    ->first();

                if (!$almacenItem) {
                    throw new \Exception("El producto no está registrado en el almacén seleccionado");
                }

                if ($almacenItem->stock < $detalle['cantidad']) {
                    throw new \Exception("Stock insuficiente para el producto solicitado");
                }

                DetalleVenta::create([
                    'id_nota_venta' => $notaVenta->id_nota_venta,
                    'id_almacen'    => $detalle['id_almacen'],
                    'id_item'       => $detalle['id_item'],
                    'cantidad'      => $detalle['cantidad'],
                    'precio'        => $detalle['precio'],
                ]);

                DB::table('almacen_item')
                    ->where('id_almacen', $detalle['id_almacen'])
                    ->where('id_item', $detalle['id_item'])
                    ->decrement('stock', $detalle['cantidad']);

                if (class_exists(\App\Models\LoteInventario::class)) {
                    try {
                        \App\Models\LoteInventario::consumir(
                            $detalle['id_almacen'],
                            $detalle['id_item'],
                            $detalle['cantidad'],
                            'PEPS'
                        );
                    } catch (\Exception $e) {
                        Log::warning('Error al consumir lote: ' . $e->getMessage());
                    }
                }

                if (class_exists(\App\Models\MovimientoInventario::class)) {
                    try {
                        \App\Models\MovimientoInventario::registrar([
                            'tipo_movimiento' => 'egreso',
                            'id_almacen' => $detalle['id_almacen'],
                            'id_item' => $detalle['id_item'],
                            'cantidad' => -$detalle['cantidad'],
                            'precio_unitario' => $detalle['precio'],
                            'costo_total' => -($detalle['cantidad'] * $detalle['precio']),
                            'referencia_id' => $notaVenta->id_nota_venta,
                            'referencia_tipo' => 'venta',
                            'observaciones' => 'Egreso por venta #' . $notaVenta->id_nota_venta,
                        ]);
                    } catch (\Exception $e) {
                        Log::warning('Error al registrar movimiento: ' . $e->getMessage());
                    }
                }
            }

            DB::commit();
            $notaVenta->load(['cliente', 'empleado']);

            return response()->json([
                'success'   => true,
                'message'   => 'Venta registrada exitosamente',
                'nota_venta' => $notaVenta
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . implode(' ', $e->validator->errors()->all())
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error en la base de datos al procesar la venta'
            ], 500);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getClientes()
    {
        return response()->json(['clientes' => Cliente::all(['id_cliente', 'nombre', 'telefono'])]);
    }

    public function getAlmacenes()
    {
        return response()->json(['almacenes' => Almacen::all(['id_almacen', 'nombre'])]);
    }

    public function getItems()
    {
        $items = Item::where('tipo_item', 'producto')
            ->get()
            ->map(function($item) {
                return [
                    'id_item' => $item->id_item,
                    'nombre' => $item->nombre,
                    'unidad' => $item->unidad_medida ?? 'unidad'
                ];
            });
        return response()->json(['items' => $items]);
    }

    public function getStock($idAlmacen, $idItem)
    {
        $almacenItem = AlmacenItem::where('id_almacen', $idAlmacen)
            ->where('id_item', $idItem)
            ->first();
        return response()->json(['stock' => $almacenItem ? $almacenItem->stock : 0]);
    }

    public function getNotaVenta($id)
    {
        $nota = NotaVenta::with(['cliente', 'emploedo'])->findOrFail($id);
        $detalles = DB::table('detalles_venta')
            ->join('almacenes', 'detalles_venta.id_almacen', '=', 'almacenes.id_almacen')
            ->join('items', 'detalles_venta.id_item', '=', 'items.id_item')
            ->where('detalles_venta.id_nota_venta', $id)
            ->select(
                'detalles_venta.*',
                'almacenes.nombre as almacen_nombre',
                'items.nombre as producto_nombre'
            )
            ->get();
        return response()->json(['nota_venta' => $nota, 'detalles' => $detalles]);
    }

    public function enviarCorreo(Request $request)
    {
        $request->validate([
            'id_venta' => 'required|exists:notas_venta,id_nota_venta',
            'correo' => 'required|email'
        ]);

        try {
            $idVenta = $request->input('id_venta');
            $correoDestino = $request->input('correo');

            $notaVenta = NotaVenta::with([
                'cliente',
                'empleado',
                'detalles.almacen',
                'detalles.item'
            ])->findOrFail($idVenta);

            Mail::send('emails.comprobante-venta', ['nota' => $notaVenta], function ($message) use ($correoDestino, $notaVenta) {
                $message->to($correoDestino)
                        ->subject('Comprobante de Venta #' . $notaVenta->id_nota_venta . ' - Panadería Otto');
            });

            Log::info('Correo de venta enviado', [
                'id_venta' => $idVenta,
                'correo' => $correoDestino
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comprobante enviado exitosamente a ' . $correoDestino
            ]);

        } catch (\Exception $e) {
            Log::error('Error al enviar correo de venta: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el correo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProductosConStock()
    {
        $almacenesItems = AlmacenItem::with(['almacen', 'item'])
            ->whereHas('item', function($query) {
                $query->where('tipo_item', 'producto');
            })
            ->get();

        $productos = [];

        foreach ($almacenesItems as $ai) {
            if ($ai->item) {
                $imagen = null;
                if ($ai->item->producto && $ai->item->producto->imagen) {
                    if (filter_var($ai->item->producto->imagen, FILTER_VALIDATE_URL)) {
                        $imagen = $ai->item->producto->imagen;
                    } else {
                        $imagen = Storage::url($ai->item->producto->imagen);
                    }
                }

                $productos[] = [
                    'id_almacen' => $ai->id_almacen,
                    'almacen_nombre' => $ai->almacen->nombre,
                    'id_item' => $ai->id_item,
                    'producto_nombre' => $ai->item->nombre,
                    'stock' => $ai->stock,
                    'precio' => $ai->item->producto->precio ?? 0,
                    'imagen' => $imagen,
                    'unidad_medida' => $ai->item->unidad_medida
                ];
            }
        }

        return response()->json([
            'success' => true,
            'productos' => $productos
        ]);
    }

    public function landingPage()
    {
        $productosConStock = DB::table('almacen_item')
            ->join('items', 'almacen_item.id_item', '=', 'items.id_item')
            ->join('productos', 'items.id_item', '=', 'productos.id_item')
            ->join('almacenes', 'almacen_item.id_almacen', '=', 'almacenes.id_almacen')
            ->leftJoin('categoria_producto', 'productos.id_cat_producto', '=', 'categoria_producto.id_cat_producto')
            ->where('items.tipo_item', 'producto')
            ->where('almacen_item.stock', '>', 0)
            ->select(
                'almacen_item.id_almacen',
                'almacen_item.id_item',
                'items.nombre',
                'productos.precio',
                'almacen_item.stock',
                'productos.imagen',
                'almacenes.nombre as almacen_nombre',
                'categoria_producto.nombre as categoria'
            )
            ->orderBy('items.nombre')
            ->get()
            ->map(function($item) {
                $imagenUrl = null;
                if ($item->imagen) {
                    if (filter_var($item->imagen, FILTER_VALIDATE_URL)) {
                        $imagenUrl = $item->imagen;
                    } else {
                        $imagenUrl = Storage::url($item->imagen);
                    }
                }

                return (object)[
                    'id_almacen' => $item->id_almacen,
                    'id_item' => $item->id_item,
                    'nombre' => $item->nombre,
                    'precio' => floatval($item->precio),
                    'stock' => intval($item->stock),
                    'imagen' => $imagenUrl,
                    'almacen_nombre' => $item->almacen_nombre,
                    'categoria' => $item->categoria ?? 'Producto',
                    'descripcion' => ''
                ];
            });

        return view('PanaderiaOtto', compact('productosConStock'));
    }

    public function agregarAlCarrito(Request $request)
    {
        $cart = session()->get('cart', []);
        $key = $request->id_almacen . '_' . $request->id_item;

        if(isset($cart[$key])) {
            $cart[$key]['cantidad'] += $request->cantidad;
        } else {
            $cart[$key] = [
                'id_almacen' => $request->id_almacen,
                'id_item' => $request->id_item,
                'nombre' => $request->nombre,
                'precio' => $request->precio,
                'cantidad' => $request->cantidad,
                'almacen_nombre' => $request->almacen_nombre,
                'imagen' => $request->imagen
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'cart_count' => count($cart),
            'message' => 'Producto agregado al carrito'
        ]);
    }

    public function actualizarCarrito(Request $request)
    {
        $cart = session()->get('cart', []);
        $key = $request->key;

        if(isset($cart[$key])) {
            $cart[$key]['cantidad'] = $request->cantidad;
            session()->put('cart', $cart);

            $subtotal = $cart[$key]['precio'] * $cart[$key]['cantidad'];
            $total = array_sum(array_map(function($item) {
                return $item['precio'] * $item['cantidad'];
            }, $cart));

            return response()->json([
                'success' => true,
                'subtotal' => number_format($subtotal, 2),
                'total' => number_format($total, 2),
                'message' => 'Cantidad actualizada'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Producto no encontrado']);
    }

    public function eliminarDelCarrito(Request $request)
    {
        $cart = session()->get('cart', []);
        $key = $request->key;

        if(isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);

            $total = array_sum(array_map(function($item) {
                return $item['precio'] * $item['cantidad'];
            }, $cart));

            return response()->json([
                'success' => true,
                'cart_count' => count($cart),
                'total' => number_format($total, 2),
                'message' => 'Producto eliminado'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Producto no encontrado']);
    }

    public function verCarrito()
    {
        $cart = session()->get('cart', []);
        $total = array_sum(array_map(function($item) {
            return $item['precio'] * $item['cantidad'];
        }, $cart));

        return view('carrito', compact('cart', 'total'));
    }

    public function carritoCount()
    {
        $cart = session()->get('cart', []);
        return response()->json(['count' => count($cart)]);
    }

    public function debugProductos()
    {
        $productos = Producto::with('item')->get();
        $items = Item::where('tipo_item', 'producto')->get();
        $almacenItems = AlmacenItem::with(['item', 'almacen'])->get();

        return response()->json([
            'productos' => $productos,
            'items' => $items,
            'almacen_items' => $almacenItems,
            'total_productos' => $productos->count(),
            'total_items' => $items->count(),
            'total_almacen_items' => $almacenItems->count()
        ]);
    }

    public function procesarPedido(Request $request, LibelulaService $libelula)
{
    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return redirect()->route('landing')->with('error', 'Carrito vacío');
    }

    $total = $this->calcularTotal($cart);
    $clienteId = null;
    $empleadoId = null;

    if (Auth::check()) {
        $usuario = Auth::user();
        if ($usuario->id_cliente) {
            $clienteId = $usuario->id_cliente;
        } elseif ($usuario->id_empleado) {
            $empleadoId = $usuario->id_empleado;
            $clienteAnonimo = $this->obtenerOCrearClienteAnonimo();
            $clienteId = $clienteAnonimo->id_cliente;
        } else {
            $clienteAnonimo = $this->obtenerOCrearClienteAnonimo();
            $clienteId = $clienteAnonimo->id_cliente;
        }
    } else {
        $clienteAnonimo = $this->obtenerOCrearClienteAnonimo();
        $clienteId = $clienteAnonimo->id_cliente;
    }

    // Crear nota de venta
    $notaVenta = NotaVenta::create([
        'fecha_venta' => now(),
        'monto_total' => $total,
        'estado' => 'pendiente',
        'id_cliente' => $clienteId,
        'id_empleado' => $empleadoId,
    ]);

    foreach ($cart as $item) {
        DetalleVenta::create([
            'id_nota_venta' => $notaVenta->id_nota_venta,
            'id_almacen' => $item['id_almacen'],
            'id_item' => $item['id_item'],
            'cantidad' => $item['cantidad'],
            'precio' => $item['precio'],
        ]);
    }

    // Llamar a Libélula
    try {
        $resultado = $libelula->registrarPago($notaVenta);
    } catch (\Exception $e) {
        Log::error('Error Libélula: ' . $e->getMessage());
        session()->forget('cart');
        return redirect()->route('landing')->with('error', 'Error al conectar con la pasarela de pago.');
    }

    
    if ($resultado['success'] && !empty($resultado['url_pasarela'])) {
        session()->forget('cart');
        return redirect()->away($resultado['url_pasarela']);
    }

    // Si falló
    session()->forget('cart');
    return redirect()->route('landing')->with('error', $resultado['message'] ?? 'Error al procesar el pago');
}

private function obtenerOCrearClienteAnonimo()
{
    // Buscar por teléfono único (asumiendo que 0000000000 es para anónimos)
    $clienteAnonimo = Cliente::where('nombre', 'Cliente')
        ->where('apellido', 'Anónimo')
        ->first();

    if (!$clienteAnonimo) {
        $clienteAnonimo = Cliente::create([
            'nombre' => 'Cliente',
            'apellido' => 'Anónimo',
            'telefono' => '0000000000'
        ]);

        Log::info('Cliente anónimo creado', ['id_cliente' => $clienteAnonimo->id_cliente]);
    }

    return $clienteAnonimo;
}

    private function calcularTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }

     // En VentaController.php
public function webhookPagoExitoso(Request $request)
{
    $transactionId = $request->get('transaction_id');
    $identificador = $request->get('identificador'); // ← Libélula debería enviar esto

    Log::info('Webhook Libélula recibido', $request->all());

    // Buscar por id_transaccion_libelula O por identificador
    $transaccion = null;

    if ($transactionId) {
        $transaccion = TransaccionLibelula::where('id_transaccion_libelula', $transactionId)->first();
    }

    if (!$transaccion && $identificador) {
        $transaccion = TransaccionLibelula::where('identificador', $identificador)->first();
    }

    if ($transaccion) {
        $transaccion->update(['estado' => 'pagado']);
        $notaVenta = $transaccion->notaVenta;
        if ($notaVenta) {
            $notaVenta->update(['estado' => 'completado']);
        }
    }

    return response()->json(['success' => true]);
}

    public function verificarPago($id)
    {
        $notaVenta = NotaVenta::findOrFail($id);
        $transaccion = $notaVenta->transaccionLibelula;

        return response()->json([
            'pagado' => $transaccion && $transaccion->estado === 'pagado'
        ]);
    }

    public function pagoExito($id)
    {
        $notaVenta = NotaVenta::findOrFail($id);
        return redirect()->route('landing')->with('success', '¡Pago confirmado! Gracias por tu compra.');
    }

    /**
 * Cambiar estado de venta pendiente a completado
 */
/**
 * Cambiar estado de venta pendiente a completado
 */
public function completarVenta($id)
{
    try {
        DB::beginTransaction();

        $notaVenta = NotaVenta::findOrFail($id);

        // Verificar que esté pendiente
        if ($notaVenta->estado !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden completar ventas pendientes'
            ], 422);
        }

        // Obtener usuario actual
        $usuario = Auth::user();
        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Si el usuario tiene un empleado asociado, usarlo
        if ($usuario->empleado) {
            $notaVenta->id_empleado = $usuario->empleado->id_empleado;
            Log::info('Asignando empleado a la venta', [
                'id_venta' => $id,
                'id_empleado' => $usuario->empleado->id_empleado
            ]);
        } elseif (!$notaVenta->id_empleado) {
            // Si no tiene empleado y el usuario no es empleado, asignar el primer empleado
            $primerEmpleado = Empleado::first();
            if ($primerEmpleado) {
                $notaVenta->id_empleado = $primerEmpleado->id_empleado;
            }
        }

        // Actualizar estado
        $notaVenta->estado = 'completado';
        $notaVenta->save();

        // Procesar detalles si existen
        if ($notaVenta->detalles->count() > 0) {
            foreach ($notaVenta->detalles as $detalle) {
                // Verificar stock antes de descontar
                $almacenItem = DB::table('almacen_item')
                    ->where('id_almacen', $detalle->id_almacen)
                    ->where('id_item', $detalle->id_item)
                    ->first();

                if ($almacenItem) {
                    if ($almacenItem->stock < $detalle->cantidad) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Stock insuficiente para completar la venta. Producto ID: {$detalle->id_item}"
                        ], 422);
                    }

                    // Descontar stock
                    DB::table('almacen_item')
                        ->where('id_almacen', $detalle->id_almacen)
                        ->where('id_item', $detalle->id_item)
                        ->decrement('stock', $detalle->cantidad);
                }
            }
        }

        DB::commit();

        Log::info('Venta completada manualmente', [
            'id_nota_venta' => $id,
            'usuario_id' => $usuario->id_usuario,
            'id_empleado' => $notaVenta->id_empleado
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Venta #' . $id . ' completada exitosamente',
            'nota_venta' => $notaVenta->load(['cliente', 'empleado'])
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al completar venta: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Error al completar la venta: ' . $e->getMessage()
        ], 500);
    }
}
}


