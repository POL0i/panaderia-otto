<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleProduccion extends Model
{
    protected $table = 'detalle_produccion';
    protected $primaryKey = 'id_detalle_produccion';
    protected $fillable = [
        'id_produccion', 'id_detalle_receta', 'id_almacen', 'id_item', 'cantidad', 'tipo_movimiento'
    ];

    public function produccion()
    {
        return $this->belongsTo(Produccion::class, 'id_produccion');
    }

    public function detalleReceta()
    {
        return $this->belongsTo(DetalleReceta::class, 'id_detalle_receta');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item');
    }

    public function almacenItem()
    {
        return $this->belongsTo(AlmacenItem::class, ['id_almacen', 'id_item'], ['id_almacen', 'id_item']);
    }

    // Acceso al almacén a través de almacenItem
    public function almacen()
    {
        return $this->hasOneThrough(
            Almacen::class,
            AlmacenItem::class,
            ['id_almacen', 'id_item'], // Claves en almacen_item
            'id_almacen',              // Clave en almacenes
            ['id_almacen', 'id_item'], // Claves locales en DetalleProduccion
            'id_almacen'               // Clave local en AlmacenItem
        );
    }

    // Acceso rápido al insumo o producto
    public function insumo()
    {
        return $this->item->insumo();
    }

    public function producto()
    {
        return $this->item->producto();
    }
}