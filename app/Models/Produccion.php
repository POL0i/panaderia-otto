<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    protected $table = 'producciones';
    protected $primaryKey = 'id_produccion';
    public $timestamps = true;

    protected $fillable = [
        'fecha_produccion',
        'cantidad_producida',
        'id_receta',
        'id_empleado',
    ];

    protected $dates = [
        'fecha_produccion',
    ];

    /**
     * Get the receta for this produccion.
     */
    public function receta()
    {
        return $this->belongsTo(Receta::class, 'id_receta', 'id_receta');
    }

    /**
     * Get the empleado for this produccion.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    /**
     * Get all detalles de produccion for this produccion.
     */
    public function detalles()
    {
        return $this->hasMany(DetalleProduccion::class, 'id_produccion', 'id_produccion');
    }

    /**
     * Get all movimientos de inventario for this produccion.
     */
    public function movimientos()
    {
        return $this->hasMany(ProduccionItemAlmacen::class, 'id_produccion', 'id_produccion');
    }
}
