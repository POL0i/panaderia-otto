<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use App\Models\NotaVenta;
use App\Models\Producto;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detallesVenta = DetalleVenta::with(['notaVenta', 'producto'])
            ->paginate(15);
        
        return view('detalleventa.index', compact('detallesVenta'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $notasVenta = NotaVenta::all();
        $productos = Producto::all();
        
        return view('detalleventa.create', compact('notasVenta', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_nota_venta' => 'required|exists:notas_venta,id_nota_venta',
            'id_producto' => 'required|exists:productos,id_producto',
            'cantidad' => 'required|numeric|min:1',
            'precio' => 'required|numeric|min:0',
        ]);

        DetalleVenta::create($validated);

        return redirect()->route('detalles-venta.index')
            ->with('success', 'Detalle de venta creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($notaVenta, $producto)
    {
        $detalleVenta = DetalleVenta::where('id_nota_venta', $notaVenta)
            ->where('id_producto', $producto)
            ->with(['notaVenta', 'producto'])
            ->firstOrFail();
        
        return view('detalleventa.show', compact('detalleVenta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($notaVenta, $producto)
    {
        $detalleVenta = DetalleVenta::where('id_nota_venta', $notaVenta)
            ->where('id_producto', $producto)
            ->firstOrFail();
        
        $notasVenta = NotaVenta::all();
        $productos = Producto::all();
        
        return view('detalleventa.edit', compact('detalleVenta', 'notasVenta', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $notaVenta, $producto)
    {
        $detalleVenta = DetalleVenta::where('id_nota_venta', $notaVenta)
            ->where('id_producto', $producto)
            ->firstOrFail();

        $validated = $request->validate([
            'cantidad' => 'required|numeric|min:1',
            'precio' => 'required|numeric|min:0',
        ]);

        $detalleVenta->update($validated);

        return redirect()->route('detalles-venta.index')
            ->with('success', 'Detalle de venta actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($notaVenta, $producto)
    {
        $detalleVenta = DetalleVenta::where('id_nota_venta', $notaVenta)
            ->where('id_producto', $producto)
            ->firstOrFail();

        $detalleVenta->delete();

        return redirect()->route('detalles-venta.index')
            ->with('success', 'Detalle de venta eliminado exitosamente');
    }
}
