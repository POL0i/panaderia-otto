<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
    ];

    /**
     * Get all permissions for this role.
     */
    public function permisos()
    {
        return $this->hasMany(RolPermiso::class, 'id_rol', 'id_rol');
    }

    /**
     * Get all rol_permiso_usuarios for this role through rol_permiso.
     */
    public function usuarios()
    {
        return $this->hasManyThrough(
            RolPermisoUsuario::class,
            RolPermiso::class,
            'id_rol',
            'id_rol_permiso',
            'id_rol',
            'id_rol_permiso'
        );
    }
}
