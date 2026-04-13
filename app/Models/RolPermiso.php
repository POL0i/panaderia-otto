<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolPermiso extends Model
{
    protected $table = 'rol_permiso';
    protected $primaryKey = 'id_rol_permiso';
    public $timestamps = true;

    protected $fillable = [
        'id_rol',
        'id_permiso',
        'estado',
        'descripcion',
    ];

    /**
     * Get the role that owns this role_permission.
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    /**
     * Get the permission that owns this role_permission.
     */
    public function permiso()
    {
        return $this->belongsTo(Permiso::class, 'id_permiso', 'id_permiso');
    }

    /**
     * Get all rol_permiso_usuarios for this role_permission.
     */
    public function usuarios()
    {
        return $this->hasMany(RolPermisoUsuario::class, 'id_rol_permiso', 'id_rol_permiso');
    }
}
