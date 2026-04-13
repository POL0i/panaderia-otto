<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $table = 'almacenes';
    protected $primaryKey = 'id_almacen';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'ubicacion',
        'capacidad',
    ];

    /**
     * Get all almacen_items for this almacen.
     */
    public function items()
    {
        return $this->hasMany(AlmacenItem::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Get all movimientos de produccion from this almacen.
     */
    public function produccionMovimientos()
    {
        return $this->hasMany(ProduccionItemAlmacen::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Get all traspasos from this almacen as origen.
     */
    public function traspasosComo()
    {
        return $this->hasMany(TraspasoAlmacenItem::class, 'id_almacen_origen', 'id_almacen');
    }

    /**
     * Get all traspasos to this almacen as destino.
     */
    public function traspasosPara()
    {
        return $this->hasMany(TraspasoAlmacenItem::class, 'id_almacen_destino', 'id_almacen');
    }
}
