<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Relación con almacén
     */
    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Relación con item
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    /**
     * ✅ Relación con LOTES INVENTARIO (UNO a MUCHOS)
     * Un AlmacenItem tiene muchos lotes
     */
    public function lotes(): HasMany
    {
        return $this->hasMany(
            LoteInventario::class,
            ['id_almacen', 'id_item'],
            ['id_almacen', 'id_item']
        );
    }

    /**
     * ✅ Relación con MOVIMIENTOS INVENTARIO (UNO a MUCHOS)
     * Un AlmacenItem tiene muchos movimientos
     */
    public function movimientos(): HasMany
    {
        return $this->hasMany(
            MovimientoInventario::class,
            ['id_almacen', 'id_item'],
            ['id_almacen', 'id_item']
        );
    }

    /**
     * ✅ Relación con LOTES ACTIVOS (disponibles)
     */
    public function lotesDisponibles(): HasMany
    {
        return $this->lotes()
            ->where('estado', 'disponible')
            ->where('cantidad_disponible', '>', 0);
    }

    /**
     * Relación con detalles de venta
     */
    public function detallesVenta(): HasMany
    {
        return $this->hasMany(
            DetalleVenta::class,
            ['id_almacen', 'id_item'],
            ['id_almacen', 'id_item']
        );
    }

    /**
     * Relación con detalles de producción
     */
    public function detallesProduccion(): HasMany
    {
        return $this->hasMany(
            DetalleProduccion::class,
            ['id_almacen', 'id_item'],
            ['id_almacen', 'id_item']
        );
    }

    /**
     * Acceso al producto si el item es de tipo producto
     */
    public function producto()
    {
        return $this->hasOneThrough(
            Producto::class,
            Item::class,
            'id_item',
            'id_item',
            'id_item',
            'id_item'
        );
    }

    /**
     * Obtener stock actual desde los lotes (más preciso)
     */
    public function getStockDesdeLotesAttribute()
    {
        return $this->lotes()
            ->where('estado', 'disponible')
            ->sum('cantidad_disponible');
    }

    /**
     * Calcular costo promedio ponderado
     */
    public function getCostoPromedioAttribute()
    {
        $lotes = $this->lotes()
            ->where('estado', 'disponible')
            ->where('cantidad_disponible', '>', 0)
            ->get();
            
        if ($lotes->isEmpty()) {
            return 0;
        }
        
        $costoTotal = $lotes->sum(function($lote) {
            return $lote->cantidad_disponible * $lote->precio_unitario;
        });
        
        $cantidadTotal = $lotes->sum('cantidad_disponible');
        
        return $cantidadTotal > 0 ? $costoTotal / $cantidadTotal : 0;
    }
}