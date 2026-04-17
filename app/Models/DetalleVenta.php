<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleVenta extends Model
{
    protected $table = 'detalles_venta';
    public $timestamps = true;
    public $incrementing = false;

    protected $primaryKey = ['id_nota_venta', 'id_almacen', 'id_item'];

    protected $fillable = [
        'id_nota_venta',
        'id_almacen',
        'id_item',
        'cantidad',
        'precio',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relación con la nota de venta
     */
    public function notaVenta(): BelongsTo
    {
        return $this->belongsTo(NotaVenta::class, 'id_nota_venta', 'id_nota_venta');
    }

    /**
     * Relación con el almacén (a través de almacen_item)
     */
    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Relación con el item (a través de almacen_item)
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    /**
     * Relación directa con almacen_item
     */
    public function almacenItem()
    {
        return $this->belongsTo(
            AlmacenItem::class, 
            ['id_almacen', 'id_item'], 
            ['id_almacen', 'id_item']
        );
    }

    /**
     * Acceso al producto a través de la relación item->producto
     */
    public function producto()
    {
        return $this->item->producto();
    }

    /**
     * Boot method para validar stock antes de crear
     */
    protected static function booted()
    {
        static::creating(function ($detalleVenta) {
            // Verificar stock disponible
            $almacenItem = AlmacenItem::where('id_almacen', $detalleVenta->id_almacen)
                ->where('id_item', $detalleVenta->id_item)
                ->first();
                
            if (!$almacenItem || $almacenItem->stock < $detalleVenta->cantidad) {
                throw new \Exception("Stock insuficiente en el almacén seleccionado");
            }
            
            // Descontar stock
            $almacenItem->decrement('stock', $detalleVenta->cantidad);
        });

        static::deleting(function ($detalleVenta) {
            // Devolver stock al eliminar detalle
            $almacenItem = AlmacenItem::where('id_almacen', $detalleVenta->id_almacen)
                ->where('id_item', $detalleVenta->id_item)
                ->first();
                
            if ($almacenItem) {
                $almacenItem->increment('stock', $detalleVenta->cantidad);
            }
        });
    }
}