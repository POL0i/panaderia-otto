<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

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
        'fecha_vencimiento',
        'metodo_valuacion',
        'estado',
        'referencia_id',
        'referencia_tipo',
    ];

    protected $casts = [
        'fecha_entrada' => 'datetime',
        'fecha_salida' => 'datetime',
        'fecha_vencimiento' => 'date',
    ];

    protected $appends = ['almacen_nombre', 'item_nombre']; // ← Cargar siempre

    /**
     * Relación con almacen_item (SOLO para integridad, no para consultas)
     */
    public function almacenItem(): BelongsTo
    {
        return $this->belongsTo(
            AlmacenItem::class,
            ['id_almacen', 'id_item'],
            ['id_almacen', 'id_item']
        );
    }

    // ✅ Accesores (sin relaciones directas)
    public function getAlmacenNombreAttribute()
    {
        return Almacen::find($this->id_almacen)?->nombre ?? 'N/A';
    }

    public function getItemNombreAttribute()
    {
        return Item::find($this->id_item)?->nombre ?? 'N/A';
    }
    
    /**
     * Crear lote desde una compra
     */
    public static function desdeCompra(DetalleCompra $detalleCompra, $fechaVencimiento = null)
    {
        return self::create([
            'id_almacen' => $detalleCompra->id_almacen,
            'id_item' => $detalleCompra->id_item,
            'cantidad_inicial' => $detalleCompra->cantidad,
            'cantidad_disponible' => $detalleCompra->cantidad,
            'precio_unitario' => $detalleCompra->precio,
            'fecha_entrada' => now(),
            'fecha_vencimiento' => $fechaVencimiento,  // ← Ahora sí existe
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
    public static function consumir($idAlmacen, $idItem, $cantidad, $metodo = 'PEPS', $referenciaId = null, $tipoReferencia = 'produccion')
    {
        Log::info('📦 LoteInventario::consumir - PARÁMETROS', [
                'almacen' => $idAlmacen,
                'item' => $idItem,
                'cantidad' => $cantidad,
                'metodo' => $metodo,
                'ref_id' => $referenciaId,
                'ref_tipo' => $tipoReferencia
            ]);

        $lotes = self::where('id_almacen', $idAlmacen)
            ->where('id_item', $idItem)
            ->where('estado', 'disponible')
            ->where('cantidad_disponible', '>', 0)
            ->when($metodo === 'PEPS', fn($q) => $q->orderBy('fecha_entrada', 'asc'))
            ->when($metodo === 'UEPS', fn($q) => $q->orderBy('fecha_entrada', 'desc'))
            ->lockForUpdate()
            ->get();

        \Log::info('🔢 Lotes encontrados para consumir', [
            'cantidad_lotes' => $lotes->count(),
            'ids' => $lotes->pluck('id_lote')->toArray()
        ]);

        $pendiente = $cantidad;
        $costoTotal = 0;
        $lotesUsados = [];

        foreach ($lotes as $lote) {
            if ($pendiente <= 0) break;

            $consumir = min($lote->cantidad_disponible, $pendiente);
            $lote->cantidad_disponible -= $consumir;
            
            // ✅ SIEMPRE registrar cuándo se consumió
            $lote->fecha_salida = now();
            
            if ($lote->cantidad_disponible <= 0) {
                $lote->estado = 'consumido';
            }
            

            $lote->save();

            \Log::info('💾 Lote actualizado', [
                'id_lote' => $lote->id_lote,
                'consumido' => $consumir,
                'disponible_ahora' => $lote->cantidad_disponible,
                'estado' => $lote->estado,
                'fecha_salida' => $lote->fecha_salida,
                'referencia_id' => $lote->referencia_id,
                'referencia_tipo' => $lote->referencia_tipo
            ]);
            
            $costoTotal += $consumir * $lote->precio_unitario;
            $pendiente -= $consumir;
            
            $lotesUsados[] = [
                'id_lote' => $lote->id_lote,
                'cantidad' => $consumir,
                'precio' => $lote->precio_unitario
            ];
        }

        return [
            'costo_total' => $costoTotal,
            'costo_unitario_promedio' => $cantidad > 0 ? $costoTotal / $cantidad : 0,
            'cantidad_consumida' => $cantidad - $pendiente,
            'lotes_usados' => $lotesUsados
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