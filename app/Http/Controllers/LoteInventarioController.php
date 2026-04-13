<?php

namespace App\Http\Controllers;

use App\Models\LoteInventario;
use App\Models\Almacen;
use App\Models\Item;
use Illuminate\Http\Request;

class LoteInventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lotes = LoteInventario::with('almacen', 'item')
            ->orderBy('fecha_entrada', 'desc')
            ->paginate(15);

        return view('inventario.lotes.index', compact('lotes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $almacenes = Almacen::all();
        $items = Item::all();

        return view('inventario.lotes.create', compact('almacenes', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_almacen' => 'required|exists:almacenes,id_almacen',
            'id_item' => 'required|exists:items,id_item',
            'cantidad_inicial' => 'required|numeric|min:0.01',
            'precio_unitario' => 'required|numeric|min:0',
            'metodo_valuacion' => 'required|in:PEPS,UEPS',
        ]);

        $validated['cantidad_disponible'] = $validated['cantidad_inicial'];
        $validated['fecha_entrada'] = now();
        $validated['estado'] = 'disponible';

        LoteInventario::create($validated);

        return redirect()->route('lotes.index')
            ->with('success', 'Lote registrado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(LoteInventario $lote)
    {
        $lote->load('almacen', 'item');
        return view('inventario.lotes.show', compact('lote'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoteInventario $lote)
    {
        $almacenes = Almacen::all();
        $items = Item::all();

        return view('inventario.lotes.edit', compact('lote', 'almacenes', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoteInventario $lote)
    {
        $validated = $request->validate([
            'cantidad_disponible' => 'required|numeric|min:0|max:' . $lote->cantidad_inicial,
            'metodo_valuacion' => 'required|in:PEPS,UEPS',
        ]);

        // Si la cantidad disponible llega a 0, marcar como consumido
        if ($validated['cantidad_disponible'] == 0) {
            $validated['estado'] = 'consumido';
            $validated['fecha_salida'] = now();
        } else {
            $validated['estado'] = 'disponible';
        }

        $lote->update($validated);

        return redirect()->route('lotes.show', $lote)
            ->with('success', 'Lote actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoteInventario $lote)
    {
        if ($lote->cantidad_disponible != $lote->cantidad_inicial) {
            return back()->with('error', 'No se puede eliminar un lote que ha sido parcialmente consumido');
        }

        $lote->delete();

        return redirect()->route('lotes.index')
            ->with('success', 'Lote eliminado correctamente');
    }

    /**
     * Filtrar lotes por almacén, item o método de valuación
     */
    public function filtrar(Request $request)
    {
        $query = LoteInventario::with('almacen', 'item');

        if ($request->id_almacen) {
            $query->where('id_almacen', $request->id_almacen);
        }

        if ($request->id_item) {
            $query->where('id_item', $request->id_item);
        }

        if ($request->metodo_valuacion) {
            $query->where('metodo_valuacion', $request->metodo_valuacion);
        }

        if ($request->estado) {
            $query->where('estado', $request->estado);
        }

        $lotes = $query->orderBy('fecha_entrada', 'desc')->paginate(15);

        return view('inventario.lotes.index', compact('lotes'));
    }

    /**
     * Consumir cantidad del lote (PEPS o UEPS)
     */
    public function consumir(Request $request, LoteInventario $lote)
    {
        $validated = $request->validate([
            'cantidad' => 'required|numeric|min:0.01|max:' . $lote->cantidad_disponible,
        ]);

        $lote->cantidad_disponible -= $validated['cantidad'];

        if ($lote->cantidad_disponible <= 0) {
            $lote->estado = 'consumido';
            $lote->fecha_salida = now();
        }

        $lote->save();

        return back()->with('success', 'Cantidad consumida correctamente');
    }
}
