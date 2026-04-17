<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

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

    protected $casts = [
        'cantidad' => 'integer',
    ];

    /**
     * Boot method para manejar el stock automáticamente
     */
    protected static function booted()
    {
        static::creating(function ($traspasoItem) {
            // Validar que los almacenes sean diferentes
            if ($traspasoItem->id_almacen_origen === $traspasoItem->id_almacen_destino) {
                throw new \Exception('El almacén origen y destino no pueden ser el mismo');
            }
            
            DB::transaction(function () use ($traspasoItem) {
                // Verificar stock en origen
                $almacenItemOrigen = AlmacenItem::where('id_almacen', $traspasoItem->id_almacen_origen)
                    ->where('id_item', $traspasoItem->id_item)
                    ->lockForUpdate()
                    ->first();
                
                if (!$almacenItemOrigen || $almacenItemOrigen->stock < $traspasoItem->cantidad) {
                    $stock = $almacenItemOrigen->stock ?? 0;
                    throw new \Exception("Stock insuficiente en almacén origen. Disponible: {$stock}");
                }
                
                // Descontar del origen
                $almacenItemOrigen->decrement('stock', $traspasoItem->cantidad);
                
                // Agregar al destino (crear si no existe)
                AlmacenItem::updateOrCreate(
                    [
                        'id_almacen' => $traspasoItem->id_almacen_destino,
                        'id_item' => $traspasoItem->id_item
                    ],
                    ['stock' => 0]
                )->increment('stock', $traspasoItem->cantidad);
            });
        });

        static::deleting(function ($traspasoItem) {
            DB::transaction(function () use ($traspasoItem) {
                // Revertir el traspaso
                AlmacenItem::where('id_almacen', $traspasoItem->id_almacen_origen)
                    ->where('id_item', $traspasoItem->id_item)
                    ->increment('stock', $traspasoItem->cantidad);
                
                AlmacenItem::where('id_almacen', $traspasoItem->id_almacen_destino)
                    ->where('id_item', $traspasoItem->id_item)
                    ->decrement('stock', $traspasoItem->cantidad);
            });
        });
    }

    /**
     * Relación con el traspaso
     */
    public function traspaso(): BelongsTo
    {
        return $this->belongsTo(Traspaso::class, 'id_traspaso', 'id_traspaso');
    }

    /**
     * Relación con almacen_item (origen)
     */
    public function almacenItemOrigen(): BelongsTo
    {
        return $this->belongsTo(
            AlmacenItem::class,
            ['id_almacen_origen', 'id_item'],
            ['id_almacen', 'id_item']
        );
    }

    /**
     * Relación con almacen_item (destino)
     */
    public function almacenItemDestino(): BelongsTo
    {
        return $this->belongsTo(
            AlmacenItem::class,
            ['id_almacen_destino', 'id_item'],
            ['id_almacen', 'id_item']
        );
    }

    /**
     * Acceso rápido al almacén origen
     */
    public function almacenOrigen()
    {
        return $this->almacenItemOrigen->almacen();
    }

    /**
     * Acceso rápido al almacén destino
     */
    public function almacenDestino()
    {
        return $this->almacenItemDestino->almacen();
    }

    /**
     * Acceso rápido al item
     */
    public function item()
    {
        return $this->almacenItemOrigen->item();
    }
}