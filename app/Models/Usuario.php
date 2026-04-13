<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'correo',
        'contraseña',
        'estado',
        'tipo_usuario',
        'id_cliente',
        'id_empleado'
    ];

    protected $hidden = [
        'contraseña'
    ];

    protected $casts = [
        'id_usuario' => 'integer',
        'id_cliente' => 'integer',
        'id_empleado' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'id_usuario';
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    /**
     * Relación con Cliente
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    /**
     * Relación con Empleado
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    /**
     * Relación con RolPermisoUsuario
     */
    public function rolPermisoUsuarios(): HasMany
    {
        return $this->hasMany(RolPermisoUsuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Obtener todos los permisos del usuario a través de rol_permiso_usuario
     */
    public function permisos(): HasManyThrough
    {
        return $this->hasManyThrough(
            Permiso::class,
            RolPermisoUsuario::class,
            'id_usuario',        // Foreign key on RolPermisoUsuario table
            'id_permiso',        // Foreign key on Permiso table
            'id_usuario',        // Local key on Usuario table
            'id_permiso'         // Local key on RolPermisoUsuario table
        );
    }

    /**
     * Verificar si es cliente
     */
    public function esCliente(): bool
    {
        return $this->tipo_usuario === 'cliente';
    }

    /**
     * Verificar si es empleado
     */
    public function esEmpleado(): bool
    {
        return $this->tipo_usuario === 'empleado';
    }

    /**
     * Verificar si está activo
     */
    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }

    // =====================================================
    // MÉTODOS DE PERMISOS Y ROLES
    // =====================================================

    /**
     * Verificar si el usuario es administrador
     */
    public function esAdmin(): bool
    {
        return DB::table('rol_permiso_usuario as rpu')
            ->join('rol_permiso as rp', 'rpu.id_rol_permiso', '=', 'rp.id_rol_permiso')
            ->join('roles as r', 'rp.id_rol', '=', 'r.id_rol')
            ->where('rpu.id_usuario', $this->id_usuario)
            ->where('r.nombre', 'Administrador')
            ->where('rpu.estado', 'activo')
            ->exists();
    }

    /**
     * Verificar si el usuario tiene un permiso específico
     */
    public function tienePermiso(string $nombrePermiso): bool
    {
        // Los administradores tienen todos los permisos
        if ($this->esAdmin()) {
            return true;
        }

        return DB::table('rol_permiso_usuario as rpu')
            ->join('rol_permiso as rp', 'rpu.id_rol_permiso', '=', 'rp.id_rol_permiso')
            ->join('permisos as p', 'rp.id_permiso', '=', 'p.id_permiso')
            ->where('rpu.id_usuario', $this->id_usuario)
            ->where('rpu.estado', 'activo')
            ->where('rp.estado', 'activo')
            ->where('p.nombre', $nombrePermiso)
            ->exists();
    }

    /**
     * Verificar si el usuario tiene múltiples permisos (AND)
     */
    public function tienePermisos(array $permisos): bool
    {
        foreach ($permisos as $permiso) {
            if (!$this->tienePermiso($permiso)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Verificar si el usuario tiene al menos uno de varios permisos (OR)
     */
    public function tieneAlgunPermiso(array $permisos): bool
    {
        foreach ($permisos as $permiso) {
            if ($this->tienePermiso($permiso)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Obtener todos los permisos del usuario (nombres)
     */
    public function obtenerPermisos(): array
    {
        if ($this->esAdmin()) {
            // Si es admin, retornar todos los permisos del sistema
            return DB::table('permisos')->pluck('nombre')->toArray();
        }

        return DB::table('rol_permiso_usuario as rpu')
            ->join('rol_permiso as rp', 'rpu.id_rol_permiso', '=', 'rp.id_rol_permiso')
            ->join('permisos as p', 'rp.id_permiso', '=', 'p.id_permiso')
            ->where('rpu.id_usuario', $this->id_usuario)
            ->where('rpu.estado', 'activo')
            ->where('rp.estado', 'activo')
            ->pluck('p.nombre')
            ->unique()
            ->toArray();
    }

    /**
     * Obtener todos los roles del usuario
     */
    public function obtenerRoles(): array
    {
        return DB::table('rol_permiso_usuario as rpu')
            ->join('rol_permiso as rp', 'rpu.id_rol_permiso', '=', 'rp.id_rol_permiso')
            ->join('roles as r', 'rp.id_rol', '=', 'r.id_rol')
            ->where('rpu.id_usuario', $this->id_usuario)
            ->where('rpu.estado', 'activo')
            ->pluck('r.nombre')
            ->unique()
            ->toArray();
    }

    /**
     * Obtener todos los permisos con detalles (incluyendo rol y descripción)
     */
    public function obtenerPermisosDetallados(): array
    {
        if ($this->esAdmin()) {
            // Para admin, retornar todos los permisos con su información
            return DB::table('permisos as p')
                ->select('p.id_permiso', 'p.nombre', DB::raw("'Administrador' as rol"), DB::raw("'Acceso total' as descripcion"))
                ->get()
                ->toArray();
        }

        return DB::table('rol_permiso_usuario as rpu')
            ->join('rol_permiso as rp', 'rpu.id_rol_permiso', '=', 'rp.id_rol_permiso')
            ->join('permisos as p', 'rp.id_permiso', '=', 'p.id_permiso')
            ->join('roles as r', 'rp.id_rol', '=', 'r.id_rol')
            ->where('rpu.id_usuario', $this->id_usuario)
            ->where('rpu.estado', 'activo')
            ->where('rp.estado', 'activo')
            ->select('p.id_permiso', 'p.nombre', 'r.nombre as rol', 'rp.descripcion')
            ->get()
            ->unique('id_permiso')
            ->toArray();
    }

    /**
     * Obtener los IDs de rol_permiso asignados al usuario
     */
    public function obtenerRolPermisoIds(): array
    {
        return DB::table('rol_permiso_usuario')
            ->where('id_usuario', $this->id_usuario)
            ->where('estado', 'activo')
            ->pluck('id_rol_permiso')
            ->toArray();
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function tieneRol(string $nombreRol): bool
    {
        return in_array($nombreRol, $this->obtenerRoles());
    }

    /**
     * Obtener el nombre completo del usuario (empleado o cliente)
     */
    public function getNombreCompletoAttribute(): string
    {
        if ($this->empleado) {
            return trim($this->empleado->nombre . ' ' . ($this->empleado->apellido ?? ''));
        }
        
        if ($this->cliente) {
            return $this->cliente->nombre ?? 'Cliente';
        }
        
        return $this->correo;
    }

    /**
     * Scope para usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para usuarios empleados
     */
    public function scopeEmpleados($query)
    {
        return $query->where('tipo_usuario', 'empleado');
    }

    /**
     * Scope para usuarios clientes
     */
    public function scopeClientes($query)
    {
        return $query->where('tipo_usuario', 'cliente');
    }
}