<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaVenta extends Model
{
    protected $table = 'notas_venta';
    protected $primaryKey = 'id_nota_venta';
    public $timestamps = true;

    protected $fillable = [
        'fecha_venta',
        'monto_total',
        'estado',
        'id_cliente',
        'id_empleado',
    ];

    protected $dates = [
        'fecha_venta',
    ];

    /**
     * Get the cliente for this nota de venta.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    /**
     * Get the empleado for this nota de venta.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    /**
     * Get all detalles de venta for this nota.
     */
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'id_nota_venta', 'id_nota_venta');
    }

    /**
     * Get all productos through detalles.
     */
    public function productos()
    {
        return $this->hasManyThrough(
            Producto::class,
            DetalleVenta::class,
            'id_nota_venta',
            'id_producto',
            'id_nota_venta',
            'id_producto'
        );
    }
}
