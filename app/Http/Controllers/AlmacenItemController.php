<?php

namespace App\Http\Controllers;

use App\Models\AlmacenItem;
use App\Models\Almacen;
use App\Models\Item;
use Illuminate\Http\Request;

class AlmacenItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $almacenItems = AlmacenItem::with(['almacen', 'item'])
            ->paginate(15);
        
        return view('almacen-item.index', compact('almacenItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $almacenes = Almacen::all();
        $items = Item::all();
        
        return view('almacen-item.create', compact('almacenes', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_almacen' => 'required|exists:almacenes,id_almacen',
            'id_item' => 'required|exists:items,id_item',
            'stock' => 'required|numeric|min:0',
        ]);

        AlmacenItem::create($validated);

        return redirect()->route('almacen-items.index')
            ->with('success', 'Almacén-Item creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($almacen, $item)
    {
        $almacenItem = AlmacenItem::where('id_almacen', $almacen)
            ->where('id_item', $item)
            ->with(['almacen', 'item'])
            ->firstOrFail();
        
        return view('almacen-item.show', compact('almacenItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($almacen, $item)
    {
        $almacenItem = AlmacenItem::where('id_almacen', $almacen)
            ->where('id_item', $item)
            ->firstOrFail();
        
        $almacenes = Almacen::all();
        $items = Item::all();
        
        return view('almacen-item.edit', compact('almacenItem', 'almacenes', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $almacen, $item)
    {
        $almacenItem = AlmacenItem::where('id_almacen', $almacen)
            ->where('id_item', $item)
            ->firstOrFail();

        $validated = $request->validate([
            'stock' => 'required|numeric|min:0',
        ]);

        $almacenItem->update($validated);

        return redirect()->route('almacen-items.index')
            ->with('success', 'Almacén-Item actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($almacen, $item)
    {
        $almacenItem = AlmacenItem::where('id_almacen', $almacen)
            ->where('id_item', $item)
            ->firstOrFail();

        $almacenItem->delete();

        return redirect()->route('almacen-items.index')
            ->with('success', 'Almacén-Item eliminado exitosamente');
    }
}
