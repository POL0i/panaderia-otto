<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    protected $primaryKey = 'id_proveedor';
    public $timestamps = true;

    protected $fillable = [
        'tipo_proveedor',
        'telefono',
        'direccion',
        'correo',
    ];

    /**
     * Get the empresa if this proveedor is empresa.
     */
    public function empresa()
    {
        return $this->hasOne(Pempresa::class, 'id_proveedor', 'id_proveedor');
    }

    /**
     * Get the persona if this proveedor is persona.
     */
    public function persona()
    {
        return $this->hasOne(Ppersona::class, 'id_proveedor', 'id_proveedor');
    }

    /**
     * Get all notas de compra for this proveedor.
     */
    public function notasCompra()
    {
        return $this->hasMany(NotaCompra::class, 'id_proveedor', 'id_proveedor');
    }
}
