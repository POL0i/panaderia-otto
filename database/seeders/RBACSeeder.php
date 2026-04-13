<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\Permiso;
use App\Models\Rol;
use App\Models\RolPermiso;
use App\Models\RolPermisoUsuario;
use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RBACSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ============================================
        // 1. CREAR TODOS LOS PERMISOS DEL SISTEMA
        // ============================================
        $permisos = $this->crearPermisos();
        echo "✓ " . count($permisos) . " permisos creados\n";

        // ============================================
        // 2. DEFINIR ROLES
        // ============================================
        $rolesCreados = $this->crearRoles();
        echo "✓ " . count($rolesCreados) . " roles creados\n";

        // ============================================
        // 3. ASIGNAR PERMISOS A ROLES
        // ============================================
        $this->asignarPermisosARoles($rolesCreados, $permisos);
        echo "✓ Permisos asignados a roles\n";

        // ============================================
        // 4. CREAR USUARIOS DE PRUEBA
        // ============================================
        $usuarios = $this->crearUsuarios();
        echo "✓ " . count($usuarios) . " usuarios de prueba creados\n";

        // ============================================
        // 5. ASIGNAR USUARIOS A ROLES
        // ============================================
        $this->asignarUsuariosARoles($usuarios, $rolesCreados);
        echo "✓ Usuarios asignados a roles\n";

        // ============================================
        // 6. MOSTRAR RESUMEN
        // ============================================
        $this->mostrarResumen($rolesCreados, $permisos);
        $this->mostrarCredenciales();
    }

    /**
     * Crear todos los permisos del sistema
     */
    private function crearPermisos(): array
    {
        $listaPermisos = [
            // Permisos básicos (acciones)
            'ver',
            'crear',
            'editar',
            'eliminar',
            
            // ============================================
            // MÓDULO: GESTIÓN COMERCIAL
            // ============================================
            'gestion_comercial_ver',
            'gestion_comercial_crear',
            'gestion_comercial_editar',
            'gestion_comercial_eliminar',
            
            // Notas de Venta
            'notas_venta_ver',
            'notas_venta_crear',
            'notas_venta_editar',
            'notas_venta_eliminar',
            'notas_venta_anular',
            
            // Detalles de Venta
            'detalles_venta_ver',
            'detalles_venta_crear',
            'detalles_venta_editar',
            'detalles_venta_eliminar',
            
            // Notas de Compra
            'notas_compra_ver',
            'notas_compra_crear',
            'notas_compra_editar',
            'notas_compra_eliminar',
            'notas_compra_aprobar',
            
            // Detalles de Compra
            'detalles_compra_ver',
            'detalles_compra_crear',
            'detalles_compra_editar',
            'detalles_compra_eliminar',
            
            // Proveedores
            'proveedores_ver',
            'proveedores_crear',
            'proveedores_editar',
            'proveedores_eliminar',
            
            // Clientes
            'clientes_ver',
            'clientes_crear',
            'clientes_editar',
            'clientes_eliminar',
            
            // ============================================
            // MÓDULO: ALMACÉN
            // ============================================
            'almacen_ver',
            'almacen_crear',
            'almacen_editar',
            'almacen_eliminar',
            
            // Almacenes
            'almacenes_ver',
            'almacenes_crear',
            'almacenes_editar',
            'almacenes_eliminar',
            
            // Productos
            'productos_ver',
            'productos_crear',
            'productos_editar',
            'productos_eliminar',
            
            // Items
            'items_ver',
            'items_crear',
            'items_editar',
            'items_eliminar',
            
            // Insumos
            'insumos_ver',
            'insumos_crear',
            'insumos_editar',
            'insumos_eliminar',
            
            // Stock/Inventario
            'almacen_items_ver',
            'almacen_items_crear',
            'almacen_items_editar',
            'almacen_items_eliminar',
            
            // ============================================
            // MÓDULO: INVENTARIO
            // ============================================
            'inventario_ver',
            'inventario_crear',
            'inventario_editar',
            'inventario_eliminar',
            
            // Movimientos
            'movimientos_ver',
            'movimientos_crear',
            'movimientos_editar',
            'movimientos_eliminar',
            
            // Traspasos
            'traspasos_ver',
            'traspasos_crear',
            'traspasos_editar',
            'traspasos_eliminar',
            'traspasos_completar',
            'traspasos_cancelar',
            'traspasos_autorizar',
            
            // Lotes
            'lotes_ver',
            'lotes_crear',
            'lotes_editar',
            'lotes_eliminar',
            'lotes_consumir',
            
            // Configuración de Inventario
            'configuracion_inventario_ver',
            'configuracion_inventario_editar',
            
            // ============================================
            // MÓDULO: PRODUCCIÓN
            // ============================================
            'produccion_ver',
            'produccion_crear',
            'produccion_editar',
            'produccion_eliminar',
            
            // Recetas
            'recetas_ver',
            'recetas_crear',
            'recetas_editar',
            'recetas_eliminar',
            
            // Detalles de Receta
            'detalles_receta_ver',
            'detalles_receta_crear',
            'detalles_receta_editar',
            'detalles_receta_eliminar',
            
            // Producciones
            'producciones_ver',
            'producciones_crear',
            'producciones_editar',
            'producciones_eliminar',
            'producciones_completar',
            'producciones_cancelar',
            
            // Items de Producción
            'produccion_items_ver',
            'produccion_items_crear',
            'produccion_items_editar',
            'produccion_items_eliminar',
            
            // ============================================
            // MÓDULO: USUARIOS Y SEGURIDAD
            // ============================================
            'usuarios_ver',
            'usuarios_crear',
            'usuarios_editar',
            'usuarios_eliminar',
            
            'empleados_ver',
            'empleados_crear',
            'empleados_editar',
            'empleados_eliminar',
            
            'roles_ver',
            'roles_crear',
            'roles_editar',
            'roles_eliminar',
            
            'permisos_ver',
            'permisos_crear',
            'permisos_editar',
            'permisos_eliminar',
            
            'rol_permisos_ver',
            'rol_permisos_crear',
            'rol_permisos_editar',
            'rol_permisos_eliminar',
            
            'rol_permiso_usuarios_ver',
            'rol_permiso_usuarios_crear',
            'rol_permiso_usuarios_editar',
            'rol_permiso_usuarios_eliminar',
            
            // ============================================
            // MÓDULO: REPORTES
            // ============================================
            'reportes_ver',
            'reportes_ventas_ver',
            'reportes_compras_ver',
            'reportes_inventario_ver',
            'reportes_produccion_ver',
            'reportes_financieros_ver',
            
            // ============================================
            // PERMISOS ESPECIALES
            // ============================================
            'acceso_total',
            'configuracion_sistema',
            'backup_crear',
            'backup_restaurar',
        ];

        $permisos = [];
        foreach ($listaPermisos as $nombrePermiso) {
            $permisos[$nombrePermiso] = Permiso::firstOrCreate(
                ['nombre' => $nombrePermiso],
                ['nombre' => $nombrePermiso]
            );
        }

        return $permisos;
    }

    /**
     * Crear roles del sistema
     */
    private function crearRoles(): array
    {
        $roles = [
            'Administrador' => 'admin',
            'Gerente' => 'gerente',
            'Encargado Venta' => 'encargado_venta',
            'Encargado Compra' => 'encargado_compra',
            'Encargado Producción' => 'encargado_produccion',
            'Encargado Inventario' => 'encargado_inventario',
            'Empleado' => 'empleado',
            'Cliente' => 'cliente',
        ];

        $rolesCreados = [];
        foreach ($roles as $nombre => $codigo) {
            $rolesCreados[$codigo] = Rol::firstOrCreate(
                ['nombre' => $nombre],
                ['nombre' => $nombre]
            );
        }

        return $rolesCreados;
    }

    /**
     * Asignar permisos a roles según su función
     */
    private function asignarPermisosARoles(array $roles, array $permisos): void
    {
        // ADMINISTRADOR - TODOS los permisos
        foreach ($permisos as $nombre => $permiso) {
            $this->crearRolPermiso($roles['admin'], $permiso, "Administrador - $nombre");
        }

        // GERENTE - Acceso a reportes y supervisión
        $permisosGerente = [
            'ver', 'crear', 'editar',
            'gestion_comercial_ver', 'almacen_ver', 'inventario_ver', 'produccion_ver',
            'reportes_ver', 'reportes_ventas_ver', 'reportes_compras_ver',
            'reportes_inventario_ver', 'reportes_produccion_ver', 'reportes_financieros_ver',
            'clientes_ver', 'empleados_ver', 'usuarios_ver',
        ];
        $this->asignarPermisosEspecificos($roles['gerente'], $permisos, $permisosGerente, 'Gerente');

        // ENCARGADO VENTA
        $permisosVenta = [
            'ver', 'crear', 'editar',
            'gestion_comercial_ver', 'gestion_comercial_crear', 'gestion_comercial_editar',
            'notas_venta_ver', 'notas_venta_crear', 'notas_venta_editar', 'notas_venta_anular',
            'detalles_venta_ver', 'detalles_venta_crear', 'detalles_venta_editar',
            'clientes_ver', 'clientes_crear', 'clientes_editar',
            'proveedores_ver', 'proveedores_crear', 'proveedores_editar',
        ];
        $this->asignarPermisosEspecificos($roles['encargado_venta'], $permisos, $permisosVenta, 'Encargado Venta');

        // ENCARGADO COMPRA
        $permisosCompra = [
            'ver', 'crear', 'editar',
            'gestion_comercial_ver', 'gestion_comercial_crear', 'gestion_comercial_editar',
            'notas_compra_ver', 'notas_compra_crear', 'notas_compra_editar', 'notas_compra_aprobar',
            'detalles_compra_ver', 'detalles_compra_crear', 'detalles_compra_editar',
            'proveedores_ver', 'proveedores_crear', 'proveedores_editar',
        ];
        $this->asignarPermisosEspecificos($roles['encargado_compra'], $permisos, $permisosCompra, 'Encargado Compra');

        // ENCARGADO PRODUCCIÓN
        $permisosProduccion = [
            'ver', 'crear', 'editar',
            'produccion_ver', 'produccion_crear', 'produccion_editar',
            'recetas_ver', 'recetas_crear', 'recetas_editar',
            'detalles_receta_ver', 'detalles_receta_crear', 'detalles_receta_editar',
            'producciones_ver', 'producciones_crear', 'producciones_editar', 'producciones_completar',
            'produccion_items_ver', 'produccion_items_crear', 'produccion_items_editar',
        ];
        $this->asignarPermisosEspecificos($roles['encargado_produccion'], $permisos, $permisosProduccion, 'Encargado Producción');

        // ENCARGADO INVENTARIO
        $permisosInventario = [
            'ver', 'crear', 'editar',
            'inventario_ver', 'inventario_crear', 'inventario_editar',
            'almacen_ver', 'almacen_crear', 'almacen_editar',
            'almacenes_ver', 'almacenes_crear', 'almacenes_editar',
            'movimientos_ver', 'movimientos_crear', 'movimientos_editar',
            'traspasos_ver', 'traspasos_crear', 'traspasos_editar', 'traspasos_completar', 'traspasos_autorizar',
            'lotes_ver', 'lotes_crear', 'lotes_editar', 'lotes_consumir',
            'configuracion_inventario_ver', 'configuracion_inventario_editar',
            'productos_ver', 'insumos_ver', 'almacen_items_ver', 'almacen_items_editar',
        ];
        $this->asignarPermisosEspecificos($roles['encargado_inventario'], $permisos, $permisosInventario, 'Encargado Inventario');

        // EMPLEADO - Solo lectura
        $permisosEmpleado = [
            'ver',
            'gestion_comercial_ver', 'almacen_ver', 'inventario_ver', 'produccion_ver',
            'clientes_ver', 'productos_ver', 'recetas_ver',
        ];
        $this->asignarPermisosEspecificos($roles['empleado'], $permisos, $permisosEmpleado, 'Empleado');

        // CLIENTE - Solo ver sus propias cosas
        $permisosCliente = ['ver', 'clientes_ver'];
        $this->asignarPermisosEspecificos($roles['cliente'], $permisos, $permisosCliente, 'Cliente');
    }

    /**
     * Asignar permisos específicos a un rol
     */
    private function asignarPermisosEspecificos($rol, $todosPermisos, $nombresPermisos, $rolNombre): void
    {
        foreach ($nombresPermisos as $nombre) {
            if (isset($todosPermisos[$nombre])) {
                $this->crearRolPermiso($rol, $todosPermisos[$nombre], "$rolNombre - $nombre");
            }
        }
    }

    /**
     * Crear relación rol-permiso
     */
    private function crearRolPermiso($rol, $permiso, $descripcion = null): void
    {
        RolPermiso::firstOrCreate(
            ['id_rol' => $rol->id_rol, 'id_permiso' => $permiso->id_permiso],
            [
                'estado' => 'activo',
                'descripcion' => $descripcion,
            ]
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
            [
                'correo' => 'venta@panaderia.com',
                'contraseña' => Hash::make('venta123'),
                'estado' => 'activo',
                'tipo_usuario' => 'empleado',
            ]
        );

        $usuarios['compra'] = Usuario::firstOrCreate(
            ['correo' => 'compra@panaderia.com'],
            [
                'correo' => 'compra@panaderia.com',
                'contraseña' => Hash::make('compra123'),
                'estado' => 'activo',
                'tipo_usuario' => 'empleado',
            ]
        );

        $usuarios['produccion'] = Usuario::firstOrCreate(
            ['correo' => 'produccion@panaderia.com'],
            [
                'correo' => 'produccion@panaderia.com',
                'contraseña' => Hash::make('produccion123'),
                'estado' => 'activo',
                'tipo_usuario' => 'empleado',
            ]
        );

        $usuarios['inventario'] = Usuario::firstOrCreate(
            ['correo' => 'inventario@panaderia.com'],
            [
                'correo' => 'inventario@panaderia.com',
                'contraseña' => Hash::make('inventario123'),
                'estado' => 'activo',
                'tipo_usuario' => 'empleado',
            ]
        );

        $usuarios['empleado'] = Usuario::firstOrCreate(
            ['correo' => 'empleado@panaderia.com'],
            [
                'correo' => 'empleado@panaderia.com',
                'contraseña' => Hash::make('empleado123'),
                'estado' => 'activo',
                'tipo_usuario' => 'empleado',
            ]
        );

        $usuarios['cliente'] = Usuario::firstOrCreate(
            ['correo' => 'cliente@panaderia.com'],
            [
                'correo' => 'cliente@panaderia.com',
                'contraseña' => Hash::make('cliente123'),
                'estado' => 'activo',
                'tipo_usuario' => 'cliente',
            ]
        );

        return $usuarios;
    }

    /**
     * Asignar usuarios a roles
     */
    private function asignarUsuariosARoles(array $usuarios, array $roles): void
    {
        $this->asignarRolAUsuario($usuarios['admin'], $roles['admin']);
        $this->asignarRolAUsuario($usuarios['venta'], $roles['encargado_venta']);
        $this->asignarRolAUsuario($usuarios['compra'], $roles['encargado_compra']);
        $this->asignarRolAUsuario($usuarios['produccion'], $roles['encargado_produccion']);
        $this->asignarRolAUsuario($usuarios['inventario'], $roles['encargado_inventario']);
        $this->asignarRolAUsuario($usuarios['empleado'], $roles['empleado']);
        $this->asignarRolAUsuario($usuarios['cliente'], $roles['cliente']);
    }

    /**
     * Asignar un rol completo a un usuario
     */
    private function asignarRolAUsuario($usuario, $rol): void
    {
        $rolesPermisos = RolPermiso::where('id_rol', $rol->id_rol)
            ->where('estado', 'activo')
            ->get();

        foreach ($rolesPermisos as $rolPermiso) {
            RolPermisoUsuario::firstOrCreate(
                [
                    'id_rol_permiso' => $rolPermiso->id_rol_permiso,
                    'id_usuario' => $usuario->id_usuario,
                ],
                [
                    'estado' => 'activo',
                    'fecha_asignacion' => now(),
                ]
            );
        }
    }

    /**
     * Mostrar resumen
     */
    private function mostrarResumen(array $roles, array $permisos): void
    {
        echo "\n=== RESUMEN DEL SISTEMA RBAC ===\n";
        echo "Total de roles: " . count($roles) . "\n";
        echo "Total de permisos: " . count($permisos) . "\n";
        echo "Total de usuarios: 7\n";
    }

    /**
     * Mostrar credenciales
     */
    private function mostrarCredenciales(): void
    {
        echo "\n--- CREDENCIALES DE PRUEBA ---\n";
        echo "Admin:              admin@panaderia.com / admin123\n";
        echo "Encargado Venta:    venta@panaderia.com / venta123\n";
        echo "Encargado Compra:   compra@panaderia.com / compra123\n";
        echo "Encargado Producción: produccion@panaderia.com / produccion123\n";
        echo "Encargado Inventario: inventario@panaderia.com / inventario123\n";
        echo "Empleado:           empleado@panaderia.com / empleado123\n";
        echo "Cliente:            cliente@panaderia.com / cliente123\n";
    }
}