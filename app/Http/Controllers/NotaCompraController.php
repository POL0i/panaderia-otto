<?php

namespace App\Http\Controllers;

use App\Models\NotaCompra;
use App\Models\Empleado;
use App\Models\Proveedor;
use App\Models\DetalleCompra;
use Illuminate\Http\Request;

class NotaCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notasCompra = NotaCompra::with(['empleado', 'proveedor'])
            ->orderBy('fecha_compra', 'desc')
            ->paginate(15);
        
        return view('notacompra.index', compact('notasCompra'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empleados = Empleado::all();
        $proveedores = Proveedor::all();
        
        return view('notacompra.create', compact('empleados', 'proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha_compra' => 'required|date',
            'monto_total' => 'required|numeric|min:0',
            'estado' => 'required|string',
            'id_empleado' => 'required|exists:empleados,id_empleado',
            'id_proveedor' => 'required|exists:proveedores,id_proveedor',
        ]);

        NotaCompra::create($validated);

        return redirect()->route('notas-compra.index')
            ->with('success', 'Nota de compra creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(NotaCompra $notaCompra)
    {
        $notaCompra->load(['empleado', 'proveedor', 'detalles.insumo']);
        
        return view('notacompra.show', compact('notaCompra'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NotaCompra $notaCompra)
    {
        $empleados = Empleado::all();
        $proveedores = Proveedor::all();
        
        return view('notacompra.edit', compact('notaCompra', 'empleados', 'proveedores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NotaCompra $notaCompra)
    {
        $validated = $request->validate([
            'fecha_compra' => 'required|date',
            'monto_total' => 'required|numeric|min:0',
            'estado' => 'required|string',
            'id_empleado' => 'required|exists:empleados,id_empleado',
            'id_proveedor' => 'required|exists:proveedores,id_proveedor',
        ]);

        $notaCompra->update($validated);

        return redirect()->route('notas-compra.index')
            ->with('success', 'Nota de compra actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NotaCompra $notaCompra)
    {
        $notaCompra->delete();

        return redirect()->route('notas-compra.index')
            ->with('success', 'Nota de compra eliminada exitosamente');
    }
}
