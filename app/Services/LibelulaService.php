<?php

namespace App\Services;

use App\Models\NotaVenta;
use App\Models\TransaccionLibelula;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LibelulaService
{
    protected $appkey;
    protected $baseUrl;
    protected $callbackUrl;

    public function __construct()
    {
        $this->appkey = config('services.libelula.appkey');
        $this->baseUrl = config('services.libelula.base_url', 'https://api.libelula.bo/rest');
        $this->callbackUrl = config('services.libelula.callback_url');
    }

    /**
     * Registrar una nota de venta en Libélula
     */
   public function registrarPago(NotaVenta $notaVenta)
{
    // Verificar si ya existe una transacción
    $transaccionExistente = TransaccionLibelula::where('nota_venta_id', $notaVenta->id_nota_venta)->first();
    
    if ($transaccionExistente && $transaccionExistente->url_pasarela) {
        \Log::info('Usando transacción existente en Service', ['nota_venta_id' => $notaVenta->id_nota_venta]);
        return [
            'success' => true,
            'transaccion' => $transaccionExistente,
            'qr_url' => $transaccionExistente->qr_url,
            'url_pasarela' => $transaccionExistente->url_pasarela,
            'id_transaccion' => $transaccionExistente->id_transaccion_libelula,
            'codigo_recaudacion' => $transaccionExistente->codigo_recaudacion
        ];
    }

    // Obtener los items de la nota de venta
    $items = [];
    foreach ($notaVenta->detalles as $detalle) {
        $items[] = [
            "concepto" => $detalle->item->producto->nombre ?? 'Producto',
            "cantidad" => $detalle->cantidad,
            "costo_unitario" => $detalle->precio,
            "descuento_unitario" => 0
        ];
    }
    
    // Si no hay detalles (recién creada), usar los datos del carrito
    if (empty($items)) {
        $items = [
            [
                "concepto" => "Producto Panadería Otto",
                "cantidad" => 1,
                "costo_unitario" => $notaVenta->monto_total,
                "descuento_unitario" => 0
            ]
        ];
    }

    $payload = [
        "appkey" => $this->appkey,
        "email_cliente" => $notaVenta->cliente->correo ?? 'cliente@ejemplo.com',
        "identificador" => (string) $notaVenta->id_nota_venta,
        "callback_url" => $this->callbackUrl,
        "url_retorno" => route('landing'),
        "descripcion" => "Nota de Venta #{$notaVenta->id_nota_venta} - Panadería Otto",
        "nombre_cliente" => $notaVenta->cliente->nombre ?? 'Cliente',
        "apellido_cliente" => $notaVenta->cliente->apellido ?? '',
        "ci" => $notaVenta->cliente->ci ?? '',
        "moneda" => "BOB",
        "lineas_detalle_deuda" => $items
    ];

    try {
        $response = Http::post("{$this->baseUrl}/deuda/registrar", $payload);
        $data = $response->json();

        if (!$response->successful() || ($data['error'] ?? true)) {
            // Si el error es por deuda existente, intentar recuperar la URL
            if (isset($data['error']) && $data['error'] == 2 && isset($data['url_pasarela_pagos']) && $data['url_pasarela_pagos']) {
                \Log::info('Deuda ya existe, usando URL existente', ['url' => $data['url_pasarela_pagos']]);
                
                // Crear/actualizar transacción con la URL existente
                $transaccion = TransaccionLibelula::updateOrCreate(
                    ['nota_venta_id' => $notaVenta->id_nota_venta],
                    [
                        'identificador' => (string) $notaVenta->id_nota_venta,
                        'id_transaccion_libelula' => $data['id_transaccion'] ?? null,
                        'codigo_recaudacion' => $data['codigo_recaudacion'] ?? null,
                        'monto' => $notaVenta->monto_total,
                        'qr_url' => $data['qr_simple_url'] ?? null,
                        'url_pasarela' => $data['url_pasarela_pagos'],
                        'respuesta_api' => $data,
                        'estado' => 'pendiente'
                    ]
                );
                
                return [
                    'success' => true,
                    'transaccion' => $transaccion,
                    'qr_url' => $data['qr_simple_url'] ?? null,
                    'url_pasarela' => $data['url_pasarela_pagos'],
                    'id_transaccion' => $data['id_transaccion'],
                    'codigo_recaudacion' => $data['codigo_recaudacion'] ?? null
                ];
            }
            
            \Log::error('Libélula Error:', $data);
            return [
                'success' => false,
                'message' => $data['mensaje'] ?? 'Error al registrar pago'
            ];
        }

        // Guardar transacción exitosa
        $transaccion = TransaccionLibelula::create([
            'nota_venta_id' => $notaVenta->id_nota_venta,
            'identificador' => (string) $notaVenta->id_nota_venta,
            'id_transaccion_libelula' => $data['id_transaccion'],
            'codigo_recaudacion' => $data['codigo_recaudacion'] ?? null,
            'monto' => $notaVenta->monto_total,
            'qr_url' => $data['qr_simple_url'] ?? null,
            'url_pasarela' => $data['url_pasarela_pagos'],
            'respuesta_api' => $data,
            'estado' => 'pendiente'
        ]);

        // Actualizar nota_venta
        $notaVenta->update([
            'metodo_pago' => 'libelula',
            'id_transaccion_libelula' => $data['id_transaccion']
        ]);

        return [
            'success' => true,
            'transaccion' => $transaccion,
            'qr_url' => $data['qr_simple_url'] ?? null,
            'url_pasarela' => $data['url_pasarela_pagos'],
            'id_transaccion' => $data['id_transaccion'],
            'codigo_recaudacion' => $data['codigo_recaudacion'] ?? null
        ];

    } catch (\Exception $e) {
        \Log::error('Libélula Exception: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Error de conexión con la pasarela de pagos: ' . $e->getMessage()
        ];
    }
}


    /**
     * Consultar estado de un pago
     */
    public function consultarPago($identificador)
    {
        $payload = [
            "appkey" => $this->appkey,
            "identificador" => (string) $identificador
        ];

        try {
            $response = Http::post("{$this->baseUrl}/deuda/consultar_deudas/por_identificador", $payload);
            $data = $response->json();

            if ($response->successful() && ($data['error'] ?? 1) == 0) {
                $datos = $data['datos'] ?? [];
                return [
                    'success' => true,
                    'pagado' => $datos['pagado'] ?? false,
                    'fecha_pago' => $datos['fecha_pago'] ?? null,
                    'forma_pago' => $datos['forma_pago'] ?? null,
                    'monto' => $datos['valor_total'] ?? 0
                ];
            }

            return [
                'success' => false,
                'message' => $data['mensaje'] ?? 'Error al consultar pago'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error de conexión'
            ];
        }
    }
}