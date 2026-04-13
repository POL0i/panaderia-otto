<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolPermisoUsuario extends Model
{
    protected $table = 'rol_permiso_usuario';
    protected $primaryKey = 'id_rol_permiso_usuario';
    public $timestamps = true;

    protected $fillable = [
        'id_rol_permiso',
        'id_usuario',
        'estado',
        'fecha_asignacion',
    ];

    protected $dates = [
        'fecha_asignacion',
    ];

    /**
     * Get the rol_permiso that owns this rol_permiso_usuario.
     */
    public function rolPermiso()
    {
        return $this->belongsTo(RolPermiso::class, 'id_rol_permiso', 'id_rol_permiso');
    }

    /**
     * Get the usuario that owns this rol_permiso_usuario.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Get the rol through rol_permiso.
     */
    public function rol()
    {
        return $this->hasOneThrough(
            Rol::class,
            RolPermiso::class,
            'id_rol_permiso',
            'id_rol',
            'id_rol_permiso',
            'id_rol'
        );
    }

    /**
     * Get the permiso through rol_permiso.
     */
    public function permiso()
    {
        return $this->hasOneThrough(
            Permiso::class,
            RolPermiso::class,
            'id_rol_permiso',
            'id_permiso',
            'id_rol_permiso',
            'id_permiso'
        );
    }
}
