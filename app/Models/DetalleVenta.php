<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleVenta extends Model
{
    protected $table = 'detalles_venta';
    protected $primaryKey = 'id_detalle_venta';  // ← nueva clave
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_nota_venta',
        'id_almacen',
        'id_item',
        'cantidad',
        'precio',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio' => 'decimal:2',
    ];

    public function notaVenta(): BelongsTo
    {
        return $this->belongsTo(NotaVenta::class, 'id_nota_venta', 'id_nota_venta');
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

    public function producto()
    {
        return $this->item->producto();
    }
}