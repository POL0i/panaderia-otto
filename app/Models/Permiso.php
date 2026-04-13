<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permisos';
    protected $primaryKey = 'id_permiso';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
    ];

    /**
     * Get all roles that have this permission.
     */
    public function roles()
    {
        return $this->hasMany(RolPermiso::class, 'id_permiso', 'id_permiso');
    }
}
