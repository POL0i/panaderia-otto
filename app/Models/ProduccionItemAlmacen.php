<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduccionItemAlmacen extends Model
{
    protected $table = 'produccion_item_almacen';
    public $timestamps = true;
    public $incrementing = false;

    protected $primaryKey = ['id_produccion', 'id_item', 'id_almacen'];

    protected $fillable = [
        'id_produccion',
        'id_item',
        'id_almacen',
        'cantidad',
        'tipo_movimiento',
    ];

    /**
     * Get the produccion for this movimiento.
     */
    public function produccion()
    {
        return $this->belongsTo(Produccion::class, 'id_produccion', 'id_produccion');
    }

    /**
     * Get the item for this movimiento.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    /**
     * Get the almacen for this movimiento.
     */
    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }
}
