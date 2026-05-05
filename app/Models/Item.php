<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id_item';
    public $timestamps = true;

    protected $fillable = [
        'tipo_item',
        'nombre',
        'unidad_medida',
    ];

    /**
     * Get the producto if this item is a producto.
     */
    public function producto()
    {
        return $this->hasOne(Producto::class, 'id_item', 'id_item');
    }

    /**
     * Get the insumo if this item is an insumo.
     */
    public function insumo()
    {
        return $this->hasOne(Insumo::class, 'id_item', 'id_item');
    }

    /**
     * Get all almacen_items for this item.
     */
    public function almacenItems()
    {
        return $this->hasMany(AlmacenItem::class, 'id_item', 'id_item');
    }

    /**
     * Get all detalles de producción for this item.
     */
    public function detallesProduccion()
    {
        return $this->hasMany(DetalleProduccion::class, 'id_item', 'id_item');
    }
}