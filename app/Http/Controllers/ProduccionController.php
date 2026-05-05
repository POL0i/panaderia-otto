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
        'cantidad_producida' => 'required|numeric|min:0.1',
        'observaciones' => 'nullable|string',
        'notificar_empleado' => 'nullable|exists:empleados,id_empleado',
    ]);

    DB::beginTransaction();
    try {
        $receta = Receta::with('detalles.insumo.item', 'producto.item')->findOrFail($validated['id_receta']);
        
        if (!$receta->producto) {
            throw new \Exception('La receta seleccionada no tiene un producto asociado.');
        }

        $produccion = Produccion::create([
            'fecha_produccion' => now()->toDateString(),
            'cantidad_producida' => $validated['cantidad_producida'],
            'id_empleado_solicita' => Auth::user()->empleado->id_empleado,
            'estado' => 'pendiente',
            'fecha_solicitud' => now(),
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        $factor = $validated['cantidad_producida'];

        // Egresos de insumos (SOLO REGISTRO, sin tocar inventario)
        foreach ($receta->detalles as $detalleReceta) {
            $insumoItem = $detalleReceta->insumo->item;
            $cantidadNecesaria = $detalleReceta->cantidad_requerida * $factor;

            DetalleProduccion::create([
                'id_produccion' => $produccion->id_produccion,
                'id_detalle_receta' => $detalleReceta->id_detalle_receta,
                'id_almacen' => null,          // ← Temporal, se actualiza al aprobar
                'id_item' => $insumoItem->id_item,
                'cantidad' => $cantidadNecesaria,
                'tipo_movimiento' => 'egreso',
            ]);
        }

        // Ingreso del producto final (SOLO REGISTRO, sin tocar inventario)
        $productoItem = $receta->producto->item;

        DetalleProduccion::create([
            'id_produccion' => $produccion->id_produccion,
            'id_detalle_receta' => null,
            'id_almacen' => null,              // ← Temporal, se actualiza al aprobar
            'id_item' => $productoItem->id_item,
            'cantidad' => $validated['cantidad_producida'],
            'tipo_movimiento' => 'ingreso',
        ]);

        DB::commit();

        return redirect()->route('producciones.show', $produccion)
            ->with('success', 'Producción #' . $produccion->id_produccion . ' creada. Pendiente de autorización.');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('ERROR STORE: ' . $e->getMessage());
        return back()->with('error', $e->getMessage())->withInput();
    }
}

    public function show(Produccion $produccion)
{
    $produccion->load([
        'empleadoSolicita',
        'empleadoAutoriza',
        'detalles.item',           // ← Cargar item directamente
        'detalles.detalleReceta.receta.producto.item',  // ← Para la receta y producto
    ]);

    // ✅ Debug temporal - quitar después
    \Log::info('Show producción:', [
        'id' => $produccion->id_produccion,
        'solicitante' => $produccion->empleadoSolicita->nombre ?? 'sin nombre',
        'detalles_count' => $produccion->detalles->count(),
    ]);

    return view('produccion.producciones.show', compact('produccion'));
}

    public function aprobar(Request $request, Produccion $produccion)
    {
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

            // Validar stock de insumos
            foreach ($produccion->detalles()->where('tipo_movimiento', 'egreso')->get() as $detalle) {
                $almacenItem = AlmacenItem::where('id_almacen', $almacenOrigen->id_almacen)
                    ->where('id_item', $detalle->id_item)
                    ->first();

                if (!$almacenItem || $almacenItem->stock < $detalle->cantidad) {
                    $nombre = $detalle->item->nombre ?? 'Item #' . $detalle->id_item;
                    $stock = $almacenItem ? $almacenItem->stock : 0;
                    $errores[] = "Stock insuficiente de '{$nombre}'. Disponible: {$stock}, Necesario: {$detalle->cantidad}";
                }
            }

            // Validar capacidad destino
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

            // Ejecutar movimientos
            foreach ($produccion->detalles()->where('tipo_movimiento', 'egreso')->get() as $detalle) {
                $detalle->update(['id_almacen' => $almacenOrigen->id_almacen]);

                if (!DB::table('almacen_item')->where('id_almacen', $almacenOrigen->id_almacen)->where('id_item', $detalle->id_item)->exists()) {
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
            }

            if ($detalleIngreso) {
                $detalleIngreso->update(['id_almacen' => $almacenDestino->id_almacen]);

                if (!DB::table('almacen_item')->where('id_almacen', $almacenDestino->id_almacen)->where('id_item', $detalleIngreso->id_item)->exists()) {
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
            }

              \App\Models\LoteInventario::create([
                'id_almacen' => $detalleIngreso->id_almacen,
                'id_item' => $detalleIngreso->id_item,
                'cantidad_inicial' => $detalleIngreso->cantidad,
                'cantidad_disponible' => $detalleIngreso->cantidad,
                'precio_unitario' => 0, // Se calculará después o se deja en 0
                'fecha_entrada' => now(),
                'referencia_id' => $produccion->id_produccion,
                'referencia_tipo' => 'produccion',
                'estado' => 'disponible',
                'metodo_valuacion' => 'PEPS',
            ]);

            \App\Models\MovimientoInventario::registrar([
                'tipo_movimiento' => 'egreso',
                'id_almacen' => $almacenOrigen->id_almacen,
                'id_item' => $detalle->id_item,
                'cantidad' => -$detalle->cantidad,         // negativo
                'precio_unitario' => 0,                    // se podría calcular
                'costo_total' => 0,
                'referencia_id' => $produccion->id_produccion,
                'referencia_tipo' => 'produccion',
                'observaciones' => 'Consumo para producción #' . $produccion->id_produccion,
                'id_usuario' => Auth::id(),
            ]);

            \App\Models\MovimientoInventario::registrar([
                'tipo_movimiento' => 'ingreso',
                'id_almacen' => $almacenDestino->id_almacen,
                'id_item' => $detalleIngreso->id_item,
                'cantidad' => $detalleIngreso->cantidad,   // positivo
                'precio_unitario' => 0,                     // costo de producción
                'costo_total' => 0,
                'referencia_id' => $produccion->id_produccion,
                'referencia_tipo' => 'produccion',
                'observaciones' => 'Ingreso de producción #' . $produccion->id_produccion,
            ]);

            $produccion->update([
                'estado' => 'aprobado',
                'id_empleado_autoriza' => Auth::user()->empleado->id_empleado,
                'fecha_autorizacion' => now(),
            ]);

            DB::commit();
            return back()->with('success', 'Producción aprobada. Inventario actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
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

    public function calcularInsumos(Request $request)
    {
        $request->validate([
            'id_receta' => 'required|exists:recetas,id_receta',
            'cantidad' => 'required|numeric|min:0.1',
        ]);

        $receta = Receta::with('detalles.insumo.item')->findOrFail($request->id_receta);
        $factor = $request->cantidad;

        $insumos = $receta->detalles->map(function ($detalle) use ($factor) {
            return [
                'insumo' => $detalle->insumo->item->nombre ?? $detalle->insumo->nombre,  // ← Corregido
                'cantidad_teorica' => $detalle->cantidad_requerida,
                'cantidad_requerida' => $detalle->cantidad_requerida * $factor,
                'unidad' => $detalle->insumo->item->unidad_medida ?? 'unidad',
            ];
        });

        return response()->json(['insumos' => $insumos]);
    }
}