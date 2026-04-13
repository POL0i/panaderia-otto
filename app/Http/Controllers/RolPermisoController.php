<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolPermisoController extends Controller
{
    /**
     * Display a listing of rol_permisos.
     */
    public function index()
    {
        $rolPermisos = \App\Models\RolPermiso::with(['rol', 'permiso'])->get();
        return view('rol_permisos.index', compact('rolPermisos'));
    }

    /**
     * Show the form for creating a new rol_permiso.
     */
    public function create()
    {
        $roles = \App\Models\Rol::all();
        $permisos = \App\Models\Permiso::all();
        return view('rol_permisos.create', compact('roles', 'permisos'));
    }

    /**
     * Store a newly created rol_permiso in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_rol' => 'required|exists:roles,id_rol',
            'id_permiso' => 'required|exists:permisos,id_permiso',
            'estado' => 'required|in:activo,inactivo',
            'descripcion' => 'nullable|string|max:50',
        ]);

        \App\Models\RolPermiso::create($validated);

        return redirect()->route('rol_permisos.index')->with('success', 'Rol-Permiso asignado exitosamente.');
    }

    /**
     * Show the form for editing the specified rol_permiso.
     */
    public function edit($id)
    {
        $rolPermiso = \App\Models\RolPermiso::findOrFail($id);
        $roles = \App\Models\Rol::all();
        $permisos = \App\Models\Permiso::all();
        return view('rol_permisos.edit', compact('rolPermiso', 'roles', 'permisos'));
    }

    /**
     * Update the specified rol_permiso in database.
     */
    public function update(Request $request, $id)
    {
        $rolPermiso = \App\Models\RolPermiso::findOrFail($id);

        $validated = $request->validate([
            'estado' => 'required|in:activo,inactivo',
            'descripcion' => 'nullable|string|max:50',
        ]);

        $rolPermiso->update($validated);

        return redirect()->route('rol_permisos.index')->with('success', 'Rol-Permiso actualizado exitosamente.');
    }

    /**
     * Remove the specified rol_permiso from database.
     */
    public function destroy($id)
    {
        $rolPermiso = \App\Models\RolPermiso::findOrFail($id);
        $rolPermiso->delete();

        return redirect()->route('rol_permisos.index')->with('success', 'Rol-Permiso eliminado exitosamente.');
    }

    /**
     * Desactivate the specified rol_permiso.
     */
    public function deactivate($id)
    {
        $rolPermiso = \App\Models\RolPermiso::findOrFail($id);
        $rolPermiso->update(['estado' => 'inactivo']);

        return redirect()->route('rol_permisos.index')->with('success', 'Rol-Permiso desactivado exitosamente.');
    }
}
