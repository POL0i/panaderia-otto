<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraspasoInventario extends Model
{
    protected $table = 'traspasos_inventario';
    protected $primaryKey = 'id_traspaso';
    public $timestamps = true;

    protected $fillable = [
        'id_almacen_origen',
        'id_almacen_destino',
        'id_item',
        'cantidad',
        'precio_unitario',
        'fecha_traspaso',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_traspaso' => 'datetime',
    ];

    /**
     * Get the almacen origen for this traspaso.
     */
    public function almacenOrigen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen_origen', 'id_almacen');
    }

    /**
     * Get the almacen destino for this traspaso.
     */
    public function almacenDestino()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen_destino', 'id_almacen');
    }

    /**
     * Get the item for this traspaso.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    /**
     * Scope para obtener traspasos pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para obtener traspasos completados
     */
    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }

    /**
     * Scope por almacén origen
     */
    public function scopeDesdeAlmacen($query, $id_almacen)
    {
        return $query->where('id_almacen_origen', $id_almacen);
    }

    /**
     * Scope por almacén destino
     */
    public function scopeHaciaAlmacen($query, $id_almacen)
    {
        return $query->where('id_almacen_destino', $id_almacen);
    }
}
