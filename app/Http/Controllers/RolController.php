<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index()
    {
        $roles = \App\Models\Rol::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new rol.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created rol in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:20|unique:roles',
        ]);

        \App\Models\Rol::create($validated);

        return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
    }

    /**
     * Show the form for editing the specified rol.
     */
    public function edit($id)
    {
        $rol = \App\Models\Rol::findOrFail($id);
        return view('roles.edit', compact('rol'));
    }

    /**
     * Update the specified rol in database.
     */
    public function update(Request $request, $id)
    {
        $rol = \App\Models\Rol::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:20|unique:roles,nombre,' . $id . ',id_rol',
        ]);

        $rol->update($validated);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Remove the specified rol from database.
     */
    public function destroy($id)
    {
        $rol = \App\Models\Rol::findOrFail($id);
        $rol->delete();

        return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente.');
    }
}
