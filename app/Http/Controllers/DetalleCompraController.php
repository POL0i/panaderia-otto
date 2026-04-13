<?php

namespace App\Http\Controllers;

use App\Models\DetalleCompra;
use App\Models\NotaCompra;
use App\Models\Insumo;
use Illuminate\Http\Request;

class DetalleCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detallesCompra = DetalleCompra::with(['notaCompra', 'insumo'])
            ->paginate(15);
        
        return view('detallecompra.index', compact('detallesCompra'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $notasCompra = NotaCompra::all();
        $insumos = Insumo::all();
        
        return view('detallecompra.create', compact('notasCompra', 'insumos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_nota_compra' => 'required|exists:notas_compra,id_nota_compra',
            'id_insumo' => 'required|exists:insumos,id_insumo',
            'cantidad' => 'required|numeric|min:1',
            'precio' => 'required|numeric|min:0',
        ]);

        DetalleCompra::create($validated);

        return redirect()->route('detalles-compra.index')
            ->with('success', 'Detalle de compra creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($notaCompra, $insumo)
    {
        $detalleCompra = DetalleCompra::where('id_nota_compra', $notaCompra)
            ->where('id_insumo', $insumo)
            ->with(['notaCompra', 'insumo'])
            ->firstOrFail();
        
        return view('detallecompra.show', compact('detalleCompra'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($notaCompra, $insumo)
    {
        $detalleCompra = DetalleCompra::where('id_nota_compra', $notaCompra)
            ->where('id_insumo', $insumo)
            ->firstOrFail();
        
        $notasCompra = NotaCompra::all();
        $insumos = Insumo::all();
        
        return view('detallecompra.edit', compact('detalleCompra', 'notasCompra', 'insumos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $notaCompra, $insumo)
    {
        $detalleCompra = DetalleCompra::where('id_nota_compra', $notaCompra)
            ->where('id_insumo', $insumo)
            ->firstOrFail();

        $validated = $request->validate([
            'cantidad' => 'required|numeric|min:1',
            'precio' => 'required|numeric|min:0',
        ]);

        $detalleCompra->update($validated);

        return redirect()->route('detalles-compra.index')
            ->with('success', 'Detalle de compra actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($notaCompra, $insumo)
    {
        $detalleCompra = DetalleCompra::where('id_nota_compra', $notaCompra)
            ->where('id_insumo', $insumo)
            ->firstOrFail();

        $detalleCompra->delete();

        return redirect()->route('detalles-compra.index')
            ->with('success', 'Detalle de compra eliminado exitosamente');
    }
}
