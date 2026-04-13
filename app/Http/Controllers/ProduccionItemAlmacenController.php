<?php

namespace App\Http\Controllers;

use App\Models\ProduccionItemAlmacen;
use App\Models\Produccion;
use App\Models\Almacen;
use App\Models\Item;
use Illuminate\Http\Request;

class ProduccionItemAlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $producciones = ProduccionItemAlmacen::with('produccion', 'almacen', 'item')
            ->orderBy('id_produccion')
            ->paginate(15);

        return view('produccion.produccion-items.index', compact('producciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $producciones = Produccion::with('receta')->get();
        $almacenes = Almacen::all();
        $items = Item::all();

        return view('produccion.produccion-items.create', compact('producciones', 'almacenes', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_produccion' => 'required|exists:producciones,id_produccion',
            'id_almacen' => 'required|exists:almacenes,id_almacen',
            'id_item' => 'required|exists:items,id_item',
            'cantidad_producida' => 'required|numeric|min:0.01',
        ]);

        // Verificar que no exista la misma combinación
        $existe = ProduccionItemAlmacen::where('id_produccion', $validated['id_produccion'])
            ->where('id_almacen', $validated['id_almacen'])
            ->where('id_item', $validated['id_item'])
            ->exists();

        if ($existe) {
            return back()->with('error', 'Ya existe este producto en este almacén para esta producción');
        }

        ProduccionItemAlmacen::create($validated);

        return redirect()->route('produccion-items.index')
            ->with('success', 'Asignación de producción creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProduccionItemAlmacen $produccionItem)
    {
        $produccionItem->load('produccion.receta', 'almacen', 'item');
        return view('produccion.produccion-items.show', compact('produccionItem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProduccionItemAlmacen $produccionItem)
    {
        $producciones = Produccion::with('receta')->get();
        $almacenes = Almacen::all();
        $items = Item::all();

        return view('produccion.produccion-items.edit', compact('produccionItem', 'producciones', 'almacenes', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProduccionItemAlmacen $produccionItem)
    {
        $validated = $request->validate([
            'id_produccion' => 'required|exists:producciones,id_produccion',
            'id_almacen' => 'required|exists:almacenes,id_almacen',
            'id_item' => 'required|exists:items,id_item',
            'cantidad_producida' => 'required|numeric|min:0.01',
        ]);

        // Verificar que no exista la misma combinación en otra fila
        $existe = ProduccionItemAlmacen::where('id_produccion', $validated['id_produccion'])
            ->where('id_almacen', $validated['id_almacen'])
            ->where('id_item', $validated['id_item'])
            ->where('id_produccion_item_almacen', '!=', $produccionItem->id_produccion_item_almacen)
            ->exists();

        if ($existe) {
            return back()->with('error', 'Ya existe este producto en este almacén para esta producción');
        }

        $produccionItem->update($validated);

        return redirect()->route('produccion-items.show', $produccionItem)
            ->with('success', 'Asignación de producción actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProduccionItemAlmacen $produccionItem)
    {
        $produccionItem->delete();

        return redirect()->route('produccion-items.index')
            ->with('success', 'Asignación de producción eliminada correctamente');
    }

    /**
     * Filtrar por producción
     */
    public function porProduccion($id_produccion)
    {
        $produccion = Produccion::with('receta')->findOrFail($id_produccion);
        $producciones = ProduccionItemAlmacen::where('id_produccion', $id_produccion)
            ->with('almacen', 'item')
            ->paginate(15);

        return view('produccion.produccion-items.index', compact('producciones', 'produccion'));
    }
}
