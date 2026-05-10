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
use Illuminate\Support\Facades\Auth;

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
        
        // ✅ AGREGAR ESTAS LÍNEAS - Cargar TODOS los empleados y clientes
        $empleados = \App\Models\Empleado::all();
        $clientes = \App\Models\Cliente::all();
        
        $roles = \App\Models\Rol::all();
        $permisos = \App\Models\Permiso::all();
        $rolPermisos = \App\Models\RolPermiso::with(['rol', 'permiso'])->where('estado', 'activo')->get();
            
        return view('usuarios.index', compact(
            'usuarios', 
            'empleados',
            'clientes',
            'roles', 
            'permisos', 
            'rolPermisos'
        ));
    }

    /**
     * Módulo de acceso - Vista principal
     */
    public function createAccess()
    {       
        $rolPermisos = RolPermiso::with(['rol', 'permiso'])
            ->where('estado', 'activo')
            ->get();
            
        $empleados = \App\Models\Empleado::all();  // TODOS los empleados
        $clientes = \App\Models\Cliente::all();    // TODOS los clientes
        $roles = Rol::all();
        $permisos = Permiso::all();
        
        // Para la tabla de usuarios con sus permisos
        $usuarios = Usuario::with(['empleado', 'cliente', 'rolPermisoUsuarios.rolPermiso.rol', 'rolPermisoUsuarios.rolPermiso.permiso'])
            ->orderBy('created_at', 'desc')
            ->get();
            
            return view('usuarios.acceso', compact(
                'empleados', 
                'clientes', 
                'rolPermisos', 
                'roles', 
                'permisos', 
                'usuarios'
            ));
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

        if ($request->tipo_usuario === 'empleado' && $request->id_empleado) {
            $existeUsuario = Usuario::where('id_empleado', $request->id_empleado)->exists();
            
            if ($existeUsuario) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Este empleado ya tiene un usuario asignado.'
                    ], 422);
                }
                return back()->with('error', 'Este empleado ya tiene un usuario asignado.')->withInput();
            }
        }
        
        // ✅ Validar que el cliente no tenga ya un usuario
        if ($request->tipo_usuario === 'cliente' && $request->id_cliente) {
            $existeUsuario = Usuario::where('id_cliente', $request->id_cliente)->exists();
            
            if ($existeUsuario) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Este cliente ya tiene un usuario asignado.'
                    ], 422);
                }
                return back()->with('error', 'Este cliente ya tiene un usuario asignado.')->withInput();
            }
        }

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
            
            // Verificar si es petición AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario creado exitosamente con sus permisos.',
                    'usuario' => $usuario
                ]);
            }
            
            return redirect()->route('usuarios.create-access')
                ->with('success', 'Usuario creado exitosamente con sus permisos.');
                    
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Verificar si es petición AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear usuario: ' . $e->getMessage()
                ], 500);
            }
            
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

        // ✅ VALIDACIÓN ADICIONAL: Un empleado solo puede tener un usuario
        if ($request->tipo_usuario === 'empleado' && $request->id_empleado) {
            $existeOtroUsuario = Usuario::where('id_empleado', $request->id_empleado)
                ->where('id_usuario', '!=', $id)
                ->exists();
            
            if ($existeOtroUsuario) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Este empleado ya tiene un usuario asignado.'
                    ], 422);
                }
                return back()->with('error', 'Este empleado ya tiene un usuario asignado.')->withInput();
            }
        }
        
        // ✅ Validación para cliente
        if ($request->tipo_usuario === 'cliente' && $request->id_cliente) {
            $existeOtroUsuario = Usuario::where('id_cliente', $request->id_cliente)
                ->where('id_usuario', '!=', $id)
                ->exists();
            
            if ($existeOtroUsuario) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Este cliente ya tiene un usuario asignado.'
                    ], 422);
                }
                return back()->with('error', 'Este cliente ya tiene un usuario asignado.')->withInput();
            }
        }

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
            
            if (!empty($validated['contraseña'])) {
                $updateData['contraseña'] = Hash::make($validated['contraseña']);
            }
            
            $usuario->update($updateData);
            
            // Manejar permisos...
            
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
            
            return back()->with('error', 'Error al actualizar usuario: ' . $e->getMessage())->withInput();
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

    public function registroClienteRapido(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo',
            'contraseña' => 'required|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            // Crear el cliente
            $cliente = Cliente::create([
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'] ?? '',
                'telefono' => $validated['telefono'] ?? null,
            ]);

            // Crear el usuario asociado al cliente
            $usuario = Usuario::create([
                'correo' => $validated['correo'],
                'contraseña' => Hash::make($validated['contraseña']),
                'tipo_usuario' => 'cliente',
                'estado' => 'activo',
                'id_cliente' => $cliente->id_cliente,
                'id_empleado' => null,
            ]);

            // Asignar permisos básicos para clientes (opcional)
            // Puedes asignar un rol por defecto a los clientes si lo deseas
            $rolCliente = Rol::where('nombre', 'cliente')->first();
            if ($rolCliente) {
                $permisosBasicos = Permiso::whereIn('nombre', ['productos_ver', 'ventas_crear'])->pluck('id_permiso');
                foreach ($permisosBasicos as $permisoId) {
                    $rolPermiso = RolPermiso::firstOrCreate([
                        'id_rol' => $rolCliente->id_rol,
                        'id_permiso' => $permisoId
                    ]);
                    
                    RolPermisoUsuario::create([
                        'id_rol_permiso' => $rolPermiso->id_rol_permiso,
                        'id_usuario' => $usuario->id_usuario,
                        'estado' => 'activo',
                        'fecha_asignacion' => now(),
                    ]);
                }
            }

            DB::commit();

            // Autenticar al usuario automáticamente después del registro
            Auth::login($usuario);

            return redirect()->route('landing')->with('success', '¡Registro exitoso! Bienvenido a Panadería Otto.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar: ' . $e->getMessage())->withInput();
        }
    }

   /**
 * Vista unificada de personas (empleados y clientes)
 */
    public function personas(Request $request)
    {
        $filtro = $request->get('filtro', 'todos');
        $buscar = $request->get('buscar', '');
        
        // Obtener empleados con su usuario
        $empleados = Empleado::with(['usuarios' => function($query) {
            $query->where('tipo_usuario', 'empleado');
        }])
        ->when($buscar, function($query) use ($buscar) {
            return $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                ->orWhere('apellido', 'like', "%{$buscar}%")
                ->orWhere('telefono', 'like', "%{$buscar}%");
            });
        })
        ->get()
        ->map(function($empleado) {
            $usuario = $empleado->usuarios->first();
            return [
                'id' => $empleado->id_empleado,
                'tipo' => 'Empleado',
                'nombre' => $empleado->nombre . ' ' . $empleado->apellido,
                'telefono' => $empleado->telefono ?? '-',
                'direccion' => $empleado->direccion ?? '-',
                'info_extra' => $empleado->sueldo ? '$' . number_format($empleado->sueldo, 2) : '-',
                'tiene_usuario' => !is_null($usuario),
                'usuario_correo' => $usuario->correo ?? null,
                'usuario_estado' => $usuario->estado ?? null,
                'usuario_id' => $usuario->id_usuario ?? null,
                'color_tipo' => 'primary',
                'icono_tipo' => 'fa-user-tie',
            ];
        });
        
        // Obtener clientes con su usuario
        $clientes = Cliente::with(['usuarios' => function($query) {
            $query->where('tipo_usuario', 'cliente');
        }])
        ->when($buscar, function($query) use ($buscar) {
            return $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                ->orWhere('apellido', 'like', "%{$buscar}%")
                ->orWhere('telefono', 'like', "%{$buscar}%");
            });
        })
        ->get()
        ->map(function($cliente) {
            $usuario = $cliente->usuarios->first();
            return [
                'id' => $cliente->id_cliente,
                'tipo' => 'Cliente',
                'nombre' => $cliente->nombre . ' ' . ($cliente->apellido ?? ''),
                'telefono' => $cliente->telefono ?? '-',
                'direccion' => '-',
                'info_extra' => '-',
                'tiene_usuario' => !is_null($usuario),
                'usuario_correo' => $usuario->correo ?? null,
                'usuario_estado' => $usuario->estado ?? null,
                'usuario_id' => $usuario->id_usuario ?? null,
                'color_tipo' => 'info',
                'icono_tipo' => 'fa-user',
            ];
        });
        
        // Unir ambas colecciones
        $personas = $empleados->concat($clientes);
        
        // Aplicar filtro
        if ($filtro === 'empleados') {
            $personas = $personas->where('tipo', 'Empleado');
        } elseif ($filtro === 'clientes') {
            $personas = $personas->where('tipo', 'Cliente');
        } elseif ($filtro === 'sin_usuario') {
            $personas = $personas->where('tiene_usuario', false);
        } elseif ($filtro === 'con_usuario') {
            $personas = $personas->where('tiene_usuario', true);
        }
        
        // Ordenar por nombre
        $personas = $personas->sortBy('nombre')->values();
        
        $total = $personas->count();
        $sinUsuario = $personas->where('tiene_usuario', false)->count();
        $empleadosCount = $personas->where('tipo', 'Empleado')->count();
        $clientesCount = $personas->where('tipo', 'Cliente')->count();
        
        return view('usuarios.personas', compact(
            'personas', 'filtro', 'buscar', 'total', 'sinUsuario', 'empleadosCount', 'clientesCount'
        ));
    }   
}