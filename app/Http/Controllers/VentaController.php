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
use Illuminate\Support\Str;
use App\Models\ConfiguracionInventario;
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
                        $metodoValuacion = ConfiguracionInventario::obtener()->metodo_valuacion_predeterminado;

                        \App\Models\LoteInventario::consumir(
                            $detalle['id_almacen'],
                            $detalle['id_item'],
                            $detalle['cantidad'],
                            $metodoValuacion
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

    public function buscar(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return redirect()->route('landing');
        }

        $productos = DB::table('almacen_item')
            ->join('items', 'almacen_item.id_item', '=', 'items.id_item')
            ->join('productos', 'items.id_item', '=', 'productos.id_item')
            ->join('almacenes', 'almacen_item.id_almacen', '=', 'almacenes.id_almacen')
            ->leftJoin('categoria_producto', 'productos.id_cat_producto', '=', 'categoria_producto.id_cat_producto')
            ->where('items.tipo_item', 'producto')
            ->where('almacen_item.stock', '>', 0)
            ->where(function ($q) use ($query) {
                $q->where('items.nombre', 'LIKE', "%{$query}%")
                ->orWhere('categoria_producto.nombre', 'LIKE', "%{$query}%");
            })
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
                    $imagenUrl = filter_var($item->imagen, FILTER_VALIDATE_URL) 
                        ? $item->imagen 
                        : Storage::url($item->imagen);
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

        return view('buscar', compact('productos', 'query'));
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
        $nota = NotaVenta::with(['cliente', 'empleado'])->findOrFail($id);
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
    // Agrupar productos por nombre, eligiendo automáticamente el almacén con mayor stock
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
        ->orderBy('almacen_item.stock', 'desc') // Priorizar almacenes con más stock
        ->get()
        ->groupBy('nombre') // Agrupar por nombre del producto
        ->map(function($grupo) {
            // Tomar el primer producto del grupo (el que tiene más stock)
            $producto = $grupo->first();
            
            // Calcular stock total
            $stockTotal = $grupo->sum('stock');
            
            // Determinar nivel de stock (sin números)
            $nivelStock = $this->getNivelStockVisual($stockTotal);
            
            $imagenUrl = null;
            if ($producto->imagen) {
                if (Str::startsWith($producto->imagen, ['http://', 'https://'])) {
                    $imagenUrl = $producto->imagen;
                } else {
                    $imagenUrl = asset('storage/' . $producto->imagen);
                }
            }
            
            return (object)[
                'id_almacen' => $producto->id_almacen, // Almacén con más stock
                'id_item' => $producto->id_item,
                'nombre' => $producto->nombre,
                'precio' => floatval($producto->precio),
                'stock_total' => $stockTotal,
                'nivel_stock' => $nivelStock,
                'imagen' => $imagenUrl,
                'categoria' => $producto->categoria ?? 'Producto',
                'descripcion' => ''
            ];
        })
        ->values();

    return view('PanaderiaOtto', compact('productosConStock'));
}

    /**
     * Determinar nivel de stock VISUAL (sin números)
     */
    private function getNivelStockVisual($stock)
    {
        if ($stock <= 0) {
            return [
                'texto' => 'Agotado',
                'clase' => 'danger',
                'icono' => 'fa-times-circle',
                'mensaje' => 'Producto agotado',
                'barra_width' => 0,
                'disabled' => true
            ];
        } elseif ($stock < 10) {
            return [
                'texto' => '¡Últimas unidades!',
                'clase' => 'warning',
                'icono' => 'fa-exclamation-triangle',
                'mensaje' => '¡Apresúrate! Pocas unidades disponibles',
                'barra_width' => 15,
                'disabled' => false
            ];
        } elseif ($stock < 30) {
            return [
                'texto' => 'Stock disponible',
                'clase' => 'info',
                'icono' => 'fa-chart-line',
                'mensaje' => 'Disponible para tu pedido',
                'barra_width' => 50,
                'disabled' => false
            ];
        } else {
            return [
                'texto' => 'Stock alto',
                'clase' => 'success',
                'icono' => 'fa-check-circle',
                'mensaje' => 'Producto disponible',
                'barra_width' => 100,
                'disabled' => false
            ];
        }
    }

    /**
     * Determinar nivel de stock visual
     */
    private function getNivelStock($stock)
    {
        if ($stock <= 0) {
            return ['texto' => 'Agotado', 'clase' => 'danger', 'icono' => 'fa-times-circle'];
        } elseif ($stock < 10) {
            return ['texto' => '¡Últimas unidades!', 'clase' => 'warning', 'icono' => 'fa-exclamation-triangle'];
        } elseif ($stock < 30) {
            return ['texto' => 'Stock disponible', 'clase' => 'info', 'icono' => 'fa-chart-line'];
        } else {
            return ['texto' => 'Stock alto', 'clase' => 'success', 'icono' => 'fa-check-circle'];
        }
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
    Log::info('=== INICIO PROCESAR PEDIDO ===');
    
    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return redirect()->route('landing')->with('error', 'Carrito vacío');
    }

    $total = $this->calcularTotal($cart);
    $clienteId = null;
    $empleadoId = null;
    $nombreCliente = 'Cliente Anónimo';
    $apellidoCliente = '';
    $emailCliente = 'cliente@panaderiaotto.com';

    if (Auth::check()) {
    $usuario = Auth::user();
    Log::info('Usuario autenticado:', [
        'id' => $usuario->id_usuario,
        'correo' => $usuario->correo,
        'tipo_usuario' => $usuario->tipo_usuario,
        'tiene_cliente' => $usuario->cliente ? 'si' : 'no',
        'tiene_empleado' => $usuario->empleado ? 'si' : 'no',
        'id_cliente' => $usuario->id_cliente,
        'id_empleado' => $usuario->id_empleado
    ]);

    // ✅ Usar la relación cliente()
    if ($usuario->cliente) {
        $clienteId = $usuario->cliente->id_cliente;
        $nombreCliente = $usuario->cliente->nombre;
        $apellidoCliente = $usuario->cliente->apellido ?? '';
        $emailCliente = $usuario->correo;
        Log::info('Cliente real asignado: ' . $nombreCliente . ' ' . $apellidoCliente);
    }
    // ✅ Usar la relación empleado()
    elseif ($usuario->empleado) {
        $empleadoId = $usuario->empleado->id_empleado;
        $nombreEmpleado = $usuario->empleado->nombre;
        $apellidoEmpleado = $usuario->empleado->apellido ?? '';
        $emailCliente = $usuario->correo;
        
        // Buscar o crear cliente con el nombre del empleado
        $clienteExistente = Cliente::where('nombre', $nombreEmpleado)
            ->where('apellido', $apellidoEmpleado)
            ->first();
        
        if ($clienteExistente) {
            $clienteId = $clienteExistente->id_cliente;
            $nombreCliente = $clienteExistente->nombre;
            $apellidoCliente = $clienteExistente->apellido ?? '';
            Log::info('Cliente encontrado para empleado: ' . $nombreCliente . ' ' . $apellidoCliente);
        } else {
            $nuevoCliente = Cliente::create([
                'nombre' => $nombreEmpleado,
                'apellido' => $apellidoEmpleado,
                'telefono' => $usuario->empleado->telefono ?? '0000000000'
            ]);
            $clienteId = $nuevoCliente->id_cliente;
            $nombreCliente = $nuevoCliente->nombre;
            $apellidoCliente = $nuevoCliente->apellido ?? '';
            Log::info('Cliente creado desde empleado: ' . $nombreCliente . ' ' . $apellidoCliente);
        }
    }
    else {
        Log::warning('Usuario sin cliente ni empleado asociado');
        $clienteAnonimo = $this->obtenerOCrearClienteAnonimo();
        $clienteId = $clienteAnonimo->id_cliente;
        $nombreCliente = $clienteAnonimo->nombre;
        $apellidoCliente = $clienteAnonimo->apellido ?? '';
    }
}

    Log::info('Datos para nota venta:', [
        'cliente_id' => $clienteId,
        'empleado_id' => $empleadoId,
        'nombre_cliente' => $nombreCliente,
        'apellido_cliente' => $apellidoCliente,
        'email_cliente' => $emailCliente
    ]);

    // Crear nota de venta como PENDIENTE
    $notaVenta = NotaVenta::create([
        'fecha_venta' => now(),
        'monto_total' => $total,
        'estado'      => 'pendiente',
        'metodo_pago' => 'qr',
        'id_cliente'  => $clienteId,
        'id_empleado' => $empleadoId,
    ]);

    foreach ($cart as $item) {
        DetalleVenta::create([
            'id_nota_venta' => $notaVenta->id_nota_venta,
            'id_almacen'    => $item['id_almacen'],
            'id_item'       => $item['id_item'],
            'cantidad'      => $item['cantidad'],
            'precio'        => $item['precio'],
        ]);
    }

    // Llamar a Libélula con el email correcto
    try {
        $resultado = $libelula->registrarPago($notaVenta, [
            'nombre_cliente' => $nombreCliente,
            'apellido_cliente' => $apellidoCliente,
            'email_cliente' => $emailCliente
        ]);
    } catch (\Exception $e) {
        Log::error('Error Libélula: ' . $e->getMessage());
        session()->forget('cart');
        return redirect()->route('landing')->with('error', 'Error al conectar con la pasarela de pago.');
    }

    if ($resultado['success'] && !empty($resultado['url_pasarela'])) {
        session()->forget('cart');
        return view('pago.mostrar', [
            'notaVenta'      => $notaVenta,
            'qr_url'         => $resultado['qr_url'] ?? null,
            'url_pasarela'   => $resultado['url_pasarela'],
            'id_transaccion' => $resultado['id_transaccion'] ?? null,
        ]);
    }

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
    Log::info('=== WEBHOOK - INICIO ===');
    
    $transactionId = $request->get('transaction_id') ?? $request->query('transaction_id');
    $identificador = $request->get('identificador') ?? $request->query('identificador');

    if (!$transactionId && !$identificador) {
        Log::error('Webhook sin identificadores');
        return response()->json(['error' => 'No identifiers'], 400);
    }

    $transaccion = null;
    if ($transactionId) {
        $transaccion = TransaccionLibelula::where('id_transaccion_libelula', $transactionId)->first();
    }
    if (!$transaccion && $identificador) {
        $transaccion = TransaccionLibelula::where('identificador', $identificador)->first();
    }

    if ($transaccion) {
        // ✅ SOLO marcar la transacción como pagada
        // NO completar la venta automáticamente
        $transaccion->update(['estado' => 'pagado']);
        
        Log::info('Transacción marcada como pagada', [
            'id' => $transaccion->id,
            'identificador' => $transaccion->identificador
        ]);
        
        // ✅ Opcional: Puedes notificar que el pago está pendiente de verificación
        Log::info('Pago recibido, pendiente de verificación por empleado');
    } else {
        Log::error('Transacción no encontrada');
    }

    return response()->json(['success' => true]);
}
    private function ejecutarDescuentoInventario(NotaVenta $notaVenta)
{
    Log::info('=== INICIO ejecutarDescuentoInventario para venta #' . $notaVenta->id_nota_venta);

    $metodoValuacion = ConfiguracionInventario::obtener()->metodo_valuacion_predeterminado;
    Log::info('Método valuación: ' . $metodoValuacion);

    foreach ($notaVenta->detalles as $detalle) {
        Log::info('Procesando detalle:', [
            'id_detalle' => $detalle->id_detalle_venta,
            'id_almacen' => $detalle->id_almacen,
            'id_item' => $detalle->id_item,
            'cantidad' => $detalle->cantidad,
            'precio' => $detalle->precio
        ]);

        // Verificar stock usando DB::table para evitar errores de modelo
        $almacenItem = DB::table('almacen_item')
            ->where('id_almacen', $detalle->id_almacen)
            ->where('id_item', $detalle->id_item)
            ->first();

        if (!$almacenItem) {
            Log::error("No existe AlmacenItem para (almacen,item): ({$detalle->id_almacen}, {$detalle->id_item})");
            continue;
        }

        Log::info('Stock actual antes de descontar: ' . $almacenItem->stock);

        if ($almacenItem->stock < $detalle->cantidad) {
            Log::error("Stock insuficiente. Stock: {$almacenItem->stock}, requerido: {$detalle->cantidad}");
            continue;
        }

        // Descontar stock usando DB::table
        DB::table('almacen_item')
            ->where('id_almacen', $detalle->id_almacen)
            ->where('id_item', $detalle->id_item)
            ->decrement('stock', $detalle->cantidad);
        
        Log::info('Stock después de descontar');

        // Consumir lotes
        if (class_exists(\App\Models\LoteInventario::class)) {
            try {
                Log::info('Llamando a LoteInventario::consumir', [
                    'almacen' => $detalle->id_almacen,
                    'item' => $detalle->id_item,
                    'cantidad' => $detalle->cantidad,
                    'metodo' => $metodoValuacion
                ]);

                $resultado = \App\Models\LoteInventario::consumir(
                    $detalle->id_almacen,
                    $detalle->id_item,
                    $detalle->cantidad,
                    $metodoValuacion,
                    $notaVenta->id_nota_venta,
                    'venta'
                );
                Log::info('Resultado de consumir lotes:', (array)$resultado);
            } catch (\Exception $e) {
                Log::error('Error al consumir lote: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            }
        } else {
            Log::warning('Clase LoteInventario no existe');
        }

        // Registrar movimiento
        if (class_exists(\App\Models\MovimientoInventario::class)) {
            try {
                \App\Models\MovimientoInventario::registrar([
                    'tipo_movimiento' => 'egreso',
                    'id_almacen'      => $detalle->id_almacen,
                    'id_item'         => $detalle->id_item,
                    'cantidad'        => -$detalle->cantidad,
                    'precio_unitario' => $detalle->precio,
                    'costo_total'     => -($detalle->cantidad * $detalle->precio),
                    'referencia_id'   => $notaVenta->id_nota_venta,
                    'referencia_tipo' => 'venta',
                    'observaciones'   => 'Egreso automático por pago confirmado - Venta #' . $notaVenta->id_nota_venta,
                ]);
                Log::info('Movimiento de inventario registrado');
            } catch (\Exception $e) {
                Log::error('Error al registrar movimiento: ' . $e->getMessage());
            }
        }
    }
    Log::info('=== FIN ejecutarDescuentoInventario ===');
}

    public function verificarPago($id)
{
    Log::info('=== VERIFICAR PAGO para venta #' . $id);
    
    $notaVenta = NotaVenta::findOrFail($id);
    $transaccion = $notaVenta->transaccionLibelula;

    if (!$transaccion) {
        return response()->json(['pagado' => false, 'mensaje' => 'Sin transacción']);
    }

    // Si ya está completada, no hacer nada
    if ($notaVenta->estado === 'completado') {
        return response()->json(['pagado' => true, 'completado' => true]);
    }

    // Consultar estado real a Libélula
    try {
        $resultado = $this->libelulaService->consultarPago($transaccion->identificador);
        Log::info('Resultado consulta pago:', $resultado);
        
        if ($resultado['success'] && $resultado['pagado']) {
            // ✅ Solo actualizar el estado de la transacción, NO completar venta
            $transaccion->update(['estado' => 'pagado']);
            
            Log::info('Pago confirmado por Libélula, pendiente de verificación manual', [
                'nota_venta_id' => $id,
                'identificador' => $transaccion->identificador
            ]);
            
            // ✅ Retornar que el pago está confirmado pero la venta no está completada
            return response()->json([
                'pagado' => true, 
                'completado' => false,
                'mensaje' => 'Pago confirmado. Esperando verificación del empleado.'
            ]);
        }
    } catch (\Exception $e) {
        Log::error('Error al consultar pago: ' . $e->getMessage());
    }

    return response()->json(['pagado' => false]);
}

    public function forzarVerificacionPago($id)
{
    $notaVenta = NotaVenta::findOrFail($id);
    $transaccion = $notaVenta->transaccionLibelula;

    if (!$transaccion) {
        return back()->with('error', 'Esta venta no tiene una transacción de Libélula asociada.');
    }

    if ($notaVenta->estado === 'completado') {
        return back()->with('success', 'La venta ya estaba completada.');
    }

    if ($transaccion->estado === 'pagado') {
        // ✅ Si el pago ya está confirmado, mostrar botón para completar manualmente
        return view('admin.ventas.confirmar_pago', [
            'notaVenta' => $notaVenta,
            'transaccion' => $transaccion,
            'mensaje' => 'El pago ha sido confirmado. Complete la venta manualmente.'
        ]);
    }

    try {
        $resultado = $this->libelulaService->consultarPago($transaccion->identificador);
        if ($resultado['success'] && $resultado['pagado']) {
            $transaccion->update(['estado' => 'pagado']);
            
            return view('admin.ventas.confirmar_pago', [
                'notaVenta' => $notaVenta,
                'transaccion' => $transaccion,
                'mensaje' => '¡Pago verificado! Complete la venta manualmente.'
            ]);
        } else {
            return back()->with('warning', 'El pago aún no se ha realizado según Libélula.');
        }
    } catch (\Exception $e) {
        Log::error('Error al verificar pago manual: ' . $e->getMessage());
        return back()->with('error', 'Error al conectar con Libélula.');
    }
}

public function completarVentaManual($id)
{
    $notaVenta = NotaVenta::findOrFail($id);
    
    // Solo se puede completar si el pago está confirmado
    $transaccion = $notaVenta->transaccionLibelula;
    if (!$transaccion || $transaccion->estado !== 'pagado') {
        return back()->with('error', 'No se puede completar una venta sin pago confirmado.');
    }
    
    if ($notaVenta->estado === 'completado') {
        return back()->with('success', 'La venta ya estaba completada.');
    }
    
    DB::beginTransaction();
    try {
        // Ejecutar descuento de inventario
        $this->ejecutarDescuentoInventario($notaVenta);
        
        // Completar la venta
        $notaVenta->update([
            'estado' => 'completado',
            'metodo_pago' => 'qr'
        ]);
        
        DB::commit();
        
        Log::info('Venta #' . $id . ' completada manualmente por empleado', [
            'usuario_id' => Auth::id(),
            'empleado_id' => Auth::user()->empleado->id_empleado ?? null
        ]);
        
        return redirect()->route('ventas.index')->with('success', 'Venta #' . $id . ' completada exitosamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al completar venta manual: ' . $e->getMessage());
        return back()->with('error', 'Error al completar la venta: ' . $e->getMessage());
    }
}


    public function pagoExito($id)
        {
            $notaVenta = NotaVenta::findOrFail($id);
            return redirect()->route('landing')->with('success', '¡Pago confirmado! Gracias por tu compra.');
        }

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

            // Asignar empleado si no tiene
            if ($usuario->empleado) {
                $notaVenta->id_empleado = $usuario->empleado->id_empleado;
            } elseif (!$notaVenta->id_empleado) {
                $primerEmpleado = Empleado::first();
                if ($primerEmpleado) {
                    $notaVenta->id_empleado = $primerEmpleado->id_empleado;
                }
            }

            // Actualizar estado
            $notaVenta->estado = 'completado';
            $notaVenta->save();

            // Obtener método de valuación
            $metodoValuacion = ConfiguracionInventario::obtener()->metodo_valuacion_predeterminado;

            // Procesar detalles y descontar inventario
            if ($notaVenta->detalles->count() > 0) {
                foreach ($notaVenta->detalles as $detalle) {
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

                        DB::table('almacen_item')
                            ->where('id_almacen', $detalle->id_almacen)
                            ->where('id_item', $detalle->id_item)
                            ->decrement('stock', $detalle->cantidad);

                        if (class_exists(\App\Models\LoteInventario::class)) {
                            try {
                                \App\Models\LoteInventario::consumir(
                                    $detalle->id_almacen,
                                    $detalle->id_item,
                                    $detalle->cantidad,
                                    $metodoValuacion
                                );
                            } catch (\Exception $e) {
                                Log::warning('Error al consumir lote en venta manual: ' . $e->getMessage());
                            }
                        }
                    }
                }
            }

            DB::commit();

            Log::info('Venta completada manualmente', [
                'id_nota_venta' => $id,
                'usuario_id'    => $usuario->id_usuario,
                'id_empleado'   => $notaVenta->id_empleado,
                'metodo_valuacion' => $metodoValuacion
            ]);

            return response()->json([
                'success'    => true,
                'message'    => 'Venta #' . $id . ' completada exitosamente',
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

   public function reintentarPago($id)
{
    $notaVenta = NotaVenta::findOrFail($id);
    
    // Verificar autenticación
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Debes iniciar sesión para continuar.');
    }
    
    $usuario = Auth::user();
    
    // Obtener el ID del cliente (puede ser del usuario o del empleado)
    $clienteId = null;
    
    if ($usuario->cliente) {
        $clienteId = $usuario->cliente->id_cliente;
    } elseif ($usuario->empleado) {
        // Buscar cliente con el nombre del empleado
        $empleado = $usuario->empleado;
        $cliente = \App\Models\Cliente::where('nombre', $empleado->nombre)
            ->where('apellido', $empleado->apellido ?? '')
            ->first();
        if ($cliente) {
            $clienteId = $cliente->id_cliente;
        }
    }
    
    // Verificar que el pedido pertenezca al cliente
    if ($clienteId != $notaVenta->id_cliente) {
        abort(403, 'No autorizado');
    }
    
    if ($notaVenta->estado !== 'pendiente') {
        return redirect()->route('landing')->with('info', 'Este pedido ya fue procesado.');
    }
    
    // Obtener la transacción existente
    $transaccion = $notaVenta->transaccionLibelula;
    
    // Si ya pagó, redirigir con mensaje
    if ($transaccion && $transaccion->estado === 'pagado') {
        return redirect()->route('landing')->with('success', 'Este pedido ya fue pagado. Gracias por tu compra.');
    }
    
    // Si tiene URL de pago, mostrar la misma
    if ($transaccion && $transaccion->url_pasarela) {
        return view('cliente.reintentar-pago', [
            'notaVenta' => $notaVenta,
            'qr_url' => $transaccion->qr_url,
            'url_pasarela' => $transaccion->url_pasarela,
            'id_transaccion' => $transaccion->id_transaccion_libelula,
        ]);
    }
    
    // Si no hay transacción, crear una nueva
    try {
        $resultado = app(\App\Services\LibelulaService::class)->registrarPago($notaVenta, [
            'nombre_cliente' => $notaVenta->cliente->nombre ?? 'Cliente',
            'apellido_cliente' => $notaVenta->cliente->apellido ?? '',
            'email_cliente' => Auth::user()->correo,
        ]);
        
        if ($resultado['success'] && !empty($resultado['url_pasarela'])) {
            return view('cliente.reintentar-pago', [
                'notaVenta' => $notaVenta,
                'qr_url' => $resultado['qr_url'] ?? null,
                'url_pasarela' => $resultado['url_pasarela'],
                'id_transaccion' => $resultado['id_transaccion'] ?? null,
            ]);
        } else {
            return redirect()->route('mis-pedidos')->with('error', $resultado['message'] ?? 'Error al generar el pago');
        }
    } catch (\Exception $e) {
        \Log::error('Error reintentar pago: ' . $e->getMessage());
        return redirect()->route('mis-pedidos')->with('error', 'Error al procesar el pago. Intente más tarde.');
    }
}

}