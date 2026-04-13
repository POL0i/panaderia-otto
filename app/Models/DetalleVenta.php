<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalles_venta';
    public $timestamps = true;
    public $incrementing = false;

    protected $primaryKey = ['id_nota_venta', 'id_producto'];

    protected $fillable = [
        'id_nota_venta',
        'id_producto',
        'cantidad',
        'precio',
    ];

    /**
     * Get the nota de venta for this detalle.
     */
    public function notaVenta()
    {
        return $this->belongsTo(NotaVenta::class, 'id_nota_venta', 'id_nota_venta');
    }

    /**
     * Get the producto for this detalle.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
