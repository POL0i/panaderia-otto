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

class VentaController extends Controller
{
    public function index()
    {
        $almacenes = Almacen::all();
        
        // Obtener items de tipo producto con su producto y stock en cada almacén (para mostrar en select)
        $items = Item::where('tipo_item', 'producto')
            ->with(['producto', 'almacenItems'])
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
            ->join('productos', 'items.id_item', '=', 'productos.id_item')
            ->select('detalles_venta.*', 'notas_venta.id_nota_venta', 'almacenes.nombre as almacen_nombre', 'productos.nombre as producto_nombre')
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
            // 1. Validación
            $validated = $request->validate([
                'id_cliente' => 'required|exists:clientes,id_cliente',
                'detalles'   => 'required|array|min:1',
                'detalles.*.id_almacen' => 'required|exists:almacenes,id_almacen',
                'detalles.*.id_item'    => 'required|exists:items,id_item',
                'detalles.*.cantidad'   => 'required|integer|min:1',
                'detalles.*.precio'     => 'required|numeric|min:0.01',
            ]);

            // 2. Iniciar transacción
            DB::beginTransaction();

            // 3. Obtener empleado
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

            // 4. Calcular total
            $montoTotal = collect($validated['detalles'])->sum(fn($d) => $d['cantidad'] * $d['precio']);

            // 5. Crear nota de venta
            $notaVenta = NotaVenta::create([
                'fecha_venta' => now(),
                'monto_total' => $montoTotal,
                'estado'      => 'completado',
                'id_cliente'  => $validated['id_cliente'],
                'id_empleado' => $idEmpleado,
            ]);

            // 6. Procesar cada detalle
            foreach ($validated['detalles'] as $detalle) {
                // Verificar existencia y stock en almacen_item
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

                // Crear detalle de venta
                DetalleVenta::create([
                    'id_nota_venta' => $notaVenta->id_nota_venta,
                    'id_almacen'    => $detalle['id_almacen'],
                    'id_item'       => $detalle['id_item'],
                    'cantidad'      => $detalle['cantidad'],
                    'precio'        => $detalle['precio'],
                ]);

                // Descontar stock usando Query Builder (evita error con PK compuesta)
                DB::table('almacen_item')
                    ->where('id_almacen', $detalle['id_almacen'])
                    ->where('id_item', $detalle['id_item'])
                    ->decrement('stock', $detalle['cantidad']);
            }

            // 7. Confirmar transacción
            DB::commit();

            // 8. Cargar relaciones para la respuesta
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

    // APIs para refrescar selects
    public function getClientes()
    {
        return response()->json(['clientes' => Cliente::all(['id_cliente', 'nombre', 'telefono'])]);
    }

    public function getAlmacenes()
    {
        return response()->json(['almacenes' => Almacen::all(['id_almacen', 'nombre'])]);
    }

    // Obtener items con stock por almacén (para refrescar)
    public function getItems()
    {
        $items = Item::where('tipo_item', 'producto')
            ->with('producto')
            ->get()
            ->map(function($item) {
                return [
                    'id_item' => $item->id_item,
                    'nombre' => $item->producto->nombre ?? 'Producto',
                    'unidad' => $item->unidad_medida ?? 'unidad'
                ];
            });
        return response()->json(['items' => $items]);
    }

    // Obtener stock de un producto en un almacén específico (para validación dinámica)
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
            ->join('productos', 'items.id_item', '=', 'productos.id_item')
            ->where('detalles_venta.id_nota_venta', $id)
            ->select('detalles_venta.*', 'almacenes.nombre as almacen_nombre', 'productos.nombre as producto_nombre')
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

            // Obtener nota de venta con todas sus relaciones
            $notaVenta = NotaVenta::with([
                'cliente', 
                'empleado', 
                'detalles.almacen', 
                'detalles.item.producto'
            ])->findOrFail($idVenta);

            // Enviar correo
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
        $almacenesItems = AlmacenItem::with(['almacen', 'item.producto'])
            ->whereHas('item', function($query) {
                $query->where('tipo_item', 'producto');
            })
            ->get();
        
        $productos = [];
        
        foreach ($almacenesItems as $ai) {
            if ($ai->item && $ai->item->producto) {
                // Procesar imagen correctamente
                $imagen = null;
                if ($ai->item->producto->imagen) {
                    // Si es URL externa
                    if (filter_var($ai->item->producto->imagen, FILTER_VALIDATE_URL)) {
                        $imagen = $ai->item->producto->imagen;
                    } 
                    // Si es ruta de storage
                    else {
                        $imagen = Storage::url($ai->item->producto->imagen);
                    }
                }
                
                $productos[] = [
                    'id_almacen' => $ai->id_almacen,
                    'almacen_nombre' => $ai->almacen->nombre,
                    'id_item' => $ai->id_item,
                    'producto_nombre' => $ai->item->producto->nombre,
                    'stock' => $ai->stock,
                    'precio' => $ai->item->producto->precio,
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

    //-- Seccion para clientes --//
    public function landingPage()
{
    // Datos estáticos de prueba
    $productosConStock = collect([
        (object)[
            'id_almacen' => 1,
            'id_item' => 1,
            'nombre' => 'Pastel de Queso',
            'precio' => 222.00,
            'stock' => 222,
            'imagen' => 'productos/GE0tW0EWBWB05jT5lHx1SziAAFQEBgcjLD2BCETh.jpg',
            'almacen_nombre' => 'Almacén Principal',
            'categoria' => 'Pastelería',
            'descripcion' => 'Delicioso pastel de queso artesanal'
        ],
        (object)[
            'id_almacen' => 4,
            'id_item' => 3,
            'nombre' => 'Dona',
            'precio' => 21.00,
            'stock' => 2100,
            'imagen' => 'productos/1nPgCUdttWG1YKgrlyLXsgWlnG9W2QWCC1LCUg9g.jpg',
            'almacen_nombre' => 'Almacén de Todo',
            'categoria' => 'Panadería',
            'descripcion' => 'Dona esponjosa y deliciosa'
        ]
    ]);
    
    return view('PanaderiaOtto', compact('productosConStock'));
    dd($productosConStock);
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
        // 1. Verificar productos en la tabla productos
        $productos = Producto::with('item')->get();
        
        // 2. Verificar items
        $items = Item::where('tipo_item', 'producto')->get();
        
        // 3. Verificar almacen_item
        $almacenItems = AlmacenItem::with(['item.producto', 'almacen'])->get();
        
        return response()->json([
            'productos' => $productos,
            'items' => $items,
            'almacen_items' => $almacenItems,
            'total_productos' => $productos->count(),
            'total_items' => $items->count(),
            'total_almacen_items' => $almacenItems->count()
        ]);
    }
}