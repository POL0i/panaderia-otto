<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    protected $table = 'recetas';
    protected $primaryKey = 'id_receta';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'id_producto',
        'cantidad_requerida',
    ];

    /**
     * Relación con el producto final
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    /**
     * Relación con los detalles de la receta (insumos)
     */
    public function detalles()
    {
        return $this->hasMany(DetalleReceta::class, 'id_receta', 'id_receta');
    }

    /**
     * Obtener las producciones que usan esta receta
     * La relación es a través de detalle_receta -> detalle_produccion
     */
    public function producciones()
    {
        return $this->hasManyThrough(
            Produccion::class,          // Modelo final
            DetalleReceta::class,       // Modelo intermedio
            'id_receta',                // Foreign key en DetalleReceta
            'id_produccion',            // Foreign key en Produccion (no se usa directo)
            'id_receta',                // Local key en Receta
            'id_detalle_receta'         // Local key en DetalleReceta
        )->join('detalle_produccion', 'detalle_receta.id_detalle_receta', '=', 'detalle_produccion.id_detalle_receta')
          ->select('producciones.*')
          ->distinct();
    }

    /**
     * Contar producciones asociadas (método alternativo)
     */
    public function getProduccionesCountAttribute()
    {
        return \App\Models\DetalleProduccion::whereIn('id_detalle_receta', 
            $this->detalles()->pluck('id_detalle_receta')
        )->distinct('id_produccion')->count('id_produccion');
    }

    /**
     * Verificar si la receta tiene producciones asociadas
     */
    public function tieneProducciones()
    {
        return $this->producciones_count > 0;
    }
}