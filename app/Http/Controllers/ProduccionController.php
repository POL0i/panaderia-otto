<?php

namespace App\Http\Controllers;

use App\Models\Produccion;
use App\Models\Receta;
use App\Models\Empleado;
use Illuminate\Http\Request;

class ProduccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $producciones = Produccion::with('receta', 'empleado')
            ->orderBy('fecha_produccion', 'desc')
            ->paginate(15);

        return view('produccion.producciones.index', compact('producciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $recetas = Receta::all();
        $empleados = Empleado::all();

        return view('produccion.producciones.create', compact('recetas', 'empleados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha_produccion' => 'required|date',
            'cantidad_producida' => 'required|numeric|min:0.01',
            'id_receta' => 'required|exists:recetas,id_receta',
            'id_empleado' => 'required|exists:empleados,id_empleado',
        ]);

        Produccion::create($validated);

        return redirect()->route('producciones.index')
            ->with('success', 'Producción registrada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produccion $produccion)
    {
        $produccion->load('receta.detalles.insumo', 'empleado', 'detalles');
        return view('produccion.producciones.show', compact('produccion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produccion $produccion)
    {
        $recetas = Receta::all();
        $empleados = Empleado::all();

        return view('produccion.producciones.edit', compact('produccion', 'recetas', 'empleados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produccion $produccion)
    {
        $validated = $request->validate([
            'fecha_produccion' => 'required|date',
            'cantidad_producida' => 'required|numeric|min:0.01',
            'id_receta' => 'required|exists:recetas,id_receta',
            'id_empleado' => 'required|exists:empleados,id_empleado',
        ]);

        $produccion->update($validated);

        return redirect()->route('producciones.show', $produccion)
            ->with('success', 'Producción actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produccion $produccion)
    {
        $produccion->detalles()->delete();
        $produccion->delete();

        return redirect()->route('producciones.index')
            ->with('success', 'Producción eliminada correctamente');
    }

    /**
     * Filtrar por fecha
     */
    public function filtrar(Request $request)
    {
        $query = Produccion::with('receta', 'empleado');

        if ($request->fecha_inicio) {
            $query->whereDate('fecha_produccion', '>=', $request->fecha_inicio);
        }

        if ($request->fecha_fin) {
            $query->whereDate('fecha_produccion', '<=', $request->fecha_fin);
        }

        if ($request->id_receta) {
            $query->where('id_receta', $request->id_receta);
        }

        if ($request->id_empleado) {
            $query->where('id_empleado', $request->id_empleado);
        }

        $producciones = $query->orderBy('fecha_produccion', 'desc')->paginate(15);

        return view('produccion.producciones.index', compact('producciones'));
    }
}
