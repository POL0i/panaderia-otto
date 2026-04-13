<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
    ];

    /**
     * Get all usuarios for this cliente.
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_cliente', 'id_cliente');
    }

    /**
     * Get all notas de venta for this cliente.
     */
    public function notasVenta()
    {
        return $this->hasMany(NotaVenta::class, 'id_cliente', 'id_cliente');
    }
}
