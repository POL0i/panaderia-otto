<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalles_venta';
    protected $primaryKey = ['id_nota_venta', 'id_almacen', 'id_item']; // Clave compuesta
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id_nota_venta',
        'id_almacen',
        'id_item',
        'cantidad',
        'precio',
    ];

    // Relación con NotaVenta
    public function notaVenta()
    {
        return $this->belongsTo(NotaVenta::class, 'id_nota_venta', 'id_nota_venta');
    }

    // Relación con Almacen
    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    // Relación con Item (insumo/producto genérico)
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    // Relación con AlmacenItem (para obtener stock y producto)
    public function almacenItem()
    {
        return $this->hasOne(AlmacenItem::class, 'id_item', 'id_item')
                    ->where('id_almacen', $this->id_almacen);
    }

    // Acceso al nombre del producto a través de la cadena de relaciones
    public function getProductoNombreAttribute()
    {
        if ($this->item && $this->item->producto) {
            return $this->item->producto->nombre ?? $this->item->nombre;
        }
        return $this->item->nombre ?? 'N/A';
    }

    // Acceso al nombre del almacén
    public function getAlmacenNombreAttribute()
    {
        return $this->almacen->nombre ?? 'N/A';
    }
}