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

    public function getAlmacenAttribute()
    {
        if (!$this->id_almacen || !$this->id_item) {
            return null;
        }

        return Almacen::find($this->id_almacen);
    }
}