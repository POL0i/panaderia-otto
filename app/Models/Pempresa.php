<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pempresa extends Model
{
    protected $table = 'pempresa';
    protected $primaryKey = 'id_empresa';
    public $timestamps = true;

    protected $fillable = [
        'id_proveedor',
        'razon_social',
    ];

    /**
     * Get the proveedor for this empresa.
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }
}
