<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class ProduccionItemAlmacen extends Model
{
    protected $table = 'produccion_item_almacen';
    public $timestamps = true;
    public $incrementing = false;

    protected $primaryKey = ['id_produccion', 'id_almacen', 'id_item'];

    protected $fillable = [
        'id_produccion',
        'id_almacen',
        'id_item',
        'cantidad',
        'tipo_movimiento',
    ];

    protected $casts = [
        'cantidad' => 'integer',
    ];

    /**
     * Constantes para tipo_movimiento
     */
    const TIPO_INGRESO = 'ingreso';  // Productos terminados
    const TIPO_EGRESO = 'egreso';    // Insumos consumidos

    /**
     * Boot method para manejar el stock automáticamente
     */
    protected static function booted()
    {
        static::creating(function ($movimiento) {
            DB::transaction(function () use ($movimiento) {
                if ($movimiento->tipo_movimiento === self::TIPO_INGRESO) {
                    // Es un producto terminado - incrementa stock
                    AlmacenItem::updateOrCreate(
                        [
                            'id_almacen' => $movimiento->id_almacen,
                            'id_item' => $movimiento->id_item
                        ],
                        ['stock' => 0]
                    )->increment('stock', $movimiento->cantidad);
                    
                } elseif ($movimiento->tipo_movimiento === self::TIPO_EGRESO) {
                    // Es un insumo consumido - decrementa stock
                    $almacenItem = AlmacenItem::where('id_almacen', $movimiento->id_almacen)
                        ->where('id_item', $movimiento->id_item)
                        ->lockForUpdate()
                        ->first();
                    
                    if (!$almacenItem || $almacenItem->stock < $movimiento->cantidad) {
                        $stock = $almacenItem->stock ?? 0;
                        throw new \Exception("Stock insuficiente de insumo. Disponible: {$stock}, Requerido: {$movimiento->cantidad}");
                    }
                    
                    $almacenItem->decrement('stock', $movimiento->cantidad);
                }
                
                // Registrar en movimientos_inventario
                self::registrarMovimientoInventario($movimiento);
            });
        });

        static::deleting(function ($movimiento) {
            DB::transaction(function () use ($movimiento) {
                // Revertir el movimiento
                if ($movimiento->tipo_movimiento === self::TIPO_INGRESO) {
                    AlmacenItem::where('id_almacen', $movimiento->id_almacen)
                        ->where('id_item', $movimiento->id_item)
                        ->decrement('stock', $movimiento->cantidad);
                } elseif ($movimiento->tipo_movimiento === self::TIPO_EGRESO) {
                    AlmacenItem::where('id_almacen', $movimiento->id_almacen)
                        ->where('id_item', $movimiento->id_item)
                        ->increment('stock', $movimiento->cantidad);
                }
            });
        });
    }

    /**
     * Registrar el movimiento en la tabla de inventario para trazabilidad
     */
    private static function registrarMovimientoInventario($movimiento)
    {
        $signo = $movimiento->tipo_movimiento === self::TIPO_INGRESO ? 1 : -1;
        
        MovimientoInventario::create([
            'tipo_movimiento' => $movimiento->tipo_movimiento === self::TIPO_INGRESO ? 'produccion' : 'produccion_insumo',
            'id_almacen' => $movimiento->id_almacen,
            'id_item' => $movimiento->id_item,
            'cantidad' => $movimiento->cantidad * $signo,
            'precio_unitario' => self::obtenerCostoItem($movimiento->id_item, $movimiento->tipo_movimiento),
            'costo_total' => self::calcularCostoTotal($movimiento),
            'fecha_movimiento' => now(),
            'referencia_id' => $movimiento->id_produccion,
            'referencia_tipo' => 'produccion',
            'estado' => 'completado',
            'observaciones' => $movimiento->tipo_movimiento === self::TIPO_INGRESO 
                ? "Producción N° {$movimiento->id_produccion} - Producto terminado"
                : "Producción N° {$movimiento->id_produccion} - Insumo consumido"
        ]);
    }

    /**
     * Obtener costo del item según tipo
     */
    private static function obtenerCostoItem($idItem, $tipoMovimiento)
    {
        if ($tipoMovimiento === self::TIPO_INGRESO) {
            // Para productos: calcular costo de producción
            return self::calcularCostoProduccion($idItem);
        } else {
            // Para insumos: obtener precio de compra promedio
            return Item::find($idItem)->insumo->precio_compra ?? 0;
        }
    }

    /**
     * Calcular costo de producción de un producto
     */
    private static function calcularCostoProduccion($idItem)
    {
        // Obtener del movimiento más reciente o calcular
        return MovimientoInventario::where('id_item', $idItem)
            ->where('tipo_movimiento', 'produccion')
            ->latest('fecha_movimiento')
            ->value('precio_unitario') ?? 0;
    }

    /**
     * Calcular costo total del movimiento
     */
    private static function calcularCostoTotal($movimiento)
    {
        $costoUnitario = self::obtenerCostoItem($movimiento->id_item, $movimiento->tipo_movimiento);
        return $costoUnitario * $movimiento->cantidad;
    }

    /**
     * Relación con la producción
     */
    public function produccion(): BelongsTo
    {
        return $this->belongsTo(Produccion::class, 'id_produccion', 'id_produccion');
    }

    /**
     * Relación directa con almacen_item
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
     * Acceso rápido al almacén
     */
    public function almacen()
    {
        return $this->almacenItem->almacen();
    }

    /**
     * Acceso rápido al item
     */
    public function item()
    {
        return $this->almacenItem->item();
    }

    /**
     * Verificar si es un ingreso (producto terminado)
     */
    public function esIngreso(): bool
    {
        return $this->tipo_movimiento === self::TIPO_INGRESO;
    }

    /**
     * Verificar si es un egreso (insumo consumido)
     */
    public function esEgreso(): bool
    {
        return $this->tipo_movimiento === self::TIPO_EGRESO;
    }

    /**
     * Scope para filtrar por tipo de movimiento
     */
    public function scopeIngresos($query)
    {
        return $query->where('tipo_movimiento', self::TIPO_INGRESO);
    }

    /**
     * Scope para filtrar egresos
     */
    public function scopeEgresos($query)
    {
        return $query->where('tipo_movimiento', self::TIPO_EGRESO);
    }

    /**
     * Scope para una producción específica
     */
    public function scopeDeProduccion($query, $idProduccion)
    {
        return $query->where('id_produccion', $idProduccion);
    }
}