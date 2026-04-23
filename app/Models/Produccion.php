<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    protected $table = 'producciones';
    protected $primaryKey = 'id_produccion';
    protected $fillable = [
        'fecha_produccion', 'cantidad_producida', 'id_empleado_solicita',
        'id_empleado_autoriza', 'estado', 'fecha_solicitud', 'fecha_autorizacion', 'observaciones'
    ];

    protected $casts = [
        'fecha_produccion' => 'date',
        'fecha_solicitud' => 'datetime',
        'fecha_autorizacion' => 'datetime',
    ];

    public function empleadoSolicita()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado_solicita');
    }

    public function empleadoAutoriza()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado_autoriza');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleProduccion::class, 'id_produccion');
    }

   public function receta()
    {
        return $this->hasOneThrough(
            Receta::class,
            DetalleProduccion::class,
            'id_produccion',           // FK en DetalleProduccion
            'id_receta',               // PK en Receta (a través de detalle_receta)
            'id_produccion',           // Local key en Produccion
            'id_receta'                // FK en detalle_receta (necesita join)
        )->join('detalle_receta', 'detalle_receta.id_detalle_receta', '=', 'detalle_produccion.id_detalle_receta')
         ->whereNotNull('detalle_produccion.id_detalle_receta');
    }
}