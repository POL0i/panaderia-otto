<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaccionLibelula extends Model
{
    protected $table = 'transacciones_libelula';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nota_venta_id',
        'identificador',
        'id_transaccion_libelula',
        'codigo_recaudacion',
        'monto',
        'estado',
        'qr_url',
        'url_pasarela',
        'respuesta_api',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'respuesta_api' => 'array',
    ];

    // Relación con nota_venta
    public function notaVenta()
    {
        return $this->belongsTo(NotaVenta::class, 'nota_venta_id', 'id_nota_venta');
    }

    // Métodos de ayuda
    public function estaPagado()
    {
        return $this->estado === 'pagado';
    }

    public function estaPendiente()
    {
        return $this->estado === 'pendiente';
    }
}
