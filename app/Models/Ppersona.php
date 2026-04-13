<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ppersona extends Model
{
    protected $table = 'ppersona';
    protected $primaryKey = 'id_persona';
    public $timestamps = true;

    protected $fillable = [
        'id_proveedor',
        'nombre',
    ];

    /**
     * Get the proveedor for this persona.
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }
}
