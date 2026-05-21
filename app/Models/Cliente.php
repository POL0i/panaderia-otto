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
    // Relación con usuarios
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_cliente', 'id_cliente');
    }
    
    // Relación para obtener el primer usuario (si existe)
    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id_cliente', 'id_cliente');
    }

    /**
     * Get all notas de venta for this cliente.
     */
    public function notasVenta()
    {
        return $this->hasMany(NotaVenta::class, 'id_cliente', 'id_cliente');
    }
}
