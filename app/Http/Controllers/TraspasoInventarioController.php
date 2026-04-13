<?php

namespace App\Http\Controllers;

use App\Models\TraspasoInventario;
use App\Models\MovimientoInventario;
use App\Models\Almacen;
use App\Models\Item;
use Illuminate\Http\Request;

class TraspasoInventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $traspasos = TraspasoInventario::with('almacenOrigen', 'almacenDestino', 'item')
            ->orderBy('fecha_traspaso', 'desc')
            ->paginate(15);

        return view('inventario.traspasos.index', compact('traspasos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $almacenes = Almacen::all();
        $items = Item::all();

        return view('inventario.traspasos.create', compact('almacenes', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_almacen_origen' => 'required|different:id_almacen_destino|exists:almacenes,id_almacen',
            'id_almacen_destino' => 'required|different:id_almacen_origen|exists:almacenes,id_almacen',
            'id_item' => 'required|exists:items,id_item',
            'cantidad' => 'required|numeric|min:0.01',
            'precio_unitario' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string',
        ]);

        $validated['fecha_traspaso'] = now();
        $validated['estado'] = 'pendiente';

        TraspasoInventario::create($validated);

        return redirect()->route('traspasos.index')
            ->with('success', 'Traspaso creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(TraspasoInventario $traspaso)
    {
        $traspaso->load('almacenOrigen', 'almacenDestino', 'item');
        return view('inventario.traspasos.show', compact('traspaso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TraspasoInventario $traspaso)
    {
        $almacenes = Almacen::all();
        $items = Item::all();

        return view('inventario.traspasos.edit', compact('traspaso', 'almacenes', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TraspasoInventario $traspaso)
    {
        $validated = $request->validate([
            'id_almacen_origen' => 'required|different:id_almacen_destino|exists:almacenes,id_almacen',
            'id_almacen_destino' => 'required|different:id_almacen_origen|exists:almacenes,id_almacen',
            'id_item' => 'required|exists:items,id_item',
            'cantidad' => 'required|numeric|min:0.01',
            'precio_unitario' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string',
        ]);

        $traspaso->update($validated);

        return redirect()->route('traspasos.show', $traspaso)
            ->with('success', 'Traspaso actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TraspasoInventario $traspaso)
    {
        if ($traspaso->estado === 'completado') {
            return back()->with('error', 'No se puede eliminar un traspaso completado');
        }

        $traspaso->delete();

        return redirect()->route('traspasos.index')
            ->with('success', 'Traspaso eliminado correctamente');
    }

    /**
     * Completar traspaso (cambiar estado a completado)
     */
    public function completar(TraspasoInventario $traspaso)
    {
        if ($traspaso->estado === 'completado') {
            return back()->with('error', 'Este traspaso ya ha sido completado');
        }

        // Crear movimientos de salida y entrada
        MovimientoInventario::create([
            'tipo_movimiento' => 'traspaso_origen',
            'id_almacen' => $traspaso->id_almacen_origen,
            'id_item' => $traspaso->id_item,
            'cantidad' => $traspaso->cantidad,
            'precio_unitario' => $traspaso->precio_unitario,
            'costo_total' => $traspaso->cantidad * $traspaso->precio_unitario,
            'fecha_movimiento' => now(),
            'referencia_id' => $traspaso->id_traspaso,
            'referencia_tipo' => 'traspaso',
            'estado' => 'completado',
            'observaciones' => $traspaso->observaciones,
        ]);

        MovimientoInventario::create([
            'tipo_movimiento' => 'traspaso_destino',
            'id_almacen' => $traspaso->id_almacen_destino,
            'id_item' => $traspaso->id_item,
            'cantidad' => $traspaso->cantidad,
            'precio_unitario' => $traspaso->precio_unitario,
            'costo_total' => $traspaso->cantidad * $traspaso->precio_unitario,
            'fecha_movimiento' => now(),
            'referencia_id' => $traspaso->id_traspaso,
            'referencia_tipo' => 'traspaso',
            'estado' => 'completado',
            'observaciones' => $traspaso->observaciones,
        ]);

        $traspaso->update(['estado' => 'completado']);

        return back()->with('success', 'Traspaso completado correctamente');
    }

    /**
     * Cancelar traspaso
     */
    public function cancelar(TraspasoInventario $traspaso)
    {
        if ($traspaso->estado === 'completado') {
            return back()->with('error', 'No se puede cancelar un traspaso completado');
        }

        $traspaso->update(['estado' => 'cancelado']);

        return back()->with('success', 'Traspaso cancelado correctamente');
    }
}
