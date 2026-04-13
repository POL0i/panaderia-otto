<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoteInventario extends Model
{
    protected $table = 'lotes_inventario';
    protected $primaryKey = 'id_lote';
    public $timestamps = true;

    protected $fillable = [
        'id_almacen',
        'id_item',
        'cantidad_inicial',
        'cantidad_disponible',
        'precio_unitario',
        'fecha_entrada',
        'fecha_salida',
        'metodo_valuacion',
        'estado',
    ];

    protected $casts = [
        'fecha_entrada' => 'datetime',
        'fecha_salida' => 'datetime',
    ];

    /**
     * Get the almacen for this lote.
     */
    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Get the item for this lote.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    /**
     * Scope para obtener lotes disponibles (PEPS)
     */
    public function scopePEPS($query)
    {
        return $query->where('metodo_valuacion', 'PEPS')->where('estado', 'disponible')->orderBy('fecha_entrada', 'asc');
    }

    /**
     * Scope para obtener lotes disponibles (UEPS)
     */
    public function scopeUEPS($query)
    {
        return $query->where('metodo_valuacion', 'UEPS')->where('estado', 'disponible')->orderBy('fecha_entrada', 'desc');
    }

    /**
     * Scope para obtener lotes disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->where('estado', 'disponible')->where('cantidad_disponible', '>', 0);
    }

    /**
     * Scope por almacén e item
     */
    public function scopePorAlmacenItem($query, $id_almacen, $id_item)
    {
        return $query->where('id_almacen', $id_almacen)->where('id_item', $id_item);
    }

    /**
     * Scope por método de valuación
     */
    public function scopePorMetodo($query, $metodo)
    {
        return $query->where('metodo_valuacion', $metodo);
    }

    /**
     * Calcular costo total consumido
     */
    public function getCostoConsumidoAttribute()
    {
        return ($this->cantidad_inicial - $this->cantidad_disponible) * $this->precio_unitario;
    }

    /**
     * Calcular valor del lote disponible
     */
    public function getValorDisponibleAttribute()
    {
        return $this->cantidad_disponible * $this->precio_unitario;
    }
}
