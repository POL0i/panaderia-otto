<?php
// database/seeders/RBACSeeder.php

namespace Database\Seeders;

use App\Models\Permiso;
use App\Models\Rol;
use App\Models\RolPermiso;
use App\Models\RolPermisoUsuario;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RBACSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // 1. CREAR PERMISOS (solo para control de acceso a módulos)
        // ============================================
        $permisos = $this->crearPermisos();
        echo "✓ " . count($permisos) . " permisos de módulo creados\n";

        // ============================================
        // 2. CREAR ROLES
        // ============================================
        $roles = $this->crearRoles();
        echo "✓ " . count($roles) . " roles creados\n";

        // ============================================
        // 3. ASIGNAR PERMISOS A ROLES
        // ============================================
        $this->asignarPermisosARoles($roles, $permisos);
        echo "✓ Permisos asignados a roles\n";

        // ============================================
        // 4. CREAR USUARIOS DE PRUEBA
        // ============================================
        $usuarios = $this->crearUsuarios();
        echo "✓ " . count($usuarios) . " usuarios creados\n";

        // ============================================
        // 5. ASIGNAR ROLES A USUARIOS
        // ============================================
        $this->asignarUsuariosARoles($usuarios, $roles);
        echo "✓ Usuarios asignados a roles\n";

        $this->mostrarCredenciales();
    }

    /**
     * Crear solo permisos de acceso a módulos (vistas/rutas)
     */
    private function crearPermisos(): array
    {
        $listaPermisos = [
            // Módulos principales (acceso a vistas)
            'gestion_comercial_ver',
            'almacen_ver',
            'inventario_ver',
            'produccion_ver',
            'reportes_ver',
            
            // Paneles destacados
            'panel_almacen_ver',
            'panel_produccion_ver',
            
            // Sub-módulos de Gestión Comercial
            'notas_venta_ver',
            'notas_compra_ver',
            'proveedores_ver',
            'clientes_ver',
            
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

        // GERENTE - Acceso a todos los módulos principales
        $permisosGerente = [
            'gestion_comercial_ver', 'almacen_ver', 'inventario_ver', 
            'produccion_ver', 'reportes_ver',
            'panel_almacen_ver', 'panel_produccion_ver',
        ];
        foreach ($permisosGerente as $nombre) {
            if (isset($permisos[$nombre])) {
                $this->crearRolPermiso($roles['Gerente'], $permisos[$nombre]);
            }
        }

        // ENCARGADO VENTA
        $permisosVenta = ['gestion_comercial_ver', 'notas_venta_ver', 'clientes_ver'];
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
        $permisosProduccion = ['produccion_ver', 'recetas_ver', 'producciones_ver', 'panel_produccion_ver'];
        foreach ($permisosProduccion as $nombre) {
            if (isset($permisos[$nombre])) {
                $this->crearRolPermiso($roles['Encargado Producción'], $permisos[$nombre]);
            }
        }

        // ENCARGADO INVENTARIO
        $permisosInventario = [
            'inventario_ver', 'almacen_ver', 'movimientos_ver', 'traspasos_ver', 
            'lotes_ver', 'insumos_ver', 'panel_almacen_ver'
        ];
        foreach ($permisosInventario as $nombre) {
            if (isset($permisos[$nombre])) {
                $this->crearRolPermiso($roles['Encargado Inventario'], $permisos[$nombre]);
            }
        }

        // EMPLEADO - Solo lectura básica
        $permisosEmpleado = ['gestion_comercial_ver', 'almacen_ver', 'inventario_ver', 'produccion_ver'];
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
     * Crear usuarios de prueba
     */
    private function crearUsuarios(): array
    {
        $usuarios = [];
        
        $usuarios['admin'] = Usuario::firstOrCreate(
            ['correo' => 'admin@panaderia.com'],
            [
                'correo' => 'admin@panaderia.com',
                'contraseña' => Hash::make('admin123'),
                'estado' => 'activo',
                'tipo_usuario' => 'empleado',
            ]
        );

        $usuarios['venta'] = Usuario::firstOrCreate(
            ['correo' => 'venta@panaderia.com'],
            ['correo' => 'venta@panaderia.com', 'contraseña' => Hash::make('venta123'), 'estado' => 'activo', 'tipo_usuario' => 'empleado']
        );

        $usuarios['compra'] = Usuario::firstOrCreate(
            ['correo' => 'compra@panaderia.com'],
            ['correo' => 'compra@panaderia.com', 'contraseña' => Hash::make('compra123'), 'estado' => 'activo', 'tipo_usuario' => 'empleado']
        );

        $usuarios['produccion'] = Usuario::firstOrCreate(
            ['correo' => 'produccion@panaderia.com'],
            ['correo' => 'produccion@panaderia.com', 'contraseña' => Hash::make('produccion123'), 'estado' => 'activo', 'tipo_usuario' => 'empleado']
        );

        $usuarios['inventario'] = Usuario::firstOrCreate(
            ['correo' => 'inventario@panaderia.com'],
            ['correo' => 'inventario@panaderia.com', 'contraseña' => Hash::make('inventario123'), 'estado' => 'activo', 'tipo_usuario' => 'empleado']
        );

        $usuarios['empleado'] = Usuario::firstOrCreate(
            ['correo' => 'empleado@panaderia.com'],
            ['correo' => 'empleado@panaderia.com', 'contraseña' => Hash::make('empleado123'), 'estado' => 'activo', 'tipo_usuario' => 'empleado']
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

    private function mostrarCredenciales(): void
    {
        echo "\n--- CREDENCIALES ---\n";
        echo "Admin: admin@panaderia.com / admin123\n";
        echo "Venta: venta@panaderia.com / venta123\n";
        echo "Compra: compra@panaderia.com / compra123\n";
        echo "Producción: produccion@panaderia.com / produccion123\n";
        echo "Inventario: inventario@panaderia.com / inventario123\n";
        echo "Empleado: empleado@panaderia.com / empleado123\n";
    }
}