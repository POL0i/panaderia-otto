<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';
    protected $primaryKey = 'id_movimiento';
    public $timestamps = true;

    protected $fillable = [
        'tipo_movimiento',
        'id_almacen',
        'id_item',
        'cantidad',
        'precio_unitario',
        'costo_total',
        'fecha_movimiento',
        'referencia_id',
        'referencia_tipo',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_movimiento' => 'datetime',
    ];

    /**
     * Get the almacen for this movimiento.
     */
    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Get the item for this movimiento.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    /**
     * Scope para obtener solo ingresos
     */
    public function scopeIngresos($query)
    {
        return $query->where('tipo_movimiento', 'ingreso');
    }

    /**
     * Scope para obtener solo egresos
     */
    public function scopeEgresos($query)
    {
        return $query->where('tipo_movimiento', 'egreso');
    }

    /**
     * Scope para obtener solo traspasos
     */
    public function scopeTraspasos($query)
    {
        return $query->whereIn('tipo_movimiento', ['traspaso_origen', 'traspaso_destino']);
    }

    /**
     * Scope para obtener solo movimientos completados
     */
    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }

    /**
     * Scope por fecha
     */
    public function scopePorFecha($query, $fecha)
    {
        return $query->whereDate('fecha_movimiento', $fecha);
    }

    /**
     * Scope por almacén e item
     */
    public function scopePorAlmacenItem($query, $id_almacen, $id_item)
    {
        return $query->where('id_almacen', $id_almacen)->where('id_item', $id_item);
    }
}
