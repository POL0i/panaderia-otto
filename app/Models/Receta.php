<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    protected $table = 'recetas';
    protected $primaryKey = 'id_receta';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'cantidad_requerida',
        'id_producto',          // ← nuevo
    ];

    /**
     * Producto que se obtiene al seguir esta receta.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    /**
     * Detalles de la receta (ingredientes).
     */
    public function detalles()
    {
        return $this->hasMany(DetalleReceta::class, 'id_receta', 'id_receta');
    }

    /**
     * Producciones realizadas con esta receta.
     */
    public function producciones()
    {
        return $this->hasMany(Produccion::class, 'id_receta', 'id_receta');
    }

    /**
     * Insumos a través de los detalles.
     */
    public function insumos()
    {
        return $this->hasManyThrough(
            Insumo::class,
            DetalleReceta::class,
            'id_receta',
            'id_insumo',
            'id_receta',
            'id_insumo'
        );
    }
}