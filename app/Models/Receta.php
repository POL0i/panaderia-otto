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
    ];

    /**
     * Get all detalles de receta for this receta.
     */
    public function detalles()
    {
        return $this->hasMany(DetalleReceta::class, 'id_receta', 'id_receta');
    }

    /**
     * Get all producciones for this receta.
     */
    public function producciones()
    {
        return $this->hasMany(Produccion::class, 'id_receta', 'id_receta');
    }

    /**
     * Get all insumos through detalles.
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
