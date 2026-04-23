<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\CategoriaInsumo;
use App\Models\Item;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $insumos = Insumo::with(['categoria', 'item'])
            ->orderBy('nombre')
            ->paginate(15);
        
        $categorias = CategoriaInsumo::with('insumos')
            ->orderBy('nombre')
            ->paginate(10);
        
        // CORREGIDO: Eliminar orderBy ya que no existe columna 'nombre'
        // Si necesitas ordenar, puedes hacerlo por id_item o simplemente obtener todos
        $items = Item::where('tipo_item', 'insumo')->get();

        return view('insumo.index', compact('insumos', 'categorias', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = CategoriaInsumo::all();
        // CORREGIDO: Eliminar orderBy
        $items = Item::where('tipo_item', 'insumo')->get();
        
        return view('insumo.create', compact('categorias', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'id_cat_insumo' => 'required|exists:categoria_insumo,id_cat_insumo',
            'id_item' => 'nullable|exists:items,id_item',
            'precio_compra' => 'nullable|numeric|min:0',
        ]);

        Insumo::create($validated);

        return redirect()->route('insumos.index')
            ->with('success', 'Insumo creado exitosamente');
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
        // CORREGIDO: Eliminar orderBy
        $items = Item::where('tipo_item', 'insumo')->get();
        
        return view('insumo.edit', compact('insumo', 'categorias', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Insumo $insumo)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'id_cat_insumo' => 'required|exists:categoria_insumo,id_cat_insumo',
            'id_item' => 'nullable|exists:items,id_item',
            'precio_compra' => 'nullable|numeric|min:0',
        ]);

        $insumo->update($validated);

        return redirect()->route('insumos.index')
            ->with('success', 'Insumo actualizado exitosamente');
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

        $insumo->delete();

        return redirect()->route('insumos.index')
            ->with('success', 'Insumo eliminado exitosamente');
    }

    // ========== MÉTODOS PARA CATEGORÍAS DE INSUMOS ==========

    /**
     * Store a newly created category in storage.
     */
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

    /**
     * Update the specified category in storage.
     */
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

    /**
     * Remove the specified category from storage.
     */
    public function destroyCategoria($id)
    {
        $categoria = CategoriaInsumo::findOrFail($id);
        
        // Verificar si tiene insumos asociados
        if ($categoria->insumos()->count() > 0) {
            return redirect()->route('insumos.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene insumos asociados');
        }
        
        $categoria->delete();

        return redirect()->route('insumos.index')
            ->with('success', 'Categoría eliminada exitosamente');
    }

    /**
     * Show form for editing category.
     */
    public function editCategoria($id)
    {
        $categoria = CategoriaInsumo::findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json($categoria);
        }
        
        return view('insumo.categoria-edit', compact('categoria'));
    }
}