<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of empleados.
     */
    public function index()
    {
        $empleados = \App\Models\Empleado::all();
        return view('empleados.index', compact('empleados'));
    }

    /**
     * Show the form for creating a new empleado.
     */
    public function create()
    {
        return view('empleados.create');
    }

    /**
     * Store a newly created empleado in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:25',
            'apellido' => 'required|string|max:25',
            'telefono' => 'required|integer',
            'direccion' => 'required|string|max:35',
            'fecha_nac' => 'required|date',
            'sueldo' => 'required|integer',
            'edad' => 'required|integer',
        ]);

        \App\Models\Empleado::create($validated);

        return redirect()->route('empleados.index')->with('success', 'Empleado creado exitosamente.');
    }

    /**
     * Show the form for editing the specified empleado.
     */
    public function edit($id)
    {
        $empleado = \App\Models\Empleado::findOrFail($id);
        return view('empleados.edit', compact('empleado'));
    }

    /**
     * Update the specified empleado in database.
     */
    public function update(Request $request, $id)
    {
        $empleado = \App\Models\Empleado::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:25',
            'apellido' => 'required|string|max:25',
            'telefono' => 'required|integer',
            'direccion' => 'required|string|max:35',
            'fecha_nac' => 'required|date',
            'sueldo' => 'required|integer',
            'edad' => 'required|integer',
        ]);

        $empleado->update($validated);

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado exitosamente.');
    }

    /**
     * Remove the specified empleado from database.
     */
    public function destroy($id)
    {
        $empleado = \App\Models\Empleado::findOrFail($id);
        $empleado->delete();

        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado exitosamente.');
    }
}
