<?php

namespace App\Http\Controllers;

use App\Models\NotaVenta;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;

class NotaVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notasVenta = NotaVenta::with(['cliente', 'empleado'])
            ->orderBy('fecha_venta', 'desc')
            ->paginate(15);
        
        return view('notaventa.index', compact('notasVenta'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::all();
        $empleados = Empleado::all();
        
        return view('notaventa.create', compact('clientes', 'empleados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha_venta' => 'required|date',
            'monto_total' => 'required|numeric|min:0',
            'estado' => 'required|string',
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'id_empleado' => 'required|exists:empleados,id_empleado',
        ]);

        NotaVenta::create($validated);

        return redirect()->route('notas-venta.index')
            ->with('success', 'Nota de venta creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(NotaVenta $notaVenta)
    {
        $notaVenta->load(['cliente', 'empleado', 'detalles.producto']);
        
        return view('notaventa.show', compact('notaVenta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NotaVenta $notaVenta)
    {
        $clientes = Cliente::all();
        $empleados = Empleado::all();
        
        return view('notaventa.edit', compact('notaVenta', 'clientes', 'empleados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NotaVenta $notaVenta)
    {
        $validated = $request->validate([
            'fecha_venta' => 'required|date',
            'monto_total' => 'required|numeric|min:0',
            'estado' => 'required|string',
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'id_empleado' => 'required|exists:empleados,id_empleado',
        ]);

        $notaVenta->update($validated);

        return redirect()->route('notas-venta.index')
            ->with('success', 'Nota de venta actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NotaVenta $notaVenta)
    {
        $notaVenta->delete();

        return redirect()->route('notas-venta.index')
            ->with('success', 'Nota de venta eliminada exitosamente');
    }
}
