<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\Permiso;
use App\Models\RolPermiso;

class RolPermisoController extends Controller
{
    /**
     * Display a listing of roles with their permissions.
     */
    public function index()
    {
        $roles = Rol::with('permisos')->get();
        $todosRoles = Rol::all(['nombre']);
        $todosPermisos = Permiso::all(['nombre']);
        
        return view('rol_permisos.index', compact('roles', 'todosRoles', 'todosPermisos'));
    }

    /**
     * Show the form for creating a new rol-permiso assignment.
     */
    public function create(Request $request)
    {
        $roles = Rol::all();
        $permisos = Permiso::all();
        
        // Obtener el rol preseleccionado si viene por URL
        $selectedRol = $request->query('rol');
        
        return view('rol_permisos.create', compact('roles', 'permisos', 'selectedRol'));
    }

    /**
     * Store multiple permisos for a role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_rol' => 'required|exists:roles,id_rol',
            'permisos' => 'required|array|min:1',
            'permisos.*' => 'required|exists:permisos,id_permiso',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $rolId = $validated['id_rol'];
        $estado = $validated['estado'];
        $permisosIds = $validated['permisos'];
        
        $asignados = 0;
        $existentes = 0;
        
        foreach ($permisosIds as $permisoId) {
            $existente = RolPermiso::where('id_rol', $rolId)
                                ->where('id_permiso', $permisoId)
                                ->first();
            
            if (!$existente) {
                RolPermiso::create([
                    'id_rol' => $rolId,
                    'id_permiso' => $permisoId,
                    'estado' => $estado,
                ]);
                $asignados++;
            } else {
                $existentes++;
            }
        }
        
        $message = "Se asignaron {$asignados} permiso(s) correctamente.";
        if ($existentes > 0) {
            $message .= " {$existentes} permiso(s) ya existían y fueron omitidos.";
        }
        
        return redirect()->route('rol_permisos.index')->with('success', $message);
    }

    /**
     * Show form to edit a role.
     */
    public function editRole($id)
    {
        $rol = Rol::findOrFail($id);
        return view('rol_permisos.edit-rol', compact('rol'));
    }

    /**
     * Update a role.
     */
    public function updateRole(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:roles,nombre,' . $id . ',id_rol',
        ]);
        
        $rol->update($validated);
        
        return redirect()->route('rol_permisos.index')
                        ->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Delete a role and all its permission assignments.
     */
    public function destroyRole($id)
    {
        $rol = Rol::findOrFail($id);
        
        // Eliminar todas las asignaciones de permisos
        RolPermiso::where('id_rol', $id)->delete();
        
        // Eliminar el rol
        $rol->delete();
        
        return redirect()->route('rol_permisos.index')
                        ->with('success', 'Rol y todas sus asignaciones eliminados exitosamente.');
    }

    /**
     * Remove all permissions from a role.
     */
    public function clearPermissions($id)
    {
        $rol = Rol::findOrFail($id);
        $count = RolPermiso::where('id_rol', $id)->count();
        
        RolPermiso::where('id_rol', $id)->delete();
        
        return redirect()->route('rol_permisos.index')
                        ->with('success', "Se eliminaron {$count} asignaciones del rol '{$rol->nombre}'.");
    }

    /**
     * Show the form for editing a rol-permiso assignment.
     */
    public function edit($id)
    {
        $rolPermiso = RolPermiso::findOrFail($id);
        $roles = Rol::all();
        $permisos = Permiso::all();
        return view('rol_permisos.edit', compact('rolPermiso', 'roles', 'permisos'));
    }

    /**
     * Update a rol-permiso assignment.
     */
    public function update(Request $request, $id)
    {
        $rolPermiso = RolPermiso::findOrFail($id);
        
        $validated = $request->validate([
            'estado' => 'required|in:activo,inactivo',
        ]);
        
        $rolPermiso->update($validated);
        
        return redirect()->route('rol_permisos.index')
                        ->with('success', 'Asignación actualizada exitosamente.');
    }

    /**
     * Remove a specific rol-permiso assignment.
     */
    public function destroy($id)
    {
        $rolPermiso = RolPermiso::findOrFail($id);
        $rolPermiso->delete();
        
        return redirect()->route('rol_permisos.index')
                        ->with('success', 'Asignación eliminada exitosamente.');
    }
}