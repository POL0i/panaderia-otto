<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $table = 'insumos';
    protected $primaryKey = 'id_insumo';
    public $timestamps = true;

    protected $fillable = [
        'id_item',
        'id_cat_insumo',
        'nombre',
        'precio_compra',
    ];

    /**
     * Get the item for this insumo.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    /**
     * Get the categoria for this insumo.
     */
    public function categoria()
    {
        return $this->belongsTo(CategoriaInsumo::class, 'id_cat_insumo', 'id_cat_insumo');
    }

    /**
     * Get all detalles de receta for this insumo.
     */
    public function detallesReceta()
    {
        return $this->hasMany(DetalleReceta::class, 'id_insumo', 'id_insumo');
    }

    /**
     * Get all detalles de compra for this insumo.
     */
    public function detallesCompra()
    {
        return $this->hasMany(DetalleCompra::class, 'id_insumo', 'id_insumo');
    }
}
