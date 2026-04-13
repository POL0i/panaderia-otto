<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\Item;
use App\Models\CategoriaInsumo;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $insumos = Insumo::with(['item', 'categoria'])
            ->orderBy('nombre')
            ->paginate(15);
        
        return view('insumo.index', compact('insumos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::where('tipo_item', 'insumo')->get();
        $categorias = CategoriaInsumo::all();
        
        return view('insumo.create', compact('items', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_item' => 'required|exists:items,id_item',
            'id_cat_insumo' => 'required|exists:categorias_insumo,id_cat_insumo',
            'nombre' => 'required|string|max:255',
            'precio_compra' => 'required|numeric|min:0',
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
        $insumo->load(['item', 'categoria', 'detallesReceta', 'detallesCompra']);
        
        return view('insumo.show', compact('insumo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Insumo $insumo)
    {
        $items = Item::where('tipo_item', 'insumo')->get();
        $categorias = CategoriaInsumo::all();
        
        return view('insumo.edit', compact('insumo', 'items', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Insumo $insumo)
    {
        $validated = $request->validate([
            'id_cat_insumo' => 'required|exists:categorias_insumo,id_cat_insumo',
            'nombre' => 'required|string|max:255',
            'precio_compra' => 'required|numeric|min:0',
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
        $insumo->delete();

        return redirect()->route('insumos.index')
            ->with('success', 'Insumo eliminado exitosamente');
    }
}
