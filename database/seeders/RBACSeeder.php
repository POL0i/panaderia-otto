<?php

namespace Database\Seeders;

use App\Models\Permiso;
use App\Models\Rol;
use App\Models\RolPermiso;
use App\Models\RolPermisoUsuario;
use App\Models\Usuario;
use App\Models\Empleado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RBACSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CREAR EMPLEADOS
        $empleados = $this->crearEmpleados();
        echo "✓ " . count($empleados) . " empleados creados\n";

        // 2. CREAR PERMISOS
        $permisos = $this->crearPermisos();
        echo "✓ " . count($permisos) . " permisos creados\n";

        // 3. CREAR ROLES
        $roles = $this->crearRoles();
        echo "✓ " . count($roles) . " roles creados\n";

        // 4. ASIGNAR PERMISOS A ROLES
        $this->asignarPermisosARoles($roles, $permisos);
        echo "✓ Permisos asignados a roles\n";

        // 5. CREAR USUARIOS Y ASIGNARLES EMPLEADO
        $usuarios = $this->crearUsuarios($empleados);
        echo "✓ " . count($usuarios) . " usuarios creados\n";

        // 6. ASIGNAR ROLES A USUARIOS
        $this->asignarUsuariosARoles($usuarios, $roles);
        echo "✓ Usuarios asignados a roles\n";

        // 7. CREAR CLIENTES Y SUS USUARIOS
        $clientesInfo = $this->crearClientesYUsuarios();
        echo "✓ " . count($clientesInfo['clientes']) . " clientes creados\n";
        echo "✓ " . count($clientesInfo['usuarios']) . " usuarios de clientes creados\n";

        // 8. MOSTRAR CREDENCIALES (solo una vez, con los datos de clientes)
        $this->mostrarCredenciales($usuarios, $clientesInfo['usuarios']);
    }

    /**
     * Crear empleados para todos los usuarios
     */
    private function crearEmpleados(): array
    {
        $datosEmpleados = [
            'admin' => [
                'nombre' => 'Carlos',
                'apellido' => 'Mendoza',
                'telefono' => '70000000',
                'direccion' => 'Av. Principal #100',
                'sueldo' => 5000,
                'fecha_nac' => '1990-05-15',
                'edad' => 36,
            ],
            'venta' => [
                'nombre' => 'Lizeth',
                'apellido' => 'García',
                'telefono' => '70000002',
                'direccion' => 'Calle 2',
                'sueldo' => 3200,
                'fecha_nac' => '1995-08-22',
                'edad' => 30,
            ],
            'compra' => [
                'nombre' => 'Roberto',
                'apellido' => 'Flores',
                'telefono' => '70000003',
                'direccion' => 'Calle 3',
                'sueldo' => 3300,
                'fecha_nac' => '1992-03-10',
                'edad' => 34,
            ],
            'produccion' => [
                'nombre' => 'Dennis',
                'apellido' => 'Rodríguez',
                'telefono' => '70000001',
                'direccion' => 'Calle 1',
                'sueldo' => 3500,
                'fecha_nac' => '1993-11-28',
                'edad' => 32,
            ],
            'inventario' => [
                'nombre' => 'Mario',
                'apellido' => 'López',
                'telefono' => '70000004',
                'direccion' => 'Calle 4',
                'sueldo' => 3400,
                'fecha_nac' => '1991-07-05',
                'edad' => 34,
            ],
            'empleado' => [
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'telefono' => '70000005',
                'direccion' => 'Calle 5',
                'sueldo' => 2800,
                'fecha_nac' => '1998-01-19',
                'edad' => 28,
            ],
        ];

        $empleados = [];
        foreach ($datosEmpleados as $key => $datos) {
            $empleados[$key] = Empleado::firstOrCreate(
                ['nombre' => $datos['nombre'], 'apellido' => $datos['apellido']],
                $datos
            );
        }

        return $empleados;
    }

    /**
     * Crear permisos de acceso a módulos
     */
    private function crearPermisos(): array
    {
        $listaPermisos = [
            // Módulos principales
            'gestion_comercial_ver',
            'almacen_ver',
            'inventario_ver',
            'produccion_ver',
            'reportes_ver',

            // Paneles destacados
            'panel_almacen_ver',
            'panel_produccion_ver',

            // 🔒 NUEVO: Permiso para gestionar producciones (aprobar/rechazar/cancelar)
            'gestionar_produccion',

            // Sub-módulos de Gestión Comercial
            'notas_venta_ver',
            'notas_compra_ver',
            'proveedores_ver',
            'clientes_ver',

            // Permisos de acción
            'ventas_completar',

            // Sub-módulos de Almacén
            'almacenes_ver',
            'productos_ver',
            'items_ver',
            'insumos_ver',

            // Sub-módulos de Inventario
            'movimientos_ver',
            'traspasos_ver',
            'lotes_ver',

            // Sub-módulos de Producción
            'recetas_ver',
            'producciones_ver',

            // Módulo de Acceso (solo admin)
            'modulo_acceso_ver',
        ];

        $permisos = [];
        foreach ($listaPermisos as $nombre) {
            $permisos[$nombre] = Permiso::firstOrCreate(['nombre' => $nombre]);
        }

        return $permisos;
    }

    /**
     * Crear roles del sistema
     */
    private function crearRoles(): array
    {
        $rolesNombres = [
            'Administrador',
            'Gerente',
            'Encargado Venta',
            'Encargado Compra',
            'Encargado Producción',
            'Encargado Inventario',
            'Empleado',
        ];

        $roles = [];
        foreach ($rolesNombres as $nombre) {
            $roles[$nombre] = Rol::firstOrCreate(['nombre' => $nombre]);
        }

        return $roles;
    }

    /**
     * Asignar permisos a roles
     */
    private function asignarPermisosARoles(array $roles, array $permisos): void
    {
        // ADMINISTRADOR - TODOS los permisos
        foreach ($permisos as $permiso) {
            $this->crearRolPermiso($roles['Administrador'], $permiso);
        }

        // GERENTE - Acceso a todos los módulos principales + gestionar_produccion
        $permisosGerente = [
            'gestion_comercial_ver', 'almacen_ver', 'inventario_ver',
            'produccion_ver', 'reportes_ver',
            'panel_almacen_ver', 'panel_produccion_ver',
            'gestionar_produccion',
            'clientes_ver', // Gerente puede ver clientes
        ];
        foreach ($permisosGerente as $nombre) {
            if (isset($permisos[$nombre])) {
                $this->crearRolPermiso($roles['Gerente'], $permisos[$nombre]);
            }
        }

        // ENCARGADO VENTA - Gestión comercial y notas de venta (sin acceso a clientes)
        $permisosVenta = ['gestion_comercial_ver', 'notas_venta_ver'];
        foreach ($permisosVenta as $nombre) {
            if (isset($permisos[$nombre])) {
                $this->crearRolPermiso($roles['Encargado Venta'], $permisos[$nombre]);
            }
        }

        // ENCARGADO COMPRA
        $permisosCompra = ['gestion_comercial_ver', 'notas_compra_ver', 'proveedores_ver'];
        foreach ($permisosCompra as $nombre) {
            if (isset($permisos[$nombre])) {
                $this->crearRolPermiso($roles['Encargado Compra'], $permisos[$nombre]);
            }
        }

        // ENCARGADO PRODUCCIÓN
        $permisosProduccion = [
            'produccion_ver', 'recetas_ver', 'producciones_ver', 'panel_produccion_ver'
        ];
        foreach ($permisosProduccion as $nombre) {
            if (isset($permisos[$nombre])) {
                $this->crearRolPermiso($roles['Encargado Producción'], $permisos[$nombre]);
            }
        }

        // ENCARGADO INVENTARIO (con permiso para gestionar producciones)
        $permisosInventario = [
            'inventario_ver', 'almacen_ver', 'movimientos_ver', 'traspasos_ver',
            'lotes_ver', 'insumos_ver', 'panel_almacen_ver',
            'produccion_ver',
            'gestionar_produccion',    // ← NUEVO: Puede aprobar/rechazar/cancelar
        ];
        foreach ($permisosInventario as $nombre) {
            if (isset($permisos[$nombre])) {
                $this->crearRolPermiso($roles['Encargado Inventario'], $permisos[$nombre]);
            }
        }

        // EMPLEADO - Solo lectura de operaciones (sin gestión comercial)
        $permisosEmpleado = ['almacen_ver', 'inventario_ver', 'produccion_ver'];
        foreach ($permisosEmpleado as $nombre) {
            if (isset($permisos[$nombre])) {
                $this->crearRolPermiso($roles['Empleado'], $permisos[$nombre]);
            }
        }
    }

    private function crearRolPermiso($rol, $permiso): void
    {
        RolPermiso::firstOrCreate(
            ['id_rol' => $rol->id_rol, 'id_permiso' => $permiso->id_permiso],
            ['estado' => 'activo']
        );
    }

    /**
     * Crear usuarios de prueba y asignarles empleado
     */
    private function crearUsuarios(array $empleados): array
    {
        $usuarios = [];

        $usuarios['admin'] = Usuario::firstOrCreate(
            ['correo' => 'admin@panaderia.com'],
            [
                'correo' => 'admin@panaderia.com',
                'contraseña' => Hash::make('admin123'),
                'estado' => 'activo',
                'tipo_usuario' => 'empleado',
                'id_empleado' => $empleados['admin']->id_empleado,
            ]
        );

        $usuarios['venta'] = Usuario::firstOrCreate(
            ['correo' => 'venta@panaderia.com'],
            [
                'correo' => 'venta@panaderia.com',
                'contraseña' => Hash::make('venta123'),
                'estado' => 'activo',
                'tipo_usuario' => 'empleado',
                'id_empleado' => $empleados['venta']->id_empleado,
            ]
        );

        $usuarios['compra'] = Usuario::firstOrCreate(
            ['correo' => 'compra@panaderia.com'],
            [
                'correo' => 'compra@panaderia.com',
                'contraseña' => Hash::make('compra123'),
                'estado' => 'activo',
                'tipo_usuario' => 'empleado',
                'id_empleado' => $empleados['compra']->id_empleado,
            ]
        );

        $usuarios['produccion'] = Usuario::firstOrCreate(
            ['correo' => 'produccion@panaderia.com'],
            [
                'correo' => 'produccion@panaderia.com',
                'contraseña' => Hash::make('produccion123'),
                'estado' => 'activo',
                'tipo_usuario' => 'empleado',
                'id_empleado' => $empleados['produccion']->id_empleado,
            ]
        );

        $usuarios['inventario'] = Usuario::firstOrCreate(
            ['correo' => 'inventario@panaderia.com'],
            [
                'correo' => 'inventario@panaderia.com',
                'contraseña' => Hash::make('inventario123'),
                'estado' => 'activo',
                'tipo_usuario' => 'empleado',
                'id_empleado' => $empleados['inventario']->id_empleado,
            ]
        );

        $usuarios['empleado'] = Usuario::firstOrCreate(
            ['correo' => 'empleado@panaderia.com'],
            [
                'correo' => 'empleado@panaderia.com',
                'contraseña' => Hash::make('empleado123'),
                'estado' => 'activo',
                'tipo_usuario' => 'empleado',
                'id_empleado' => $empleados['empleado']->id_empleado,
            ]
        );

        return $usuarios;
    }

    private function asignarUsuariosARoles(array $usuarios, array $roles): void
    {
        $this->asignarRolAUsuario($usuarios['admin'], $roles['Administrador']);
        $this->asignarRolAUsuario($usuarios['venta'], $roles['Encargado Venta']);
        $this->asignarRolAUsuario($usuarios['compra'], $roles['Encargado Compra']);
        $this->asignarRolAUsuario($usuarios['produccion'], $roles['Encargado Producción']);
        $this->asignarRolAUsuario($usuarios['inventario'], $roles['Encargado Inventario']);
        $this->asignarRolAUsuario($usuarios['empleado'], $roles['Empleado']);
    }

    private function asignarRolAUsuario($usuario, $rol): void
    {
        $rolesPermisos = RolPermiso::where('id_rol', $rol->id_rol)
            ->where('estado', 'activo')
            ->get();

        foreach ($rolesPermisos as $rolPermiso) {
            RolPermisoUsuario::firstOrCreate(
                ['id_rol_permiso' => $rolPermiso->id_rol_permiso, 'id_usuario' => $usuario->id_usuario],
                ['estado' => 'activo', 'fecha_asignacion' => now()]
            );
        }
    }

    private function mostrarCredenciales($usuariosEmpleados = null, $usuariosClientes = null): void
    {
        echo "\n--- CREDENCIALES EMPLEADOS ---\n";
        echo "Admin:      admin@panaderia.com       / admin123       (Carlos Mendoza)\n";
        echo "Venta:      venta@panaderia.com       / venta123       (Lizeth García)\n";
        echo "Compra:     compra@panaderia.com      / compra123      (Roberto Flores)\n";
        echo "Producción: produccion@panaderia.com  / produccion123  (Dennis Rodríguez)\n";
        echo "Inventario: inventario@panaderia.com  / inventario123  (Mario López)\n";
        echo "Empleado:   empleado@panaderia.com    / empleado123    (Juan Pérez)\n";

        if ($usuariosClientes && count($usuariosClientes) > 0) {
            echo "\n--- CREDENCIALES CLIENTES ---\n";
            foreach ($usuariosClientes as $usuario) {
                // Buscar el cliente relacionado por el nombre del correo
                $nombreCorreo = explode('@', $usuario->correo)[0];
                $partesNombre = explode('.', $nombreCorreo);
                $nombre = ucfirst($partesNombre[0] ?? '');
                $apellido = ucfirst($partesNombre[1] ?? '');
                $nombreCliente = $nombre . ' ' . $apellido;
                echo "{$nombreCliente}: {$usuario->correo} / cliente123\n";
            }
        }
    }

    private function crearClientesYUsuarios(): array
{
    $datosClientes = [
        [
            'nombre' => 'Ana',
            'apellido' => 'González',
            'telefono' => 70001001,
            'correo' => 'ana.gonzalez@example.com',
            'contraseña' => 'cliente123',
        ],
        [
            'nombre' => 'Luis',
            'apellido' => 'Ramírez',
            'telefono' => 70001002,
            'correo' => 'luis.ramirez@example.com',
            'contraseña' => 'cliente123',
        ],
        [
            'nombre' => 'Martha',
            'apellido' => 'Sánchez',
            'telefono' => 70001003,
            'correo' => 'martha.sanchez@example.com',
            'contraseña' => 'cliente123',
        ],
    ];

    $clientes = [];
    $usuarios = [];

    foreach ($datosClientes as $data) {
        // Crear o actualizar cliente
        $cliente = \App\Models\Cliente::updateOrCreate(
            ['nombre' => $data['nombre'], 'apellido' => $data['apellido']],
            [
                'telefono' => $data['telefono'],
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $clientes[] = $cliente;

        // ✅ Crear usuario para este cliente con id_cliente asignado
        $usuario = \App\Models\Usuario::updateOrCreate(
            ['correo' => $data['correo']],
            [
                'correo' => $data['correo'],
                'contraseña' => \Illuminate\Support\Facades\Hash::make($data['contraseña']),
                'estado' => 'activo',
                'tipo_usuario' => 'cliente',
                'id_empleado' => null,
                'id_cliente' => $cliente->id_cliente,  // ← DESCOMENTAR Y ASIGNAR
            ]
        );
        $usuarios[] = $usuario;
        
        // Log para verificar
        \Log::info('Cliente y usuario creado', [
            'cliente_id' => $cliente->id_cliente,
            'usuario_id' => $usuario->id_usuario,
            'usuario_correo' => $usuario->correo,
            'usuario_id_cliente' => $usuario->id_cliente
        ]);
    }

    return ['clientes' => $clientes, 'usuarios' => $usuarios];
}
}