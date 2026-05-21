<?php

namespace App\Http\Controllers;

use App\Models\Produccion;
use App\Models\DetalleProduccion;
use App\Models\Receta;
use App\Models\Almacen;
use App\Models\AlmacenItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProduccionController extends Controller
{
    public function index(Request $request)
    {
        $query = Produccion::with(['empleadoSolicita', 'empleadoAutoriza']);

        if ($request->has('estado') && in_array($request->estado, ['pendiente', 'aprobado', 'rechazado', 'cancelado'])) {
            $query->where('estado', $request->estado);
        }

        $producciones = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('produccion.producciones.index', compact('producciones'));
    }

    public function create()
    {
        $recetas = Receta::with('detalles.insumo')->get();
        $almacenes = Almacen::all();
        return view('produccion.producciones.create', compact('recetas', 'almacenes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_receta' => 'required|exists:recetas,id_receta',
            'cantidad_producida' => 'required|numeric|min:0.1', // total de producto
            'observaciones' => 'nullable|string',
            'notificar_empleado' => 'nullable|exists:empleados,id_empleado',
            'fecha_vencimiento_producto' => 'nullable|date|after_or_equal:today', 
        ]);

        DB::beginTransaction();
        try {
            $receta = Receta::with('detalles.insumo.item', 'producto.item')
                ->findOrFail($validated['id_receta']);

            if (!$receta->producto) {
                throw new \Exception('La receta seleccionada no tiene un producto asociado.');
            }

            // ✅ Factor corregido: total de producto ÷ rendimiento de la receta
            $factor = $validated['cantidad_producida'] / $receta->cantidad_requerida;

            $produccion = Produccion::create([
                'fecha_produccion' => now()->toDateString(),
                'cantidad_producida' => $validated['cantidad_producida'],
                'id_empleado_solicita' => Auth::user()->empleado->id_empleado,
                'estado' => 'pendiente',
                'fecha_solicitud' => now(),
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            if (!empty($validated['fecha_vencimiento_producto'])) {
                // La guardamos temporalmente en la observación del detalle de ingreso
                // o usamos el campo 'fecha_vencimiento' si existe en detalle_produccion
                // Mejor: la guardamos en un atributo temporal de la producción
                session(['fecha_vencimiento_prod_' . $produccion->id_produccion => $validated['fecha_vencimiento_producto']]);
            }

            if (!empty($validated['fecha_vencimiento_producto'])) {
    session(['fecha_vencimiento_prod_' . $produccion->id_produccion => $validated['fecha_vencimiento_producto']]);
    Log::info('📅 Fecha vencimiento guardada en sesión', [
        'produccion_id' => $produccion->id_produccion,
        'fecha' => $validated['fecha_vencimiento_producto']
    ]);
}

            // Egresos de insumos (solo registro)
            foreach ($receta->detalles as $detalleReceta) {
                $insumoItem = $detalleReceta->insumo->item;
                $cantidadNecesaria = $detalleReceta->cantidad_requerida * $factor;

                DetalleProduccion::create([
                    'id_produccion' => $produccion->id_produccion,
                    'id_detalle_receta' => $detalleReceta->id_detalle_receta,
                    'id_almacen' => null,
                    'id_item' => $insumoItem->id_item,
                    'cantidad' => $cantidadNecesaria,
                    'tipo_movimiento' => 'egreso',
                ]);
            }

            // Ingreso del producto final (solo registro)
            $productoItem = $receta->producto->item;
            DetalleProduccion::create([
                'id_produccion' => $produccion->id_produccion,
                'id_detalle_receta' => null,
                'id_almacen' => null,
                'id_item' => $productoItem->id_item,
                'cantidad' => $validated['cantidad_producida'],
                'tipo_movimiento' => 'ingreso',
            ]);

            DB::commit();

            return redirect()->route('producciones.show', $produccion)
                ->with('success', 'Producción #' . $produccion->id_produccion . ' creada. Pendiente de autorización.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ERROR STORE: ' . $e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Produccion $produccion)
    {
        $produccion->load([
            'empleadoSolicita',
            'empleadoAutoriza',
            'detalles.item',
            'detalles.detalleReceta.receta.producto.item',
        ]);

        Log::info('Show producción:', [
            'id' => $produccion->id_produccion,
            'solicitante' => $produccion->empleadoSolicita->nombre ?? 'sin nombre',
            'detalles_count' => $produccion->detalles->count(),
        ]);

        return view('produccion.producciones.show', compact('produccion'));
    }

    public function aprobar(Request $request, Produccion $produccion)
    {
        $config = \App\Models\ConfiguracionInventario::obtener();
        $metodo = $config->metodo_valuacion_predeterminado ?? 'PEPS';

        if (!$produccion->esPendiente()) {
            return back()->with('error', 'Solo se pueden aprobar producciones pendientes.');
        }

        $request->validate([
            'almacen_origen' => 'required|exists:almacenes,id_almacen',
            'almacen_destino' => 'required|exists:almacenes,id_almacen',
        ]);

        DB::beginTransaction();
        try {
            $almacenOrigen = Almacen::findOrFail($request->almacen_origen);
            $almacenDestino = Almacen::findOrFail($request->almacen_destino);

            if (!$almacenOrigen->permiteInsumos()) {
                throw new \Exception("El almacén '{$almacenOrigen->nombre}' no acepta insumos.");
            }
            if (!$almacenDestino->permiteProductos()) {
                throw new \Exception("El almacén '{$almacenDestino->nombre}' no acepta productos.");
            }

            $errores = [];

            // Validar stock de insumos (EGRESOS)
            $detallesEgreso = $produccion->detalles()->where('tipo_movimiento', 'egreso')->get();
            foreach ($detallesEgreso as $detalle) {
                $almacenItem = AlmacenItem::where('id_almacen', $almacenOrigen->id_almacen)
                    ->where('id_item', $detalle->id_item)
                    ->first();

                if (!$almacenItem || $almacenItem->stock < $detalle->cantidad) {
                    $nombre = $detalle->item->nombre ?? 'Item #' . $detalle->id_item;
                    $stock = $almacenItem ? $almacenItem->stock : 0;
                    $errores[] = "Stock insuficiente de '{$nombre}'. Disponible: {$stock}, Necesario: {$detalle->cantidad}";
                }
            }

            // Validar capacidad destino (INGRESO)
            $detalleIngreso = $produccion->detalles()->where('tipo_movimiento', 'ingreso')->first();
            if ($detalleIngreso && $almacenDestino->capacidad > 0) {
                $stockActual = DB::table('almacen_item')
                    ->where('id_almacen', $almacenDestino->id_almacen)
                    ->sum('stock');

                $stockFuturo = $stockActual + $detalleIngreso->cantidad;
                if ($stockFuturo > $almacenDestino->capacidad) {
                    $errores[] = "Capacidad excedida en '{$almacenDestino->nombre}'. Actual: {$stockActual}, Máx: {$almacenDestino->capacidad}";
                }
            }

            if (!empty($errores)) {
                throw new \Exception(implode("\n", $errores));
            }

            // Procesar egresos
            foreach ($detallesEgreso as $detalle) {
                $detalle->update(['id_almacen' => $almacenOrigen->id_almacen]);

                if (!DB::table('almacen_item')
                    ->where('id_almacen', $almacenOrigen->id_almacen)
                    ->where('id_item', $detalle->id_item)
                    ->exists()) {
                    DB::table('almacen_item')->insert([
                        'id_almacen' => $almacenOrigen->id_almacen,
                        'id_item' => $detalle->id_item,
                        'stock' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('almacen_item')
                    ->where('id_almacen', $almacenOrigen->id_almacen)
                    ->where('id_item', $detalle->id_item)
                    ->decrement('stock', $detalle->cantidad);

                        \Log::info('🔍 ANTES de consumir', [
                            'produccion_id' => $produccion->id_produccion,
                            'almacen' => $almacenOrigen->id_almacen,
                            'item' => $detalle->id_item,
                            'cantidad' => $detalle->cantidad,
                            'metodo' => $metodo
                        ]);

                if (class_exists(\App\Models\LoteInventario::class)) {
                    try {
                        $resultado = \App\Models\LoteInventario::consumir(
                            $almacenOrigen->id_almacen,
                            $detalle->id_item,
                            $detalle->cantidad,
                            $metodo,
                            $produccion->id_produccion,
                            'produccion'
                        );
                        
                        \Log::info('✅ DESPUÉS de consumir', [
                            'cantidad_consumida' => $resultado['cantidad_consumida'],
                            'lotes_usados' => $resultado['lotes_usados'],
                            'costo_total' => $resultado['costo_total']
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('❌ ERROR al consumir lote', [
                            'message' => $e->getMessage(),
                            'item' => $detalle->id_item
                        ]);
                    }
                } else {
                    \Log::warning('⚠️ LoteInventario NO EXISTE');
                }

                if (class_exists(\App\Models\MovimientoInventario::class)) {
                    try {
                        \App\Models\MovimientoInventario::registrar([
                            'tipo_movimiento' => 'egreso',
                            'id_almacen' => $almacenOrigen->id_almacen,
                            'id_item' => $detalle->id_item,
                            'cantidad' => -$detalle->cantidad,
                            'precio_unitario' => 0,
                            'costo_total' => 0,
                            'referencia_id' => $produccion->id_produccion,
                            'referencia_tipo' => 'produccion',
                            'observaciones' => 'Consumo para producción #' . $produccion->id_produccion,
                            'id_usuario' => Auth::id(),
                        ]);
                    } catch (\Exception $e) {
                        Log::warning('Error al registrar movimiento egreso: ' . $e->getMessage());
                    }
                }
            }

            // Procesar ingreso
            if ($detalleIngreso) {
                $detalleIngreso->update(['id_almacen' => $almacenDestino->id_almacen]);

                if (!DB::table('almacen_item')
                    ->where('id_almacen', $almacenDestino->id_almacen)
                    ->where('id_item', $detalleIngreso->id_item)
                    ->exists()) {
                    DB::table('almacen_item')->insert([
                        'id_almacen' => $almacenDestino->id_almacen,
                        'id_item' => $detalleIngreso->id_item,
                        'stock' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('almacen_item')
                    ->where('id_almacen', $almacenDestino->id_almacen)
                    ->where('id_item', $detalleIngreso->id_item)
                    ->increment('stock', $detalleIngreso->cantidad);

                // Obtener fecha de vencimiento de la sesión
                $fechaVencimiento = session('fecha_vencimiento_prod_' . $produccion->id_produccion);

                Log::info('🔍 Valor de $fechaVencimiento desde sesión', ['fecha' => $fechaVencimiento]);
                
                // Crear UN SOLO lote
                if (class_exists(\App\Models\LoteInventario::class)) {
                    try {
                        \App\Models\LoteInventario::create([
                            'id_almacen'          => $almacenDestino->id_almacen,
                            'id_item'             => $detalleIngreso->id_item,
                            'cantidad_inicial'    => $detalleIngreso->cantidad,
                            'cantidad_disponible' => $detalleIngreso->cantidad,
                            'precio_unitario'     => 0,
                            'fecha_entrada'       => now(),
                            'fecha_vencimiento'   => $fechaVencimiento,           // ← de la sesión
                            'referencia_id'       => $produccion->id_produccion,
                            'referencia_tipo'     => 'produccion',
                            'estado'              => 'disponible',
                            'metodo_valuacion'    => $metodo,                     // ← de la configuración
                        ]);
                    } catch (\Exception $e) {
                        Log::warning('Error al crear lote: ' . $e->getMessage());
                    }
                }

                // Limpiar la sesión
                session()->forget('fecha_vencimiento_prod_' . $produccion->id_produccion);

                // Movimiento de inventario
                if (class_exists(\App\Models\MovimientoInventario::class)) {
                    try {
                        \App\Models\MovimientoInventario::registrar([
                            'tipo_movimiento' => 'ingreso',
                            'id_almacen' => $almacenDestino->id_almacen,
                            'id_item' => $detalleIngreso->id_item,
                            'cantidad' => $detalleIngreso->cantidad,
                            'precio_unitario' => 0,
                            'costo_total' => 0,
                            'referencia_id' => $produccion->id_produccion,
                            'referencia_tipo' => 'produccion',
                            'observaciones' => 'Ingreso de producción #' . $produccion->id_produccion,
                            'id_usuario' => Auth::id(),
                        ]);
                    } catch (\Exception $e) {
                        Log::warning('Error al registrar movimiento ingreso: ' . $e->getMessage());
                    }
                }
            }

            $produccion->update([
                'estado' => 'aprobado',
                'id_empleado_autoriza' => Auth::user()->empleado->id_empleado,
                'fecha_autorizacion' => now(),
            ]);

            DB::commit();
            return back()->with('success', 'Producción aprobada. Inventario actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al aprobar producción: ' . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    public function rechazar(Request $request, Produccion $produccion)
    {
        if (!$produccion->esPendiente()) {
            return back()->with('error', 'Solo se pueden rechazar producciones pendientes.');
        }

        $request->validate(['motivo' => 'required|string']);

        $produccion->update([
            'estado' => 'rechazado',
            'id_empleado_autoriza' => Auth::user()->empleado->id_empleado,
            'fecha_autorizacion' => now(),
            'observaciones' => $produccion->observaciones . "\nRechazo: " . $request->motivo,
        ]);

        return redirect()->route('producciones.show', $produccion)
            ->with('success', 'Producción rechazada.');
    }

    public function cancelar(Produccion $produccion)
    {
        if (!$produccion->esPendiente()) {
            return back()->with('error', 'Solo se pueden cancelar producciones pendientes.');
        }

        $produccion->update(['estado' => 'cancelado']);
        return back()->with('success', 'Producción cancelada.');
    }

    /**
     * Calcula los insumos necesarios para una cantidad total de producto.
     */
    public function calcularInsumos(Request $request)
{
    try {
        $request->validate([
            'id_receta' => 'required|exists:recetas,id_receta',
            'cantidad' => 'required|numeric|min:0.1',
        ]);

        $receta = Receta::with(['detalles.insumo.item'])->findOrFail($request->id_receta);
        
        // Verificar que la receta tenga detalles
        if (!$receta->detalles || $receta->detalles->isEmpty()) {
            return response()->json([
                'error' => 'La receta no tiene insumos asociados',
                'insumos' => []
            ], 422);
        }
        
        // 🔴 CONVERTIR a número (solución para el error)
        $cantidadRequerida = floatval($receta->cantidad_requerida);
        $cantidadSolicitada = floatval($request->cantidad);
        
        // Verificar que la receta tenga cantidad_requerida válida
        if (!$cantidadRequerida || $cantidadRequerida <= 0) {
            return response()->json([
                'error' => 'La receta no tiene una cantidad requerida válida. Valor actual: ' . $receta->cantidad_requerida,
                'insumos' => []
            ], 422);
        }
        
        // Calcular factor
        $factor = $cantidadSolicitada / $cantidadRequerida;
        
        $insumos = [];
        
        foreach ($receta->detalles as $detalle) {
            // Verificar que el detalle tenga insumo
            if (!$detalle->insumo) {
                \Log::warning('Detalle de receta sin insumo', ['detalle_id' => $detalle->id_detalle_receta]);
                continue;
            }
            
            // Verificar que el insumo tenga item
            if (!$detalle->insumo->item) {
                \Log::warning('Insumo sin item asociado', ['insumo_id' => $detalle->id_insumo]);
                continue;
            }
            
            // 🔴 Convertir cantidad_requerida del detalle a número
            $cantidadTeorica = floatval($detalle->cantidad_requerida);
            
            $insumos[] = [
                'id_insumo' => $detalle->id_insumo,
                'insumo' => $detalle->insumo->item->nombre ?? 'Insumo desconocido',
                'cantidad_teorica' => $cantidadTeorica,
                'cantidad_requerida' => $cantidadTeorica * $factor,
                'unidad' => $detalle->insumo->item->unidad_medida ?? 'unidad',
            ];
        }
        
        if (empty($insumos)) {
            return response()->json([
                'error' => 'No se pudieron calcular los insumos. Verifique que la receta tenga insumos válidos.',
                'insumos' => []
            ], 422);
        }
        
        return response()->json([
            'success' => true,
            'insumos' => $insumos,
            'factor' => $factor,
            'cantidad_producto' => $cantidadSolicitada,
            'rendimiento_receta' => $cantidadRequerida
        ]);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'Error de validación: ' . implode(', ', $e->errors())
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Error en calcularInsumos: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'error' => 'Error al calcular insumos: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Retorna el stock disponible de cada insumo requerido en un almacén específico.
     */
    public function insumosStock(Almacen $almacen, Produccion $produccion)
    {
        \Log::info('🔍 insumosStock llamado', [
            'almacen_id' => $almacen->id_almacen,
            'produccion_id' => $produccion->id_produccion
        ]);

        $detallesEgreso = $produccion->detalles()
                            ->where('tipo_movimiento', 'egreso')
                            ->with('item')
                            ->get();

        if ($detallesEgreso->isEmpty()) {
            \Log::warning('No hay detalles de egreso para producción #' . $produccion->id_produccion);
            return response()->json(['insumos' => []]);
        }

        $resultado = [];

        foreach ($detallesEgreso as $detalle) {
            // Verifica el nombre real del campo stock
            $stock = AlmacenItem::where('id_almacen', $almacen->id_almacen)
                        ->where('id_item', $detalle->id_item)
                        ->value('stock') ?? 0; // ← Cambia 'stock' por 'cantidad_disponible' si es necesario

            $itemNombre = $detalle->item->nombre ?? 'Item #' . $detalle->id_item;

            \Log::info("📦 $itemNombre: stock=$stock, requerido=$detalle->cantidad");

            $resultado[] = [
                'nombre'      => $itemNombre,
                'requerido'   => $detalle->cantidad,
                'stock'       => $stock,
                'suficiente'  => $stock >= $detalle->cantidad
            ];
        }

        return response()->json(['insumos' => $resultado]);
    }

    /**
     * Retorna el espacio disponible en un almacén para el producto final.
     */
    public function capacidadDisponible(Almacen $almacen, Produccion $produccion)
    {
        $detalleIngreso = $produccion->detalles()
                            ->where('tipo_movimiento', 'ingreso')
                            ->with('item')
                            ->first();

        if (!$detalleIngreso) {
            return response()->json(['error' => 'No hay detalle de producto'], 422);
        }

        // Stock total actual de TODOS los ítems en ese almacén
        $stockTotal = DB::table('almacen_item')
                        ->where('id_almacen', $almacen->id_almacen)
                        ->sum('stock');

        $capacidad = $almacen->capacidad;
        $disponible = $capacidad > 0 ? $capacidad - $stockTotal : null;

        return response()->json([
            'almacen'        => $almacen->nombre,
            'capacidad'      => $capacidad,
            'stock_actual'   => $stockTotal,
            'disponible'     => $disponible,
            'cantidad_prod'  => $detalleIngreso->cantidad,
            'suficiente'     => $capacidad == 0 || $disponible >= $detalleIngreso->cantidad,
            'producto'       => $detalleIngreso->item->nombre ?? 'Producto final'
        ]);
    }
}