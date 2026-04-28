<?php
// app/Http/Controllers/ProduccionController.php

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

    public function indexProducciones()
    {
        return $this->index();
    }

    public function index()
    {
        $producciones = Produccion::with(['empleadoSolicita', 'empleadoAutoriza'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
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
            'almacen_destino' => 'required|exists:almacenes,id_almacen',
            'observaciones' => 'nullable|string',
            'notificar_empleado' => 'nullable|exists:empleados,id_empleado',
        ]);

        DB::beginTransaction();
        try {
            $receta = Receta::with('detalles.insumo.item', 'producto.item')->findOrFail($validated['id_receta']);
            
            if (!$receta->producto) {
                throw new \Exception('La receta seleccionada no tiene un producto asociado.');
            }

            // Crear producción
            $produccion = Produccion::create([
                'fecha_produccion' => now()->toDateString(),
                'cantidad_producida' => $validated['cantidad_producida'],
                'id_empleado_solicita' => Auth::user()->empleado->id_empleado,
                'estado' => 'pendiente',
                'fecha_solicitud' => now(),
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            $factor = $validated['cantidad_producida']; // Asumiendo que la receta base produce 1 unidad/lote

            // 1. Egresos de insumos (por cada detalle de receta)
            foreach ($receta->detalles as $detalleReceta) {
                $insumoItem = $detalleReceta->insumo->item;
                $cantidadNecesaria = $detalleReceta->cantidad_requerida * $factor;

                // Por ahora usamos un almacén fijo para insumos (puede ser un select adicional)
                $almacenInsumos = 1;

                DetalleProduccion::create([
                    'id_produccion' => $produccion->id_produccion,
                    'id_detalle_receta' => $detalleReceta->id_detalle_receta,
                    'id_almacen' => $almacenInsumos,
                    'id_item' => $insumoItem->id_item,
                    'cantidad' => $cantidadNecesaria,
                    'tipo_movimiento' => 'egreso',
                ]);
            }

            // 2. Ingreso del producto final
            $productoItem = $receta->producto->item;
            DetalleProduccion::create([
                'id_produccion' => $produccion->id_produccion,
                'id_detalle_receta' => null,
                'id_almacen' => $validated['almacen_destino'],
                'id_item' => $productoItem->id_item,
                'cantidad' => $validated['cantidad_producida'],
                'tipo_movimiento' => 'ingreso',
            ]);

            DB::commit();

            // Notificación (opcional)
            if (!empty($validated['notificar_empleado'])) {
                // Lógica de notificación
            }

            return redirect()->route('producciones.show', $produccion)
                ->with('success', 'Orden de producción creada.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Produccion $produccion)
    {
        $produccion->load(['empleadoSolicita', 'empleadoAutoriza', 'detalles.detalleReceta.insumo', 'detalles.item']);
        return view('produccion.producciones.show', compact('produccion'));
    }

    public function aprobar(Produccion $produccion)
    {
        if (!$produccion->esPendiente()) {
            return back()->with('error', 'Solo se pueden aprobar producciones pendientes.');
        }

        DB::beginTransaction();
        try {
            foreach ($produccion->detalles as $detalle) {
                $almacenItem = AlmacenItem::where('id_almacen', $detalle->id_almacen)
                    ->where('id_item', $detalle->id_item)
                    ->lockForUpdate()
                    ->first();

                if (!$almacenItem) {
                    // Si no existe el registro en almacen_item (por ej. para el producto final), lo creamos
                    DB::table('almacen_item')->insert([
                        'id_almacen' => $detalle->id_almacen,
                        'id_item' => $detalle->id_item,
                        'stock' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $almacenItem = AlmacenItem::where(...)->first();
                }

                if ($detalle->tipo_movimiento === 'egreso') {
                    if ($almacenItem->stock < $detalle->cantidad) {
                        throw new \Exception("Stock insuficiente de {$detalle->item->insumo->nombre}");
                    }
                    DB::table('almacen_item')
                        ->where('id_almacen', $detalle->id_almacen)
                        ->where('id_item', $detalle->id_item)
                        ->decrement('stock', $detalle->cantidad);
                } else {
                    DB::table('almacen_item')
                        ->where('id_almacen', $detalle->id_almacen)
                        ->where('id_item', $detalle->id_item)
                        ->increment('stock', $detalle->cantidad);
                }
            }

            $produccion->update([
                'estado' => 'aprobado',
                'id_empleado_autoriza' => Auth::user()->empleado->id_empleado,
                'fecha_autorizacion' => now(),
            ]);

            DB::commit();
            return back()->with('success', 'Producción aprobada e inventario actualizado.');
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

        try {
            $receta = Receta::with('detalles.insumo')->findOrFail($request->id_receta);
            $factor = $request->cantidad;

            $insumos = $receta->detalles->map(function ($detalle) use ($factor) {
                $cantidadRequerida = $detalle->cantidad_requerida * $factor;
                return [
                    'insumo' => $detalle->insumo->nombre,
                    'cantidad_teorica' => $detalle->cantidad_requerida,
                    'cantidad_requerida' => $cantidadRequerida,
                    'unidad' => $detalle->insumo->unidad_medida ?? 'unidad',
                ];
            });

            return response()->json([
                'success' => true,
                'insumos' => $insumos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al calcular insumos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Guardar producción (alias para store)
     */
    public function storeProduccion(Request $request)
    {
        return $this->store($request);
    }
}