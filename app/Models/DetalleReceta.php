<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleReceta extends Model
{
    protected $table = 'detalle_receta';
    protected $primaryKey = 'id_detalle_receta';
    public $timestamps = true;

    protected $fillable = [
        'id_receta',
        'id_insumo',
        'cantidad_requerida',
    ];

    /**
     * Get the receta for this detalle.
     */
    public function receta()
    {
        return $this->belongsTo(Receta::class, 'id_receta', 'id_receta');
    }

    /**
     * Get the insumo for this detalle.
     */
    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'id_insumo', 'id_insumo');
    }

    /**
     * Get all detalles de produccion for this detalle receta.
     */
    public function detallesProduccion()
    {
        return $this->hasMany(DetalleProduccion::class, 'id_detalle_receta', 'id_detalle_receta');
    }
}
