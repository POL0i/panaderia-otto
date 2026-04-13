<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaProducto extends Model
{
    protected $table = 'categoria_producto';
    protected $primaryKey = 'id_cat_producto';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Get all productos for this categoria.
     */
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_cat_producto', 'id_cat_producto');
    }
}
