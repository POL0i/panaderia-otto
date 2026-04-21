<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaCompra extends Model
{
    protected $table = 'notas_compra';
    protected $primaryKey = 'id_nota_compra';
    public $timestamps = true;

    protected $fillable = [
        'fecha_compra',
        'monto_total',
        'estado',
        'id_empleado',
        'id_proveedor',
    ];

    protected $dates = [
        'fecha_compra',
    ];

    protected $casts = [
        'fecha_compra' => 'datetime',  // <-- Agregar esta línea
        'monto_total' => 'decimal:2',
    ];

    /**
     * Get the empleado for this nota de compra.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    /**
     * Get the proveedor for this nota de compra.
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    /**
     * Get all detalles de compra for this nota.
     */
    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'id_nota_compra', 'id_nota_compra');
    }

    /**
     * Get all insumos through detalles.
     */
    public function insumos()
    {
        return $this->hasManyThrough(
            Insumo::class,
            DetalleCompra::class,
            'id_nota_compra',
            'id_insumo',
            'id_nota_compra',
            'id_insumo'
        );
    }
}
