<?php

namespace App\Http\Controllers;

use App\Models\MovimientoInventario;
use App\Models\Almacen;
use App\Models\Item;
use Illuminate\Http\Request;

class MovimientoInventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movimientos = MovimientoInventario::with('almacen', 'item')
            ->orderBy('fecha_movimiento', 'desc')
            ->paginate(15);

        return view('inventario.movimientos.index', compact('movimientos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $almacenes = Almacen::all();
        $items = Item::all();
        $tipos_movimiento = ['ingreso', 'egreso', 'ajuste'];
        $referencias_tipo = ['compra', 'venta', 'produccion', 'ajuste', 'traspaso'];

        return view('inventario.movimientos.create', compact('almacenes', 'items', 'tipos_movimiento', 'referencias_tipo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_movimiento' => 'required|in:ingreso,egreso,ajuste',
            'id_almacen' => 'required|exists:almacenes,id_almacen',
            'id_item' => 'required|exists:items,id_item',
            'cantidad' => 'required|numeric|min:0.01',
            'precio_unitario' => 'required|numeric|min:0',
            'referencia_tipo' => 'required|in:compra,venta,produccion,ajuste,traspaso',
            'referencia_id' => 'nullable|numeric',
            'observaciones' => 'nullable|string',
        ]);

        $validated['costo_total'] = $validated['cantidad'] * $validated['precio_unitario'];
        $validated['fecha_movimiento'] = now();
        $validated['estado'] = 'completado';

        MovimientoInventario::create($validated);

        return redirect()->route('movimientos.index')
            ->with('success', 'Movimiento registrado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(MovimientoInventario $movimiento)
    {
        $movimiento->load('almacen', 'item');
        return view('inventario.movimientos.show', compact('movimiento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MovimientoInventario $movimiento)
    {
        $almacenes = Almacen::all();
        $items = Item::all();
        $tipos_movimiento = ['ingreso', 'egreso', 'ajuste'];
        $referencias_tipo = ['compra', 'venta', 'produccion', 'ajuste', 'traspaso'];

        return view('inventario.movimientos.edit', compact('movimiento', 'almacenes', 'items', 'tipos_movimiento', 'referencias_tipo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MovimientoInventario $movimiento)
    {
        $validated = $request->validate([
            'tipo_movimiento' => 'required|in:ingreso,egreso,ajuste',
            'id_almacen' => 'required|exists:almacenes,id_almacen',
            'id_item' => 'required|exists:items,id_item',
            'cantidad' => 'required|numeric|min:0.01',
            'precio_unitario' => 'required|numeric|min:0',
            'referencia_tipo' => 'required|in:compra,venta,produccion,ajuste,traspaso',
            'referencia_id' => 'nullable|numeric',
            'observaciones' => 'nullable|string',
        ]);

        $validated['costo_total'] = $validated['cantidad'] * $validated['precio_unitario'];

        $movimiento->update($validated);

        return redirect()->route('movimientos.show', $movimiento)
            ->with('success', 'Movimiento actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MovimientoInventario $movimiento)
    {
        $movimiento->delete();

        return redirect()->route('movimientos.index')
            ->with('success', 'Movimiento eliminado correctamente');
    }

    /**
     * Filtrar movimientos por almacén e item
     */
    public function filtrar(Request $request)
    {
        $query = MovimientoInventario::with('almacen', 'item');

        if ($request->id_almacen) {
            $query->where('id_almacen', $request->id_almacen);
        }

        if ($request->id_item) {
            $query->where('id_item', $request->id_item);
        }

        if ($request->tipo_movimiento) {
            $query->where('tipo_movimiento', $request->tipo_movimiento);
        }

        if ($request->fecha_inicio) {
            $query->whereDate('fecha_movimiento', '>=', $request->fecha_inicio);
        }

        if ($request->fecha_fin) {
            $query->whereDate('fecha_movimiento', '<=', $request->fecha_fin);
        }

        $movimientos = $query->orderBy('fecha_movimiento', 'desc')->paginate(15);

        return view('inventario.movimientos.index', compact('movimientos'));
    }
}
