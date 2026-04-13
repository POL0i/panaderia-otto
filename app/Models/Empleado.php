<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'id_empleado';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'direccion',
        'fecha_nac',
        'sueldo',
        'edad',
    ];

    protected $dates = [
        'fecha_nac',
    ];

    /**
     * Get all usuarios for this empleado.
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_empleado', 'id_empleado');
    }

    /**
     * Get all notas de venta for this empleado.
     */
    public function notasVenta()
    {
        return $this->hasMany(NotaVenta::class, 'id_empleado', 'id_empleado');
    }

    /**
     * Get all notas de compra for this empleado.
     */
    public function notasCompra()
    {
        return $this->hasMany(NotaCompra::class, 'id_empleado', 'id_empleado');
    }

    /**
     * Get all traspasos for this empleado.
     */
    public function traspasos()
    {
        return $this->hasMany(Traspaso::class, 'id_empleado', 'id_empleado');
    }

    /**
     * Get all producciones for this empleado.
     */
    public function producciones()
    {
        return $this->hasMany(Produccion::class, 'id_empleado', 'id_empleado');
    }
}
