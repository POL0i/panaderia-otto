<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    public $timestamps = true;

    protected $fillable = [
        'id_item',
        'id_cat_producto',
        'nombre',
        'precio',
    ];

    /**
     * Get the item for this producto.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    /**
     * Get the categoria for this producto.
     */
    public function categoria()
    {
        return $this->belongsTo(CategoriaProducto::class, 'id_cat_producto', 'id_cat_producto');
    }

    /**
     * Get all detalles de venta for this producto.
     */
    public function detallesVenta()
    {
        return $this->hasMany(DetalleVenta::class, 'id_producto', 'id_producto');
    }
}
