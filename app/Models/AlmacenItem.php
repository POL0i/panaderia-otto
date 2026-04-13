<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * Get the almacen for this almacen_item.
     */
    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Get the item for this almacen_item.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }
}
