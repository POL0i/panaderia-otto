<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoteInventario extends Model
{
    protected $table = 'lotes_inventario';
    protected $primaryKey = 'id_lote';

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
        'referencia_id',
        'referencia_tipo',
    ];

    protected $casts = [
        'cantidad_inicial' => 'decimal:2',
        'cantidad_disponible' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'fecha_entrada' => 'datetime',
        'fecha_salida' => 'datetime',
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
     * Crear lote desde una compra
     */
    public static function desdeCompra(DetalleCompra $detalleCompra)
    {
        return self::create([
            'id_almacen' => $detalleCompra->id_almacen,
            'id_item' => $detalleCompra->id_item,
            'cantidad_inicial' => $detalleCompra->cantidad,
            'cantidad_disponible' => $detalleCompra->cantidad,
            'precio_unitario' => $detalleCompra->precio,
            'fecha_entrada' => now(),
            'referencia_id' => $detalleCompra->id_nota_compra,
            'referencia_tipo' => 'compra',
            'estado' => 'disponible'
        ]);
    }

    /**
     * Crear lote desde producción
     */
    public static function desdeProduccion(ProduccionItemAlmacen $produccionItem)
    {
        if ($produccionItem->esIngreso()) {
            return self::create([
                'id_almacen' => $produccionItem->id_almacen,
                'id_item' => $produccionItem->id_item,
                'cantidad_inicial' => $produccionItem->cantidad,
                'cantidad_disponible' => $produccionItem->cantidad,
                'precio_unitario' => self::calcularCostoProduccion($produccionItem),
                'fecha_entrada' => now(),
                'referencia_id' => $produccionItem->id_produccion,
                'referencia_tipo' => 'produccion',
                'estado' => 'disponible'
            ]);
        }
        return null;
    }

    /**
     * Consumir del lote (PEPS/UEPS)
     */
    public static function consumir($idAlmacen, $idItem, $cantidad, $metodo = 'PEPS')
    {
        $lotes = self::where('id_almacen', $idAlmacen)
            ->where('id_item', $idItem)
            ->where('estado', 'disponible')
            ->where('cantidad_disponible', '>', 0)
            ->when($metodo === 'PEPS', fn($q) => $q->orderBy('fecha_entrada', 'asc'))
            ->when($metodo === 'UEPS', fn($q) => $q->orderBy('fecha_entrada', 'desc'))
            ->lockForUpdate()
            ->get();

        $pendiente = $cantidad;
        $costoTotal = 0;

        foreach ($lotes as $lote) {
            if ($pendiente <= 0) break;

            $consumir = min($lote->cantidad_disponible, $pendiente);
            $lote->cantidad_disponible -= $consumir;
            
            if ($lote->cantidad_disponible <= 0) {
                $lote->estado = 'consumido';
                $lote->fecha_salida = now();
            }
            
            $lote->save();
            
            $costoTotal += $consumir * $lote->precio_unitario;
            $pendiente -= $consumir;
        }

        return [
            'costo_total' => $costoTotal,
            'costo_unitario_promedio' => $cantidad > 0 ? $costoTotal / $cantidad : 0,
            'cantidad_consumida' => $cantidad - $pendiente
        ];
    }

    private static function calcularCostoProduccion($produccionItem)
    {
        // Calcular costo basado en insumos consumidos
        $insumos = ProduccionItemAlmacen::where('id_produccion', $produccionItem->id_produccion)
            ->egresos()
            ->get();
            
        $costoTotal = 0;
        foreach ($insumos as $insumo) {
            $costoTotal += $insumo->cantidad * ($insumo->item->insumo->precio_compra ?? 0);
        }
        
        return $costoTotal / $produccionItem->cantidad;
    }
}