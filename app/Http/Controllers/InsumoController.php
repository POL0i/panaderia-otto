<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\CategoriaInsumo;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $insumos = Insumo::with(['categoria', 'item'])
            ->join('items', 'insumos.id_item', '=', 'items.id_item')
            ->orderBy('items.nombre', 'asc')
            ->select('insumos.*')
            ->paginate(15);

        $categorias = CategoriaInsumo::with('insumos')
            ->orderBy('nombre')
            ->paginate(10);

        return view('insumo.index', compact('insumos', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = CategoriaInsumo::all();

        return view('insumo.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'id_cat_insumo' => 'required|exists:categoria_insumo,id_cat_insumo',
            'unidad_medida' => 'required|string|in:kg,g,lb,oz,L,mL,unidad,docena',
            'precio_compra' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Crear el Item padre con NOMBRE
            $item = Item::create([
                'nombre' => $request->nombre,
                'tipo_item' => 'insumo',
                'unidad_medida' => $request->unidad_medida,
            ]);

            // 2. Crear el Insumo SIN nombre
            $insumo = Insumo::create([
                'id_item' => $item->id_item,
                'id_cat_insumo' => $request->id_cat_insumo,
                'precio_compra' => $request->precio_compra ?? null,
            ]);

            DB::commit();

            return redirect()->route('insumos.index')
                ->with('success', 'Insumo creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear insumo: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Insumo $insumo)
    {
        $insumo->load(['categoria', 'item', 'detallesReceta', 'detallesCompra']);

        return view('insumo.show', compact('insumo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Insumo $insumo)
    {
        $categorias = CategoriaInsumo::all();

        return view('insumo.edit', compact('insumo', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Insumo $insumo)
    {
        $validated = $request->validate([
            'id_cat_insumo' => 'required|exists:categoria_insumo,id_cat_insumo',
            'nombre' => 'required|string|max:255',
            'unidad_medida' => 'required|string|in:kg,g,lb,oz,L,mL,unidad,docena',
            'precio_compra' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Actualizar el Item relacionado
            $insumo->item->update([
                'nombre' => $request->nombre,
                'unidad_medida' => $request->unidad_medida,
            ]);

            // Actualizar el Insumo
            $insumo->update([
                'id_cat_insumo' => $request->id_cat_insumo,
                'precio_compra' => $request->precio_compra,
            ]);

            DB::commit();

            return redirect()->route('insumos.index')
                ->with('success', 'Insumo actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar insumo: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insumo $insumo)
    {
        if ($insumo->detallesReceta()->count() > 0 || $insumo->detallesCompra()->count() > 0) {
            return redirect()->route('insumos.index')
                ->with('error', 'No se puede eliminar un insumo con recetas o compras asociadas');
        }

        $item = $insumo->item;
        $insumo->delete();
        $item->delete();

        return redirect()->route('insumos.index')
            ->with('success', 'Insumo eliminado exitosamente');
    }

    // ========== MÉTODOS PARA CATEGORÍAS DE INSUMOS ==========
    // Estos se mantienen igual

    public function storeCategoria(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:categoria_insumo,nombre',
            'descripcion' => 'nullable|string|max:500',
        ]);

        CategoriaInsumo::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Categoría creada exitosamente']);
        }

        return redirect()->route('insumos.index')
            ->with('success', 'Categoría creada exitosamente');
    }

    public function updateCategoria(Request $request, $id)
    {
        $categoria = CategoriaInsumo::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:categoria_insumo,nombre,' . $id . ',id_cat_insumo',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $categoria->update($validated);

        return redirect()->route('insumos.index')
            ->with('success', 'Categoría actualizada exitosamente');
    }

    public function destroyCategoria($id)
    {
        $categoria = CategoriaInsumo::findOrFail($id);

        if ($categoria->insumos()->count() > 0) {
            return redirect()->route('insumos.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene insumos asociados');
        }

        $categoria->delete();

        return redirect()->route('insumos.index')
            ->with('success', 'Categoría eliminada exitosamente');
    }

    public function editCategoria($id)
    {
        $categoria = CategoriaInsumo::findOrFail($id);

        if (request()->ajax()) {
            return response()->json($categoria);
        }

        return view('insumo.categoria-edit', compact('categoria'));
    }
}
