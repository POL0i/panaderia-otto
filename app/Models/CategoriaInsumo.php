<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaInsumo extends Model
{
    protected $table = 'categoria_insumo';
    protected $primaryKey = 'id_cat_insumo';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Get all insumos for this categoria.
     */
    public function insumos()
    {
        return $this->hasMany(Insumo::class, 'id_cat_insumo', 'id_cat_insumo');
    }
}
