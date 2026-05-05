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

    protected static function booted()
    {
        static::creating(function ($traspasoItem) {
            if ($traspasoItem->id_almacen_origen === $traspasoItem->id_almacen_destino) {
                throw new \Exception('El almacén origen y destino no pueden ser el mismo');
            }
            
            DB::transaction(function () use ($traspasoItem) {
                // Verificar stock en origen usando Query Builder
                $almacenItemOrigen = DB::table('almacen_item')
                    ->where('id_almacen', $traspasoItem->id_almacen_origen)
                    ->where('id_item', $traspasoItem->id_item)
                    ->lockForUpdate()
                    ->first();
                
                $stockActual = $almacenItemOrigen->stock ?? 0;
                
                if ($stockActual < $traspasoItem->cantidad) {
                    throw new \Exception("Stock insuficiente en almacén origen. Disponible: {$stockActual}");
                }
                
                // ✅ Descontar del origen con Query Builder
                DB::table('almacen_item')
                    ->where('id_almacen', $traspasoItem->id_almacen_origen)
                    ->where('id_item', $traspasoItem->id_item)
                    ->decrement('stock', $traspasoItem->cantidad);
                
                // ✅ Agregar al destino con Query Builder
                $existeDestino = DB::table('almacen_item')
                    ->where('id_almacen', $traspasoItem->id_almacen_destino)
                    ->where('id_item', $traspasoItem->id_item)
                    ->exists();
                
                if ($existeDestino) {
                    DB::table('almacen_item')
                        ->where('id_almacen', $traspasoItem->id_almacen_destino)
                        ->where('id_item', $traspasoItem->id_item)
                        ->increment('stock', $traspasoItem->cantidad);
                } else {
                    DB::table('almacen_item')->insert([
                        'id_almacen' => $traspasoItem->id_almacen_destino,
                        'id_item' => $traspasoItem->id_item,
                        'stock' => $traspasoItem->cantidad,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                \App\Models\LoteInventario::create([
                'id_almacen' => $traspasoItem->id_almacen_destino,
                'id_item' => $traspasoItem->id_item,
                'cantidad_inicial' => $traspasoItem->cantidad,
                'cantidad_disponible' => $traspasoItem->cantidad,
                'precio_unitario' => 0,
                'fecha_entrada' => now(),
                'referencia_id' => $traspasoItem->id_traspaso,
                'referencia_tipo' => 'traspaso',
                'estado' => 'disponible',
                'metodo_valuacion' => 'PEPS',
            ]);

            // ✅ Consumir lote en origen
            \App\Models\LoteInventario::consumir(
                $traspasoItem->id_almacen_origen,
                $traspasoItem->id_item,
                $traspasoItem->cantidad,
                'PEPS'
            );
            });

               // ✅ Movimiento de SALIDA (origen)
                \App\Models\MovimientoInventario::registrar([
                    'tipo_movimiento' => 'traspaso_origen',       // ← Valor correcto del ENUM
                    'id_almacen' => $traspasoItem->id_almacen_origen,
                    'id_item' => $traspasoItem->id_item,
                    'cantidad' => -$traspasoItem->cantidad,
                    'referencia_id' => $traspasoItem->id_traspaso,
                    'referencia_tipo' => 'traspaso',
                    'observaciones' => 'Salida por traspaso #' . $traspasoItem->id_traspaso,
                ]);

                // ✅ Movimiento de ENTRADA (destino)
                \App\Models\MovimientoInventario::registrar([
                    'tipo_movimiento' => 'traspaso_destino',      // ← Valor correcto del ENUM
                    'id_almacen' => $traspasoItem->id_almacen_destino,
                    'id_item' => $traspasoItem->id_item,
                    'cantidad' => $traspasoItem->cantidad,
                    'referencia_id' => $traspasoItem->id_traspaso,
                    'referencia_tipo' => 'traspaso',
                    'observaciones' => 'Entrada por traspaso #' . $traspasoItem->id_traspaso,
                ]);
                        });

                        static::deleting(function ($traspasoItem) {
            DB::transaction(function () use ($traspasoItem) {
                // Revertir con Query Builder
                DB::table('almacen_item')
                    ->where('id_almacen', $traspasoItem->id_almacen_origen)
                    ->where('id_item', $traspasoItem->id_item)
                    ->increment('stock', $traspasoItem->cantidad);
                
                DB::table('almacen_item')
                    ->where('id_almacen', $traspasoItem->id_almacen_destino)
                    ->where('id_item', $traspasoItem->id_item)
                    ->decrement('stock', $traspasoItem->cantidad);
            });
        });
    }

    public function traspaso(): BelongsTo
    {
        return $this->belongsTo(Traspaso::class, 'id_traspaso', 'id_traspaso');
    }

    public function almacenItemOrigen(): BelongsTo
    {
        return $this->belongsTo(
            AlmacenItem::class,
            ['id_almacen_origen', 'id_item'],
            ['id_almacen', 'id_item']
        );
    }

    public function almacenItemDestino(): BelongsTo
    {
        return $this->belongsTo(
            AlmacenItem::class,
            ['id_almacen_destino', 'id_item'],
            ['id_almacen', 'id_item']
        );
    }

    public function almacenOrigen()
    {
        return Almacen::find($this->id_almacen_origen);
    }

    public function almacenDestino()
    {
        return Almacen::find($this->id_almacen_destino);
    }

    public function item()
    {
        return Item::find($this->id_item);
    }
}