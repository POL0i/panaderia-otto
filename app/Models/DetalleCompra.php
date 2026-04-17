<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleCompra extends Model
{
    protected $table = 'detalles_compra';
    public $timestamps = true;
    public $incrementing = false;

    protected $primaryKey = ['id_nota_compra', 'id_almacen', 'id_item'];

    protected $fillable = [
        'id_nota_compra',
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
     * Relación con la nota de compra
     */
    public function notaCompra(): BelongsTo
    {
        return $this->belongsTo(NotaCompra::class, 'id_nota_compra', 'id_nota_compra');
    }

    /**
     * Relación con el almacén
     */
    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Relación con el item
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
     * Acceso al insumo a través de la relación item->insumo
     */
    public function insumo()
    {
        return $this->item->insumo();
    }

    /**
     * Boot method para incrementar stock al crear detalle de compra
     */
    protected static function booted()
    {
        static::creating(function ($detalleCompra) {
            // Buscar o crear el registro en almacen_item
            $almacenItem = AlmacenItem::firstOrCreate(
                [
                    'id_almacen' => $detalleCompra->id_almacen,
                    'id_item' => $detalleCompra->id_item
                ],
                ['stock' => 0]
            );
            
            // Incrementar stock
            $almacenItem->increment('stock', $detalleCompra->cantidad);
            
            // Registrar movimiento de inventario
            MovimientoInventario::create([
                'tipo_movimiento' => 'ingreso',
                'id_almacen' => $detalleCompra->id_almacen,
                'id_item' => $detalleCompra->id_item,
                'cantidad' => $detalleCompra->cantidad,
                'precio_unitario' => $detalleCompra->precio,
                'costo_total' => $detalleCompra->cantidad * $detalleCompra->precio,
                'fecha_movimiento' => now(),
                'referencia_id' => $detalleCompra->id_nota_compra,
                'referencia_tipo' => 'compra',
                'estado' => 'completado',
                'observaciones' => "Ingreso por compra N° {$detalleCompra->id_nota_compra}"
            ]);
        });

        static::updating(function ($detalleCompra) {
            // Obtener diferencia de cantidad
            $original = $detalleCompra->getOriginal();
            $diferencia = $detalleCompra->cantidad - $original['cantidad'];
            
            if ($diferencia !== 0) {
                $almacenItem = AlmacenItem::where('id_almacen', $detalleCompra->id_almacen)
                    ->where('id_item', $detalleCompra->id_item)
                    ->first();
                    
                if ($almacenItem) {
                    if ($diferencia > 0) {
                        $almacenItem->increment('stock', $diferencia);
                    } else {
                        // Verificar que no quede stock negativo
                        if ($almacenItem->stock + $diferencia < 0) {
                            throw new \Exception("No se puede reducir más stock del disponible");
                        }
                        $almacenItem->decrement('stock', abs($diferencia));
                    }
                }
            }
        });

        static::deleting(function ($detalleCompra) {
            // Devolver stock al eliminar detalle
            $almacenItem = AlmacenItem::where('id_almacen', $detalleCompra->id_almacen)
                ->where('id_item', $detalleCompra->id_item)
                ->first();
                
            if ($almacenItem) {
                $almacenItem->decrement('stock', $detalleCompra->cantidad);
            }
        });
    }
}