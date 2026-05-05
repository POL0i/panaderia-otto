<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaVenta extends Model
{
    protected $table = 'notas_venta';
    protected $primaryKey = 'id_nota_venta';
    public $timestamps = true;

    protected $fillable = [
        'fecha_venta',
        'monto_total',
        'estado',
        'metodo_pago',
        'id_transaccion_libelula',
        'id_cliente',
        'id_empleado',
    ];

    protected $casts = [
        'fecha_venta' => 'date',
        'monto_total' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'id_nota_venta', 'id_nota_venta');
    }

    // ✅ Nueva relación con transacciones Libélula
    public function transaccionLibelula()
    {
        return $this->hasOne(TransaccionLibelula::class, 'nota_venta_id', 'id_nota_venta');
    }

    
    public function pagadoConLibelula()
    {
        return $this->metodo_pago === 'libelula' && $this->estado === 'completado';
    }
}