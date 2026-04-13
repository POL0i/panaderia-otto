<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Traspaso extends Model
{
    protected $table = 'traspasos';
    protected $primaryKey = 'id_traspaso';
    public $timestamps = true;

    protected $fillable = [
        'fecha_traspaso',
        'descripcion',
        'id_empleado',
    ];

    protected $dates = [
        'fecha_traspaso',
    ];

    /**
     * Get the empleado for this traspaso.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    /**
     * Get all traspasos almacen items for this traspaso.
     */
    public function detalles()
    {
        return $this->hasMany(TraspasoAlmacenItem::class, 'id_traspaso', 'id_traspaso');
    }
}
