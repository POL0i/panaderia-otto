<?php
// app/Models/DetalleCompra.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleCompra extends Model
{
    protected $table = 'detalles_compra';
    protected $primaryKey = 'id_detalle_compra';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'id_nota_compra',
        'id_almacen',
        'id_item',
        'cantidad',
        'precio',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio' => 'decimal:2',
    ];

    public function notaCompra(): BelongsTo
    {
        return $this->belongsTo(NotaCompra::class, 'id_nota_compra', 'id_nota_compra');
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    public function almacenItem()
    {
        return $this->belongsTo(
            AlmacenItem::class, 
            ['id_almacen', 'id_item'], 
            ['id_almacen', 'id_item']
        );
    }

    public function insumo()
    {
        return $this->item->insumo();
    }
}