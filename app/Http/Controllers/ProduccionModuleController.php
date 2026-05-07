<?php
// app/Http/Controllers/ProduccionModuleController.php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\Produccion;
use App\Models\CategoriaInsumo;
use App\Models\Insumo;
use App\Models\DetalleReceta;
use App\Models\DetalleProduccion;
use App\Models\Item;
use App\Models\Almacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProduccionModuleController extends Controller
{
    public function index()
    {
        $totalRecetas = Receta::count();
        $totalProducciones = Produccion::count();
        $totalCategorias = CategoriaInsumo::count();
        $totalInsumos = Insumo::count();

        $categorias = CategoriaInsumo::orderBy('nombre')->get();

        $ultimasRecetas = Receta::withCount('detalles')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('produccion.index', compact(
            'totalRecetas',
            'totalProducciones',
            'totalCategorias',
            'totalInsumos',
            'categorias',
            'ultimasRecetas'
        ));
    }

    public function storeCategoria(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:categoria_insumo,nombre',
            'descripcion' => 'nullable|string',
        ]);

        $categoria = CategoriaInsumo::create($validated);

        return response()->json([
            'success' => true,
            'categoria' => $categoria,
            'message' => 'Categoría creada exitosamente'
        ]);
    }

    public function storeInsumo(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'id_cat_insumo' => 'required|exists:categoria_insumo,id_cat_insumo',
            'unidad_medida' => 'required|string|in:kg,g,lb,oz,L,mL,unidad',
            'precio_compra' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Crear el Item primero
            $item = Item::create([
                'tipo_item' => 'insumo',
                'unidad_medida' => $validated['unidad_medida'],
            ]);

            // 2. Crear el Insumo con el id_item
            $insumo = Insumo::create([
                'id_item' => $item->id_item,
                'id_cat_insumo' => $validated['id_cat_insumo'],
                'nombre' => $validated['nombre'],
                'precio_compra' => $validated['precio_compra'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'insumo' => $insumo->load('categoria', 'item'),
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

    public function storeReceta(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:recetas,nombre',
            'descripcion' => 'nullable|string',
            'id_producto' => 'required|exists:productos,id_producto',
        ]);

        $receta = Receta::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'cantidad_requerida' => 0,
            'id_producto' => $validated['id_producto'],
        ]);

        return response()->json([
            'success' => true,
            'receta' => $receta,
            'message' => 'Receta creada correctamente. Ahora puedes agregar insumos.',
            'redirect' => route('recetas.show', $receta)
        ]);
    }

    public function detallesReceta(Receta $receta)
    {
        $receta->load(['detalles.insumo.categoria']);

        $categorias = CategoriaInsumo::with(['insumos' => function($query) {
            $query->orderBy('nombre');
        }])->orderBy('nombre')->get();

        $insumosEnReceta = $receta->detalles->pluck('id_insumo')->toArray();

        return view('produccion.recetas.detalles', compact('receta', 'categorias', 'insumosEnReceta'));
    }

    public function storeDetallesReceta(Request $request, Receta $receta)
    {
        $validated = $request->validate([
            'insumos' => 'required|array|min:1',
            'insumos.*.id_insumo' => 'required|exists:insumos,id_insumo',
            'insumos.*.cantidad' => 'required|numeric|min:0.001',
        ]);

        DB::beginTransaction();
        try {
            $insumosAgregados = 0;

            foreach ($validated['insumos'] as $insumoData) {
                $existente = DetalleReceta::where([
                    'id_receta' => $receta->id_receta,
                    'id_insumo' => $insumoData['id_insumo']
                ])->first();

                if (!$existente) {
                    DetalleReceta::create([
                        'id_receta' => $receta->id_receta,
                        'id_insumo' => $insumoData['id_insumo'],
                        'cantidad_requerida' => $insumoData['cantidad'],
                    ]);
                    $insumosAgregados++;
                }
            }

            $totalInsumos = $receta->detalles()->count();
            $receta->update(['cantidad_requerida' => $totalInsumos]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Se agregaron {$insumosAgregados} insumos a la receta",
                'total_insumos' => $totalInsumos
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar insumos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateDetalleReceta(Request $request, DetalleReceta $detalle)
    {
        $validated = $request->validate([
            'cantidad' => 'required|numeric|min:0.001',
        ]);

        $detalle->update([
            'cantidad_requerida' => $validated['cantidad'],
        ]);

        return response()->json([
            'success' => true,
            'detalle' => $detalle->load('insumo.categoria'),
            'message' => 'Detalle actualizado correctamente'
        ]);
    }

    public function destroyDetalleReceta(DetalleReceta $detalle)
    {
        $receta = $detalle->receta;
        $detalle->delete();

        $totalInsumos = $receta->detalles()->count();
        $receta->update(['cantidad_requerida' => $totalInsumos]);

        return response()->json([
            'success' => true,
            'message' => 'Insumo removido de la receta',
            'total_insumos' => $totalInsumos
        ]);
    }

    public function indexProducciones()
    {
        $producciones = Produccion::with(['empleadoSolicita', 'empleadoAutoriza'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('produccion.producciones.index', compact('producciones'));
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
                'id_detalle_receta' => $detalle->id_detalle_receta,
                'insumo' => $detalle->insumo->nombre,
                'cantidad_teorica' => $detalle->cantidad_requerida,
                'cantidad_requerida' => $detalle->cantidad_requerida * $factor,
                'unidad' => $detalle->insumo->item->unidad_medida ?? 'unidad',
            ];
        });

        return response()->json(['insumos' => $insumos]);
    }

    public function storeProduccion(Request $request)
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
            // Obtener receta con sus relaciones
            $receta = Receta::with(['detalles.insumo.item', 'producto.item'])->findOrFail($validated['id_receta']);

            // Verificar que la receta tenga producto
            if (!$receta->producto) {
                throw new \Exception('La receta seleccionada no tiene un producto asociado.');
            }

            $factor = $validated['cantidad_producida'];

            // Obtener empleado del usuario autenticado
            $usuario = Auth::user();
            if (!$usuario || !$usuario->empleado) {
                throw new \Exception('Usuario no tiene un empleado asociado.');
            }

            // Crear producción
            $produccion = Produccion::create([
                'fecha_produccion' => now()->toDateString(),
                'cantidad_producida' => $validated['cantidad_producida'],
                'id_empleado_solicita' => $usuario->empleado->id_empleado,
                'estado' => 'pendiente',
                'fecha_solicitud' => now(),
                'observaciones' => $validated['observaciones'] ?? null,
            ]);

            // ✅ Determinar almacén de origen (primer almacén o seleccionable)
            $almacenOrigen = Almacen::first();
            if (!$almacenOrigen) {
                throw new \Exception('No hay almacenes registrados en el sistema.');
            }

            // Crear detalles de egreso (insumos)
            foreach ($receta->detalles as $detalleReceta) {
                $insumoItem = $detalleReceta->insumo->item;
                $cantidadNecesaria = $detalleReceta->cantidad_requerida * $factor;

                DetalleProduccion::create([
                    'id_produccion' => $produccion->id_produccion,
                    'id_detalle_receta' => $detalleReceta->id_detalle_receta,
                    'id_almacen' => $almacenOrigen->id_almacen,
                    'id_item' => $insumoItem->id_item,
                    'cantidad' => $cantidadNecesaria,
                    'tipo_movimiento' => 'egreso',
                ]);
            }

            // Crear detalle de ingreso del producto terminado
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

            return redirect()->route('producciones.show', $produccion)
                ->with('success', 'Producción #' . $produccion->id_produccion . ' creada. Pendiente de autorización.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear producción: ' . $e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
