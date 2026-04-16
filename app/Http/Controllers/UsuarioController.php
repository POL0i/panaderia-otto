<?php
// app/Http/Controllers/UsuarioController.php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Empleado;
use App\Models\Cliente;
use App\Models\Rol;
use App\Models\Permiso;
use App\Models\RolPermiso;
use App\Models\RolPermisoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = Usuario::with(['empleado', 'cliente', 'rolPermisoUsuarios.rolPermiso.rol', 'rolPermisoUsuarios.rolPermiso.permiso'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Módulo de acceso - Vista principal
     */
    public function createAccess()
    {
        $empleados = Empleado::whereDoesntHave('usuarios', function($query) {
                $query->where('tipo_usuario', 'empleado');
            })
            ->orWhereHas('usuarios', function($query) {
                $query->where('tipo_usuario', 'cliente');
            })
            ->get();
            
        $clientes = Cliente::whereDoesntHave('usuarios', function($query) {
                $query->where('tipo_usuario', 'cliente');
            })
            ->orWhereHas('usuarios', function($query) {
                $query->where('tipo_usuario', 'empleado');
            })
            ->get();
            
        $rolPermisos = RolPermiso::with(['rol', 'permiso'])
            ->where('estado', 'activo')
            ->get();
            
        $roles = Rol::all();
        $permisos = Permiso::all();
        
        // Para la tabla de usuarios con sus permisos
        $usuarios = Usuario::with(['empleado', 'cliente', 'rolPermisoUsuarios.rolPermiso.rol', 'rolPermisoUsuarios.rolPermiso.permiso'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('usuarios.acceso', compact('empleados', 'clientes', 'rolPermisos', 'roles', 'permisos', 'usuarios'));
    }

    /**
     * Store a newly created user with access
     */
    public function storeAccess(Request $request)
    {
        $validated = $request->validate([
            'correo' => 'required|email|unique:usuarios,correo',
            'contraseña' => 'required|min:8',
            'tipo_usuario' => 'required|in:cliente,empleado',
            'estado' => 'required|in:activo,inactivo',
            'id_empleado' => 'nullable|exists:empleados,id_empleado',
            'id_cliente' => 'nullable|exists:clientes,id_cliente',
            'rol_permiso_ids' => 'nullable|array',
            'rol_permiso_ids.*' => 'exists:rol_permiso,id_rol_permiso'
        ]);

        DB::beginTransaction();
        try {
            // Crear usuario
            $usuario = Usuario::create([
                'correo' => $validated['correo'],
                'contraseña' => Hash::make($validated['contraseña']),
                'tipo_usuario' => $validated['tipo_usuario'],
                'estado' => $validated['estado'],
                'id_empleado' => $validated['tipo_usuario'] == 'empleado' ? $validated['id_empleado'] : null,
                'id_cliente' => $validated['tipo_usuario'] == 'cliente' ? $validated['id_cliente'] : null,
            ]);

            // Asignar permisos
            if (!empty($validated['rol_permiso_ids'])) {
                foreach ($validated['rol_permiso_ids'] as $rolPermisoId) {
                    RolPermisoUsuario::create([
                        'id_rol_permiso' => $rolPermisoId,
                        'id_usuario' => $usuario->id_usuario,
                        'estado' => 'activo',
                        'fecha_asignacion' => now(),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('usuarios.create-access')
                ->with('success', 'Usuario creado exitosamente con sus permisos.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Obtener permisos de un usuario específico (para el modal)
     */
    
    public function getPermisosUsuario($id)
    {
        $usuario = Usuario::with(['empleado', 'cliente'])->findOrFail($id);
        
        // IDs de rol_permiso actuales
        $permisosActuales = $usuario->rolPermisoUsuarios()
            ->where('estado', 'activo')
            ->pluck('id_rol_permiso')
            ->toArray();
        
        // Nombres de permisos actuales para mostrar
        $permisosActualesNombres = DB::table('rol_permiso_usuario as rpu')
            ->join('rol_permiso as rp', 'rpu.id_rol_permiso', '=', 'rp.id_rol_permiso')
            ->join('permisos as p', 'rp.id_permiso', '=', 'p.id_permiso')
            ->where('rpu.id_usuario', $id)
            ->where('rpu.estado', 'activo')
            ->pluck('p.nombre')
            ->toArray();
        
        // Todos los rol_permiso disponibles
        $todosRolPermisos = RolPermiso::with(['rol', 'permiso'])
            ->where('estado', 'activo')
            ->get();
        
        return response()->json([
            'usuario' => $usuario,
            'permisos_actuales' => $permisosActuales,
            'permisos_actuales_nombres' => $permisosActualesNombres,
            'todos_rol_permisos' => $todosRolPermisos,
        ]);
    }

    /**
     * Actualizar permisos de un usuario
     */
    public function updatePermisos(Request $request, $id)
    {
        $validated = $request->validate([
            'rol_permiso_ids' => 'nullable|array',
            'rol_permiso_ids.*' => 'exists:rol_permiso,id_rol_permiso'
        ]);

        DB::beginTransaction();
        try {
            $usuario = Usuario::findOrFail($id);
            
            // Desactivar permisos actuales
            $usuario->rolPermisoUsuarios()->update(['estado' => 'inactivo']);
            
            // Asignar nuevos permisos
            if (!empty($validated['rol_permiso_ids'])) {
                foreach ($validated['rol_permiso_ids'] as $rolPermisoId) {
                    // Verificar si ya existe
                    $existente = RolPermisoUsuario::where([
                        'id_rol_permiso' => $rolPermisoId,
                        'id_usuario' => $id
                    ])->first();
                    
                    if ($existente) {
                        $existente->update(['estado' => 'activo']);
                    } else {
                        RolPermisoUsuario::create([
                            'id_rol_permiso' => $rolPermisoId,
                            'id_usuario' => $id,
                            'estado' => 'activo',
                            'fecha_asignacion' => now(),
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Permisos actualizados exitosamente'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar permisos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear nuevo empleado (desde modal)
     */
    public function storeEmpleado(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_nac' => 'nullable|date',
            'sueldo' => 'nullable|numeric',
            'edad' => 'nullable|integer',
        ]);

        $empleado = Empleado::create($validated);
        
        return response()->json([
            'success' => true,
            'empleado' => $empleado,
            'message' => 'Empleado creado exitosamente'
        ]);
    }

    /**
     * Crear nuevo rol (desde modal)
     */
    public function storeRol(Request $request)
    {
        // Aceptar tanto form-data como JSON
        if ($request->isJson()) {
            $data = $request->json()->all();
            $nombre = $data['nombre'] ?? null;
        } else {
            $nombre = $request->input('nombre');
        }
        
        $validated = validator(['nombre' => $nombre], [
            'nombre' => 'required|string|max:255|unique:roles,nombre'
        ])->validate();

        $rol = Rol::create(['nombre' => $validated['nombre']]);
        
        return response()->json([
            'success' => true,
            'rol' => $rol,
            'message' => 'Rol creado exitosamente'
        ]);
    }

    /**
     * Crear nuevo permiso (desde modal)
     */
    public function storePermiso(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:permisos,nombre',
        ]);

        $permiso = Permiso::create($validated);
        
        return response()->json([
            'success' => true,
            'permiso' => $permiso,
            'message' => 'Permiso creado exitosamente'
        ]);
    }

    /**
     * Asignar permiso a rol
     */
    public function storeRolPermiso(Request $request)
    {
        $validated = $request->validate([
            'id_rol' => 'required|exists:roles,id_rol',
            'id_permiso' => 'required|exists:permisos,id_permiso',
            'estado' => 'required|in:activo,inactivo',
            'descripcion' => 'nullable|string',
        ]);

        $rolPermiso = RolPermiso::firstOrCreate(
            [
                'id_rol' => $validated['id_rol'],
                'id_permiso' => $validated['id_permiso']
            ],
            [
                'estado' => $validated['estado'],
                'descripcion' => $validated['descripcion'] ?? null,
            ]
        );
        
        return response()->json([
            'success' => true,
            'rol_permiso' => $rolPermiso->load(['rol', 'permiso']),
            'message' => 'Permiso asignado al rol exitosamente'
        ]);
    }

    public function edit($id)
    {
        $usuario = Usuario::with(['empleado', 'cliente'])->findOrFail($id);
        
        $empleados = Empleado::all();
        $clientes = Cliente::all();
        
        // Obtener permisos actuales
        $permisosActuales = $usuario->rolPermisoUsuarios()
            ->where('estado', 'activo')
            ->pluck('id_rol_permiso')
            ->toArray();
        
        // Todos los rol_permiso disponibles
        $todosRolPermisos = RolPermiso::with(['rol', 'permiso'])
            ->where('estado', 'activo')
            ->get();
        
        return response()->json([
            'usuario' => $usuario,
            'empleados' => $empleados,
            'clientes' => $clientes,
            'permisos_actuales' => $permisosActuales,
            'todos_rol_permisos' => $todosRolPermisos
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'correo' => 'required|email|unique:usuarios,correo,' . $id . ',id_usuario',
            'contraseña' => 'nullable|min:8',
            'tipo_usuario' => 'required|in:cliente,empleado',
            'estado' => 'required|in:activo,inactivo',
            'id_empleado' => 'nullable|exists:empleados,id_empleado',
            'id_cliente' => 'nullable|exists:clientes,id_cliente',
            'rol_permiso_ids' => 'nullable|array',
            'rol_permiso_ids.*' => 'exists:rol_permiso,id_rol_permiso'
        ]);

        DB::beginTransaction();
        try {
            $usuario = Usuario::findOrFail($id);
            
            $updateData = [
                'correo' => $validated['correo'],
                'tipo_usuario' => $validated['tipo_usuario'],
                'estado' => $validated['estado'],
                'id_empleado' => $validated['tipo_usuario'] == 'empleado' ? $validated['id_empleado'] : null,
                'id_cliente' => $validated['tipo_usuario'] == 'cliente' ? $validated['id_cliente'] : null,
            ];
            
            // Solo actualizar contraseña si se proporcionó
            if (!empty($validated['contraseña'])) {
                $updateData['contraseña'] = Hash::make($validated['contraseña']);
            }
            
            $usuario->update($updateData);
            
            // Actualizar permisos
            // Desactivar permisos actuales
            $usuario->rolPermisoUsuarios()->update(['estado' => 'inactivo']);
            
            // Asignar nuevos permisos
            if (!empty($validated['rol_permiso_ids'])) {
                foreach ($validated['rol_permiso_ids'] as $rolPermisoId) {
                    $existente = RolPermisoUsuario::where([
                        'id_rol_permiso' => $rolPermisoId,
                        'id_usuario' => $id
                    ])->first();
                    
                    if ($existente) {
                        $existente->update(['estado' => 'activo']);
                    } else {
                        RolPermisoUsuario::create([
                            'id_rol_permiso' => $rolPermisoId,
                            'id_usuario' => $id,
                            'estado' => 'activo',
                            'fecha_asignacion' => now(),
                        ]);
                    }
                }
            }

            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario actualizado exitosamente'
                ]);
            }
            
            return redirect()->route('usuarios.create-access')
                ->with('success', 'Usuario actualizado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar usuario: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error al actualizar usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function storeCliente(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $cliente = Cliente::create($validated);
        
        return response()->json([
            'success' => true,
            'cliente' => $cliente,
            'message' => 'Cliente creado exitosamente'
        ]);
    }

}   