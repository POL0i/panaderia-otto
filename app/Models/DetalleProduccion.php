<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleProduccion extends Model
{
    protected $table = 'detalle_produccion';
    public $timestamps = true;
    public $incrementing = false;

    protected $primaryKey = ['id_produccion', 'id_detalle_receta'];

    protected $fillable = [
        'id_produccion',
        'id_detalle_receta',
        'cantidad_usada',
    ];

    /**
     * Get the produccion for this detalle.
     */
    public function produccion()
    {
        return $this->belongsTo(Produccion::class, 'id_produccion', 'id_produccion');
    }

    /**
     * Get the detalle receta for this detalle.
     */
    public function detalleReceta()
    {
        return $this->belongsTo(DetalleReceta::class, 'id_detalle_receta', 'id_detalle_receta');
    }

    /**
     * Get the receta through detalle_receta.
     */
    public function receta()
    {
        return $this->hasOneThrough(
            Receta::class,
            DetalleReceta::class,
            'id_detalle_receta',
            'id_receta',
            'id_detalle_receta',
            'id_receta'
        );
    }

    /**
     * Get the insumo through detalle_receta.
     */
    public function insumo()
    {
        return $this->hasOneThrough(
            Insumo::class,
            DetalleReceta::class,
            'id_detalle_receta',
            'id_insumo',
            'id_detalle_receta',
            'id_insumo'
        );
    }
}
