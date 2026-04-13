<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detalles_compra';
    public $timestamps = true;
    public $incrementing = false;

    protected $primaryKey = ['id_nota_compra', 'id_insumo'];

    protected $fillable = [
        'id_nota_compra',
        'id_insumo',
        'cantidad',
        'precio',
    ];

    /**
     * Get the nota de compra for this detalle.
     */
    public function notaCompra()
    {
        return $this->belongsTo(NotaCompra::class, 'id_nota_compra', 'id_nota_compra');
    }

    /**
     * Get the insumo for this detalle.
     */
    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'id_insumo', 'id_insumo');
    }
}
