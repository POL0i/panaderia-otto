<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolPermisoUsuarioController extends Controller
{
    /**
     * Display a listing of rol_permiso_usuarios.
     */
    public function index()
    {
        $rolPermisoUsuarios = \App\Models\RolPermisoUsuario::with(['rolPermiso.rol', 'rolPermiso.permiso', 'usuario'])->get();
        return view('rol_permiso_usuarios.index', compact('rolPermisoUsuarios'));
    }

    /**
     * Show the form for creating a new rol_permiso_usuario.
     */
    public function create()
    {
        $rolPermisos = \App\Models\RolPermiso::with(['rol', 'permiso'])->get();
        $usuarios = \App\Models\Usuario::all();
        return view('rol_permiso_usuarios.create', compact('rolPermisos', 'usuarios'));
    }

    /**
     * Store a newly created rol_permiso_usuario in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_rol_permiso' => 'required|exists:rol_permiso,id_rol_permiso',
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'estado' => 'required|in:activo,inactivo',
        ]);

        \App\Models\RolPermisoUsuario::create($validated);

        return redirect()->route('rol_permiso_usuarios.index')->with('success', 'Rol-Permiso asignado al usuario exitosamente.');
    }

    /**
     * Show the form for editing the specified rol_permiso_usuario.
     */
    public function edit($id)
    {
        $rolPermisoUsuario = \App\Models\RolPermisoUsuario::findOrFail($id);
        $rolPermisos = \App\Models\RolPermiso::with(['rol', 'permiso'])->get();
        $usuarios = \App\Models\Usuario::all();
        return view('rol_permiso_usuarios.edit', compact('rolPermisoUsuario', 'rolPermisos', 'usuarios'));
    }

    /**
     * Update the specified rol_permiso_usuario in database.
     */
    public function update(Request $request, $id)
    {
        $rolPermisoUsuario = \App\Models\RolPermisoUsuario::findOrFail($id);

        $validated = $request->validate([
            'estado' => 'required|in:activo,inactivo',
        ]);

        $rolPermisoUsuario->update($validated);

        return redirect()->route('rol_permiso_usuarios.index')->with('success', 'Rol-Permiso del usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified rol_permiso_usuario from database.
     */
    public function destroy($id)
    {
        $rolPermisoUsuario = \App\Models\RolPermisoUsuario::findOrFail($id);
        $rolPermisoUsuario->delete();

        return redirect()->route('rol_permiso_usuarios.index')->with('success', 'Rol-Permiso del usuario eliminado exitosamente.');
    }

    /**
     * Desactivate the specified rol_permiso_usuario.
     */
    public function deactivate($id)
    {
        $rolPermisoUsuario = \App\Models\RolPermisoUsuario::findOrFail($id);
        $rolPermisoUsuario->update(['estado' => 'inactivo']);

        return redirect()->route('rol_permiso_usuarios.index')->with('success', 'Rol-Permiso del usuario desactivado exitosamente.');
    }
}
