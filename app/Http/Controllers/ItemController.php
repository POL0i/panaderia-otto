<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with(['producto', 'insumo'])
            ->paginate(15);
        
        return view('item.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('item.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_item' => 'required|in:producto,insumo',
            'unidad_medida' => 'required|string|max:50',
        ]);

        Item::create($validated);

        return redirect()->route('items.index')
            ->with('success', 'Item creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->load(['producto', 'insumo', 'almacenItems', 'produccionMovimientos']);
        
        return view('item.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        return view('item.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'tipo_item' => 'required|in:producto,insumo',
            'unidad_medida' => 'required|string|max:50',
        ]);

        $item->update($validated);

        return redirect()->route('items.index')
            ->with('success', 'Item actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Item eliminado exitosamente');
    }
}
