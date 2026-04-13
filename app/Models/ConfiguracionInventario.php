<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionInventario extends Model
{
    protected $table = 'configuracion_inventario';
    protected $primaryKey = 'id_config';
    public $timestamps = true;

    protected $fillable = [
        'metodo_valuacion_predeterminado',
        'automatizar_movimientos',
        'requerir_aprobacion',
    ];

    protected $casts = [
        'automatizar_movimientos' => 'boolean',
        'requerir_aprobacion' => 'boolean',
    ];

    /**
     * Obtener la configuración actual (singleton)
     */
    public static function obtener()
    {
        return self::first() ?? self::create([
            'metodo_valuacion_predeterminado' => 'PEPS',
            'automatizar_movimientos' => true,
            'requerir_aprobacion' => false,
        ]);
    }
}
