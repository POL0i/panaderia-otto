<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';
    protected $primaryKey = 'id_movimiento';

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
        'cantidad' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'costo_total' => 'decimal:2',
        'stock_resultante' => 'decimal:2',
        'fecha_movimiento' => 'datetime',
    ];

    /**
     * Relación con almacen_item
     */
    public function almacenItem(): BelongsTo
    {
        return $this->belongsTo(
            AlmacenItem::class,
            ['id_almacen', 'id_item'],
            ['id_almacen', 'id_item']
        );
    }

    /**
     * Relación con usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Registrar movimiento genérico
     */
    public static function registrar($data)
    {
        // Obtener stock actual
        $almacenItem = AlmacenItem::where('id_almacen', $data['id_almacen'])
            ->where('id_item', $data['id_item'])
            ->first();
            
        $stockActual = $almacenItem ? $almacenItem->stock : 0;
        
        return self::create([
            'tipo_movimiento' => $data['tipo_movimiento'],
            'id_almacen' => $data['id_almacen'],
            'id_item' => $data['id_item'],
            'cantidad' => $data['cantidad'],
            'precio_unitario' => $data['precio_unitario'] ?? 0,
            'costo_total' => $data['costo_total'] ?? ($data['cantidad'] * ($data['precio_unitario'] ?? 0)),
            'fecha_movimiento' => $data['fecha_movimiento'] ?? now(),
            'referencia_id' => $data['referencia_id'] ?? null,
            'referencia_tipo' => $data['referencia_tipo'] ?? null,
            'observaciones' => $data['observaciones'] ?? null,
        ]);
    }

    /**
     * Scope para reporte de movimientos por período
     */
    public function scopeEnPeriodo($query, $desde, $hasta)
    {
        return $query->whereBetween('fecha_movimiento', [$desde, $hasta]);
    }

    /**
     * Scope para un item específico
     */
    public function scopeDelItem($query, $idItem)
    {
        return $query->where('id_item', $idItem);
    }

    /**
     * Scope para un almacén específico
     */
    public function scopeEnAlmacen($query, $idAlmacen)
    {
        return $query->where('id_almacen', $idAlmacen);
    }

    /**
     * Scope para ingresos
     */
    public function scopeIngresos($query)
    {
        return $query->where('cantidad', '>', 0);
    }

    /**
     * Scope para egresos
     */
    public function scopeEgresos($query)
    {
        return $query->where('cantidad', '<', 0);
    }

    // accesores para mostrar nombres en lugar de IDs

    protected $appends = ['almacen_nombre', 'item_nombre'];

    public function getAlmacenNombreAttribute()
    {
        return \App\Models\Almacen::find($this->id_almacen)?->nombre ?? 'N/A';
    }

    public function getItemNombreAttribute()
    {
        return \App\Models\Item::find($this->id_item)?->nombre ?? 'N/A';
    }
}
