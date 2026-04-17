<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlmacenItem extends Model
{
    protected $table = 'almacen_item';
    public $timestamps = true;
    public $incrementing = false;

    protected $primaryKey = ['id_almacen', 'id_item'];

    protected $fillable = [
        'id_almacen',
        'id_item',
        'stock',
    ];

    /**
     * Relación con almacén
     */
    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Relación con item
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    /**
     * Relación con detalles de venta
     */
    public function detallesVenta(): HasMany
    {
        return $this->hasMany(
            DetalleVenta::class,
            ['id_almacen', 'id_item'],
            ['id_almacen', 'id_item']
        );
    }

    /**
     * Acceso al producto si el item es de tipo producto
     */
    public function producto()
    {
        return $this->hasOneThrough(
            Producto::class,
            Item::class,
            'id_item', // Foreign key en items
            'id_item', // Foreign key en productos
            'id_item', // Local key en almacen_item
            'id_item'  // Local key en items
        );
    }
}