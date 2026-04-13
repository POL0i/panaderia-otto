<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraspasoAlmacenItem extends Model
{
    protected $table = 'traspaso_almacen_item';
    protected $primaryKey = 'id_detalle_traspaso';
    public $timestamps = true;

    protected $fillable = [
        'id_traspaso',
        'id_almacen_origen',
        'id_almacen_destino',
        'id_item',
        'cantidad',
    ];

    /**
     * Get the traspaso for this detalle.
     */
    public function traspaso()
    {
        return $this->belongsTo(Traspaso::class, 'id_traspaso', 'id_traspaso');
    }

    /**
     * Get the almacen origen for this detalle.
     */
    public function almacenOrigen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen_origen', 'id_almacen');
    }

    /**
     * Get the almacen destino for this detalle.
     */
    public function almacenDestino()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen_destino', 'id_almacen');
    }

    /**
     * Get the item for this detalle.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }
}
