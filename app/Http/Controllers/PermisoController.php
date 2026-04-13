<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermisoController extends Controller
{
    /**
     * Display a listing of permisos.
     */
    public function index()
    {
        $permisos = \App\Models\Permiso::all();
        return view('permisos.index', compact('permisos'));
    }

    /**
     * Show the form for creating a new permiso.
     */
    public function create()
    {
        return view('permisos.create');
    }

    /**
     * Store a newly created permiso in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:20|unique:permisos',
        ]);

        \App\Models\Permiso::create($validated);

        return redirect()->route('permisos.index')->with('success', 'Permiso creado exitosamente.');
    }

    /**
     * Show the form for editing the specified permiso.
     */
    public function edit($id)
    {
        $permiso = \App\Models\Permiso::findOrFail($id);
        return view('permisos.edit', compact('permiso'));
    }

    /**
     * Update the specified permiso in database.
     */
    public function update(Request $request, $id)
    {
        $permiso = \App\Models\Permiso::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:20|unique:permisos,nombre,' . $id . ',id_permiso',
        ]);

        $permiso->update($validated);

        return redirect()->route('permisos.index')->with('success', 'Permiso actualizado exitosamente.');
    }

    /**
     * Remove the specified permiso from database.
     */
    public function destroy($id)
    {
        $permiso = \App\Models\Permiso::findOrFail($id);
        $permiso->delete();

        return redirect()->route('permisos.index')->with('success', 'Permiso eliminado exitosamente.');
    }
}
