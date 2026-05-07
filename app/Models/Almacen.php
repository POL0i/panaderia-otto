<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;  // ✅ AGREGADO

class Almacen extends Model
{
    protected $table = 'almacenes';
    protected $primaryKey = 'id_almacen';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'ubicacion',
        'capacidad',
        'tipo_almacen',
    ];

    protected $casts = [
        'capacidad' => 'integer',
    ];

    // Constantes para tipos de almacén
    const TIPO_INSUMO = 'insumo';
    const TIPO_PRODUCTO = 'producto';
    const TIPO_MIXTO = 'mixto';

    // Lista de tipos disponibles
    public static function getTiposAlmacen()
    {
        return [
            self::TIPO_INSUMO => 'Insumo',
            self::TIPO_PRODUCTO => 'Producto',
            self::TIPO_MIXTO => 'Mixto',
        ];
    }

    /**
     * Verificar si el almacén es de tipo insumo
     */
    public function esInsumo()
    {
        return $this->tipo_almacen === self::TIPO_INSUMO;
    }

    /**
     * Verificar si el almacén es de tipo producto
     */
    public function esProducto()
    {
        return $this->tipo_almacen === self::TIPO_PRODUCTO;
    }

    /**
     * Verificar si el almacén es mixto
     */
    public function esMixto()
    {
        return $this->tipo_almacen === self::TIPO_MIXTO;
    }

    /**
     * Verificar si permite almacenar insumos
     */
    public function permiteInsumos()
    {
        return $this->esInsumo() || $this->esMixto();
    }

    /**
     * Verificar si permite almacenar productos
     */
    public function permiteProductos()
    {
        return $this->esProducto() || $this->esMixto();
    }

    /**
     * Get all almacen_items for this almacen.
     */
    public function almacenItems()  // ✅ Nombre correcto de la relación
    {
        return $this->hasMany(AlmacenItem::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Alias para mantener compatibilidad
     */
    public function items()
    {
        return $this->almacenItems();
    }

    /**
     * Get items filtered by type (insumo or producto)
     */
    public function itemsPorTipo($tipoItem)
    {
        return $this->almacenItems()
            ->whereHas('item', function($query) use ($tipoItem) {
                $query->where('tipo_item', $tipoItem);
            });
    }

    /**
     * Get only insumos in this almacen
     */
    public function insumos()
    {
        return $this->itemsPorTipo('insumo');
    }

    /**
     * Get only productos in this almacen
     */
    public function productosAlmacen()
    {
        return $this->itemsPorTipo('producto');
    }

    /**
     * Calcular porcentaje de capacidad utilizada
     */
    public function getPorcentajeCapacidadAttribute()
    {
        $stockActual = $this->almacenItems()->sum('stock');
        if ($this->capacidad <= 0) return 0;

        return round(($stockActual / $this->capacidad) * 100, 2);
    }

    /**
     * Get color based on capacity usage
     */
    public function getCapacidadColorAttribute()
    {
        $porcentaje = $this->porcentaje_capacidad;

        if ($porcentaje >= 90) return 'danger';
        if ($porcentaje >= 70) return 'warning';
        return 'success';
    }

    /**
     * Get all movimientos de produccion from this almacen.
     */
    public function produccionMovimientos()
    {
        return $this->hasMany(ProduccionItemAlmacen::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Get all traspasos from this almacen as origen.
     */
    public function traspasosComoOrigen()
    {
        return $this->hasMany(TraspasoAlmacenItem::class, 'id_almacen_origen', 'id_almacen');
    }

    /**
     * Get all traspasos to this almacen as destino.
     */
    public function traspasosComoDestino()
    {
        return $this->hasMany(TraspasoAlmacenItem::class, 'id_almacen_destino', 'id_almacen');
    }
}
