<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\Insumo;
use App\Models\CategoriaInsumo;
use App\Models\DetalleReceta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecetaController extends Controller
{
    public function index()
    {
        $recetas = Receta::with(['detalles.insumo.categoria'])
            ->withCount('detalles')
            ->orderBy('nombre')
            ->paginate(15);

        return view('produccion.recetas.index', compact('recetas'));
    }

    public function create()
    {
        $categorias = CategoriaInsumo::orderBy('nombre')->get();
        $insumos = Insumo::with('categoria')->orderBy('nombre')->get();
        
        return view('produccion.recetas.create', compact('categorias', 'insumos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:recetas,nombre',
            'descripcion' => 'nullable|string',
            'insumos' => 'nullable|array',
            'insumos.*.id_insumo' => 'required|exists:insumos,id_insumo',
            'insumos.*.cantidad' => 'required|numeric|min:0.001',
            'insumos.*.unidad' => 'required|string|in:kg,g,lb,oz,L,mL,unidad',
        ]);

        DB::beginTransaction();
        try {
            // Crear receta
            $receta = Receta::create([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'] ?? null,
                'cantidad_requerida' => 1, // Valor temporal, se actualizará después
            ]);

            $totalInsumos = 0;
            
            // Crear detalles de receta
            if (!empty($validated['insumos'])) {
                foreach ($validated['insumos'] as $insumoData) {
                    DetalleReceta::create([
                        'id_receta' => $receta->id_receta,
                        'id_insumo' => $insumoData['id_insumo'],
                        'cantidad_requerida' => $insumoData['cantidad'],
                        'unidad_medida' => $insumoData['unidad'],
                    ]);
                    $totalInsumos++;
                }
            }

            // Actualizar cantidad de insumos en la receta
            $receta->update(['cantidad_requerida' => $totalInsumos]);

            DB::commit();
            return redirect()->route('recetas.show', $receta)
                ->with('success', 'Receta creada correctamente con ' . $totalInsumos . ' insumos');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear receta: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Receta $receta)
    {
        $receta->load(['detalles.insumo.categoria']);
        $categorias = CategoriaInsumo::orderBy('nombre')->get();
        $insumos = Insumo::with('categoria')->orderBy('nombre')->get();
        
        return view('produccion.recetas.show', compact('receta', 'categorias', 'insumos'));
    }

    public function edit(Receta $receta)
    {
        $receta->load(['detalles.insumo.categoria']);
        $categorias = CategoriaInsumo::orderBy('nombre')->get();
        $insumos = Insumo::with('categoria')->orderBy('nombre')->get();
        
        return view('produccion.recetas.edit', compact('receta', 'categorias', 'insumos'));
    }

    public function update(Request $request, Receta $receta)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:recetas,nombre,' . $receta->id_receta . ',id_receta',
            'descripcion' => 'nullable|string',
        ]);

        $receta->update($validated);

        return redirect()->route('recetas.show', $receta)
            ->with('success', 'Receta actualizada correctamente');
    }

    public function destroy(Receta $receta)
    {
        if ($receta->producciones()->count() > 0) {
            return back()->with('error', 'No se puede eliminar una receta con producciones asociadas');
        }

        DB::beginTransaction();
        try {
            $receta->detalles()->delete();
            $receta->delete();
            DB::commit();
            
            return redirect()->route('recetas.index')
                ->with('success', 'Receta eliminada correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar receta: ' . $e->getMessage());
        }
    }

    // ============================================
    // MÉTODOS AJAX PARA GESTIÓN DE DETALLES
    // ============================================

    public function storeDetalle(Request $request, Receta $receta)
    {
        $validated = $request->validate([
            'id_insumo' => 'required|exists:insumos,id_insumo',
            'cantidad' => 'required|numeric|min:0.001',
            'unidad' => 'required|string|in:kg,g,lb,oz,L,mL,unidad',
        ]);

        $detalle = DetalleReceta::create([
            'id_receta' => $receta->id_receta,
            'id_insumo' => $validated['id_insumo'],
            'cantidad_requerida' => $validated['cantidad'],
            'unidad_medida' => $validated['unidad'],
        ]);

        // Actualizar contador en receta
        $receta->update(['cantidad_requerida' => $receta->detalles()->count()]);

        return response()->json([
            'success' => true,
            'detalle' => $detalle->load('insumo.categoria'),
            'message' => 'Insumo agregado a la receta'
        ]);
    }

    public function updateDetalle(Request $request, DetalleReceta $detalle)
    {
        $validated = $request->validate([
            'cantidad' => 'required|numeric|min:0.001',
            'unidad' => 'required|string|in:kg,g,lb,oz,L,mL,unidad',
        ]);

        $detalle->update([
            'cantidad_requerida' => $validated['cantidad'],
            'unidad_medida' => $validated['unidad'],
        ]);

        return response()->json([
            'success' => true,
            'detalle' => $detalle->load('insumo.categoria'),
            'message' => 'Detalle actualizado correctamente'
        ]);
    }

    public function destroyDetalle(DetalleReceta $detalle)
    {
        $receta = $detalle->receta;
        $detalle->delete();
        
        // Actualizar contador
        $receta->update(['cantidad_requerida' => $receta->detalles()->count()]);

        return response()->json([
            'success' => true,
            'message' => 'Insumo removido de la receta'
        ]);
    }
}