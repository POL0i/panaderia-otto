<?php
// app/Http/Controllers/CompraController.php

namespace App\Http\Controllers;

use App\Models\NotaCompra;
use App\Models\DetalleCompra;
use App\Models\Almacen;
use App\Models\Item;
use App\Models\Insumo;
use App\Models\Proveedor;
use App\Models\Empleado;
use App\Models\AlmacenItem;
use App\Models\Ppersona;
use App\Models\Pempresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\MailService;

class CompraController extends Controller
{
    /**
     * Display the purchases section.
     */
    public function index()
    {
        // Obtener datos para los selects - SIN usar 'estado'
        $almacenes = Almacen::all();
        
        // Obtener items que son insumos - SIN usar 'estado'
        // La relación es: Item tiene un insumo, no al revés
        $items = Item::whereHas('insumo') // Solo items que tienen un insumo asociado
            ->with('insumo')
            ->get();
        
        $proveedores = Proveedor::with(['persona', 'empresa'])->get();
        $empleados = Empleado::all();
        
        // Obtener notas de compra recientes
        $notasCompra = NotaCompra::with(['empleado', 'proveedor', 'detalles'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Obtener detalles de compra recientes
        $detallesCompra = DetalleCompra::with(['notaCompra', 'almacen', 'item.insumo'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        
        // Categorías para el modal de insumo (si existen)
        $categoriasInsumo = [];
        if (class_exists(\App\Models\CategoriaInsumo::class)) {
            $categoriasInsumo = \App\Models\CategoriaInsumo::all();
        }
        
        return view('seccion-compras.index', compact(
            'almacenes', 'items', 'proveedores', 'empleados', 
            'notasCompra', 'detallesCompra', 'categoriasInsumo'
        ));
    }

    /**
     * Store a new purchase note and its details.
     */
   public function store(Request $request)
{
    try {
        \Log::info('=== INICIO COMPRA ===', ['request' => $request->except(['detalles'])]);
        
        // 1. Validación
        $validated = $request->validate([
            'id_proveedor' => 'required|exists:proveedores,id_proveedor',
            'detalles' => 'required|array|min:1',
            'detalles.*.id_almacen' => 'required|exists:almacenes,id_almacen',
            'detalles.*.id_item' => 'required|exists:items,id_item',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio' => 'required|numeric|min:0.01',
        ]);
        
        \Log::info('Validación OK', ['detalles_count' => count($validated['detalles'])]);

        DB::beginTransaction();

        // 3. Obtener empleado
        $usuario = Auth::user();
        if (!$usuario) throw new \Exception('Usuario no autenticado');
        
        $idEmpleado = $usuario->empleado->id_empleado ?? Empleado::first()->id_empleado;
        \Log::info('Empleado OK', ['id' => $idEmpleado]);

        // 4. Validar capacidad
        $totalesPorAlmacen = [];
        foreach ($validated['detalles'] as $detalle) {
            $totalesPorAlmacen[$detalle['id_almacen']] = ($totalesPorAlmacen[$detalle['id_almacen']] ?? 0) + $detalle['cantidad'];
        }
        
        foreach ($totalesPorAlmacen as $idAlmacen => $cantidadTotal) {
            $almacen = Almacen::findOrFail($idAlmacen);
            if ($almacen->capacidad > 0) {
                $stockActual = DB::table('almacen_item')->where('id_almacen', $idAlmacen)->sum('stock');
                if (($stockActual + $cantidadTotal) > $almacen->capacidad) {
                    throw new \Exception("Capacidad excedida en '{$almacen->nombre}'");
                }
            }
        }
        \Log::info('Capacidad validada OK');

        // 5. Calcular total y crear nota
        $montoTotal = collect($validated['detalles'])->sum(fn($d) => $d['cantidad'] * $d['precio']);
        
        $notaCompra = NotaCompra::create([
            'fecha_compra' => now(),
            'monto_total' => $montoTotal,
            'estado' => 'completado',
            'id_proveedor' => $validated['id_proveedor'],
            'id_empleado' => $idEmpleado,
        ]);
        \Log::info('NotaCompra creada', ['id' => $notaCompra->id_nota_compra]);

        // 6. Procesar detalles
        foreach ($validated['detalles'] as $index => $detalle) {
            \Log::info("Procesando detalle #{$index}", ['item' => $detalle['id_item'], 'almacen' => $detalle['id_almacen']]);

            // Crear almacen_item si no existe
            if (!DB::table('almacen_item')->where('id_almacen', $detalle['id_almacen'])->where('id_item', $detalle['id_item'])->exists()) {
                DB::table('almacen_item')->insert([
                    'id_almacen' => $detalle['id_almacen'],
                    'id_item' => $detalle['id_item'],
                    'stock' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Crear DetalleCompra
            $detalleCompra = DetalleCompra::create([
                'id_nota_compra' => $notaCompra->id_nota_compra,
                'id_almacen' => $detalle['id_almacen'],
                'id_item' => $detalle['id_item'],
                'cantidad' => $detalle['cantidad'],
                'precio' => $detalle['precio'],
            ]);
            \Log::info("DetalleCompra creado", ['id' => $detalleCompra->id_detalle_compra ?? 'N/A']);

            // Incrementar stock
            DB::table('almacen_item')
                ->where('id_almacen', $detalle['id_almacen'])
                ->where('id_item', $detalle['id_item'])
                ->increment('stock', $detalle['cantidad']);
            \Log::info("Stock incrementado");

            // Movimiento de inventario
            try {
                \App\Models\MovimientoInventario::registrar([
                    'tipo_movimiento' => 'ingreso',
                    'id_almacen' => $detalle['id_almacen'],
                    'id_item' => $detalle['id_item'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio'],
                    'costo_total' => $detalle['cantidad'] * $detalle['precio'],
                    'referencia_id' => $notaCompra->id_nota_compra,
                    'referencia_tipo' => 'compra',
                    'observaciones' => 'Ingreso por compra #' . $notaCompra->id_nota_compra,
                ]);
                \Log::info("MovimientoInventario registrado OK");
            } catch (\Exception $e) {
                \Log::error("ERROR en MovimientoInventario: " . $e->getMessage());
                throw $e;
            }

            // Lote
            try {
                \App\Models\LoteInventario::desdeCompra($detalleCompra);
                \Log::info("Lote creado OK");
            } catch (\Exception $e) {
                \Log::error("ERROR en Lote: " . $e->getMessage());
                throw $e;
            }
        }

        DB::commit();
        \Log::info('=== COMPRA COMPLETADA ===');

        $notaCompra->load(['empleado', 'proveedor']);
        return response()->json([
            'success' => true,
            'message' => 'Compra registrada exitosamente',
            'nota_compra' => $notaCompra
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        \Log::error('VALIDATION ERROR', ['errors' => $e->errors()]);
        return response()->json([
            'success' => false,
            'message' => 'Error de validación: ' . implode(' ', $e->validator->errors()->all())
        ], 422);
        
    } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        \Log::error('SQL ERROR', [
            'message' => $e->getMessage(),
            'sql' => $e->getSql(),
            'bindings' => $e->getBindings()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Error en la base de datos al procesar la compra'
        ], 500);
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('GENERAL ERROR', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Get items by almacen.
     */
    public function getItemsByAlmacen($idAlmacen)
    {
        $items = AlmacenItem::where('id_almacen', $idAlmacen)
            ->with(['item.insumo', 'item.producto'])
            ->get()
            ->map(function($almacenItem) {
                $item = $almacenItem->item;
                $nombre = $item->insumo ? $item->insumo->nombre : ($item->producto ? $item->producto->nombre : 'Item');
                return [
                    'id_item' => $item->id_item,
                    'nombre' => $nombre,
                    'stock' => $almacenItem->stock,
                    'unidad' => $item->insumo ? ($item->insumo->unidad_medida ?? 'unidad') : 'unidad'
                ];
            });
        
        return response()->json(['items' => $items]);
    }

    /**
     * Get purchase note details.
     */
    public function getNotaCompra($id)
    {
        $notaCompra = NotaCompra::with(['empleado', 'proveedor', 'detalles.almacen', 'detalles.item.insumo'])
            ->findOrFail($id);
        
        return response()->json(['nota_compra' => $notaCompra]);
    }

    /**
     * Get purchase details.
     */
    public function getDetallesCompra()
    {
        $detalles = DetalleCompra::with(['notaCompra', 'almacen', 'item.insumo'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json(['detalles' => $detalles]);
    }

    /**
     * Store a new proveedor.
     */
    public function storeProveedor(Request $request)
    {
        $validated = $request->validate([
            'tipo_proveedor' => 'required|in:persona,empresa',
            'nombre_persona' => 'nullable|string|max:35',
            'razon_social' => 'nullable|string|max:35',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'correo' => 'nullable|email|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Crear el proveedor base
            $proveedor = Proveedor::create([
                'tipo_proveedor' => $validated['tipo_proveedor'],
                'telefono' => $validated['telefono'] ?? null,
                'direccion' => $validated['direccion'] ?? null,
                'correo' => $validated['correo'] ?? null,
            ]);
            
            // Crear el registro específico según el tipo
            if ($validated['tipo_proveedor'] === 'persona') {
                Ppersona::create([
                    'id_proveedor' => $proveedor->id_proveedor,
                    'nombre' => $validated['nombre_persona'],
                ]);
            } else {
                Pempresa::create([
                    'id_proveedor' => $proveedor->id_proveedor,
                    'razon_social' => $validated['razon_social'],
                ]);
            }
            
            DB::commit();
            
            // Cargar las relaciones para la respuesta
            $proveedor->load(['persona', 'empresa']);
            
            return response()->json([
                'success' => true,
                'proveedor' => $proveedor,
                'message' => 'Proveedor creado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear proveedor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new almacen.
     */
    public function storeAlmacen(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:almacenes,nombre',
            'ubicacion' => 'nullable|string|max:255',
            'capacidad' => 'nullable|numeric',
        ]);

        try {
            $almacen = Almacen::create($validated);
            
            return response()->json([
                'success' => true,
                'almacen' => $almacen,
                'message' => 'Almacén creado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear almacén: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new insumo (item).
     */
    public function storeInsumo(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'unidad_medida' => 'required|string|max:50',
            'precio_compra' => 'nullable|numeric|min:0',
            'id_cat_insumo' => 'nullable|exists:categoria_insumos,id_cat_insumo',
        ]);

        DB::beginTransaction();
        try {
            // Crear el item primero
            $item = Item::create([
                'tipo_item' => 'insumo',
                'unidad_medida' => $validated['unidad_medida'],
            ]);
            
            // Crear insumo asociado al item
            $insumo = Insumo::create([
                'id_item' => $item->id_item,
                'nombre' => $validated['nombre'],
                'precio_compra' => $validated['precio_compra'] ?? 0,
                'id_cat_insumo' => $validated['id_cat_insumo'] ?? null,
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'insumo' => $insumo,
                'item' => $item,
                'message' => 'Insumo creado exitosamente'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear insumo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all proveedores for refresh.
     */
    public function getProveedores()
    {
        $proveedores = Proveedor::with(['persona', 'empresa'])->get();
        
        $proveedores = $proveedores->map(function($proveedor) {
            $nombre = '';
            if ($proveedor->tipo_proveedor === 'persona' && $proveedor->persona) {
                $nombre = $proveedor->persona->nombre;
            } elseif ($proveedor->tipo_proveedor === 'empresa' && $proveedor->empresa) {
                $nombre = $proveedor->empresa->razon_social;
            }
            
            return [
                'id_proveedor' => $proveedor->id_proveedor,
                'nombre' => $nombre,
                'telefono' => $proveedor->telefono,
                'tipo_proveedor' => $proveedor->tipo_proveedor,
            ];
        });
        
        return response()->json(['proveedores' => $proveedores]);
    }

    /**
     * Get all almacenes for refresh.
     */
    public function getAlmacenes()
    {
        $almacenes = Almacen::whereIn('tipo_almacen', ['mixto', 'insumo'])->get();
        return response()->json(['almacenes' => $almacenes]);
    }

    /**
     * Get all items (insumos) for refresh.
     */
    public function getItems()
    {
        $items = Item::whereHas('insumo')
            ->with('insumo')
            ->get()
            ->map(function($item) {
                return [
                    'id_item' => $item->id_item,
                    'nombre' => $item->insumo->nombre,
                    'unidad' => $item->unidad_medida,
                ];
            });
        
        return response()->json(['items' => $items]);
    }

    public function enviarCorreoCompra(Request $request)
{
    $request->validate([
        'id_compra' => 'required|exists:notas_compra,id_nota_compra',
        'correo' => 'required|email'
    ]);

    try {
        $idCompra = $request->id_compra;
        $correoDestino = $request->correo;

        // Obtener la nota de compra con todas sus relaciones
        $notaCompra = NotaCompra::with([
            'empleado', 
            'proveedor.persona', 
            'proveedor.empresa',
            'detalles.almacen',
            'detalles.item.insumo'
        ])->findOrFail($idCompra);

        // Construir el contenido HTML del correo
        $html = $this->generarHtmlComprobante($notaCompra);

        // Enviar correo usando el servicio Mail
        $mailService = new MailService();
        $enviado = $mailService->sendEmail(
            $correoDestino,
            "Comprobante de Compra #{$notaCompra->id_nota_compra} - Panadería Otto",
            $html
        );

        if ($enviado) {
            return response()->json([
                'success' => true,
                'message' => 'Correo enviado exitosamente'
            ]);
        } else {
            throw new \Exception('No se pudo enviar el correo');
        }

    } catch (\Exception $e) {
        \Log::error('Error al enviar correo de compra: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al enviar el correo: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Generar HTML del comprobante para el correo
 */
private function generarHtmlComprobante($notaCompra)
{
    $proveedorNombre = '';
    if ($notaCompra->proveedor) {
        $proveedorNombre = $notaCompra->proveedor->persona->nombre ?? $notaCompra->proveedor->empresa->razon_social ?? 'N/A';
    }
    $proveedorTelefono = $notaCompra->proveedor->telefono ?? 'N/A';
    $proveedorCorreo = $notaCompra->proveedor->correo ?? 'N/A';

    $empleadoNombre = $notaCompra->empleado->nombre ?? 'N/A';
    
    $itemsHtml = '';
    $total = 0;
    foreach ($notaCompra->detalles as $detalle) {
        $nombreItem = $detalle->item->insumo->nombre ?? $detalle->item->nombre ?? 'Item';
        $almacenNombre = $detalle->almacen->nombre ?? 'N/A';
        $subtotal = $detalle->cantidad * $detalle->precio;
        $total += $subtotal;
        
        $itemsHtml .= "<tr>
            <td>{$detalle->cantidad}</td>
            <td>{$nombreItem}</td>
            <td>{$almacenNombre}</td>
            <td style='text-align:right'>Bs. " . number_format($detalle->precio, 2) . "</td>
            <td style='text-align:right'>Bs. " . number_format($subtotal, 2) . "</td>
        </tr>";
    }

    $fecha = date('d/m/Y H:i', strtotime($notaCompra->fecha_compra));

    return "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; }
            .header { background: #f8f9fa; padding: 20px; }
            .title { color: #8B4513; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .text-right { text-align: right; }
            .total { font-weight: bold; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h2 class='title'>PANADERÍA OTTO</h2>
            <h3>Comprobante de Compra #{$notaCompra->id_nota_compra}</h3>
            <p><strong>Fecha:</strong> {$fecha}</p>
        </div>
        
        <div style='padding: 20px;'>
            <p><strong>Proveedor:</strong> {$proveedorNombre}<br>
            <strong>Teléfono:</strong> {$proveedorTelefono}<br>
            <strong>Correo:</strong> {$proveedorCorreo}</p>
            
            <p><strong>Atendido por:</strong> {$empleadoNombre}</p>
            
            <h4>Detalle de Items</h4>
            <table>
                <thead>
                    <tr>
                        <th>Cant.</th>
                        <th>Descripción</th>
                        <th>Almacén</th>
                        <th>P. Unit.</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    {$itemsHtml}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan='4' class='text-right'><strong>Total:</strong></td>
                        <td class='text-right'><strong>Bs. " . number_format($total, 2) . "</strong></td>
                    </tr>
                </tfoot>
            </table>
            
            <p>Gracias por su preferencia.<br>
            Documento generado electrónicamente.</p>
        </div>
    </body>
    </html>
    ";
}
}