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

    public function registrarPago(NotaVenta $notaVenta)
    {
        // ✅ Generar identificador ÚNICO cada vez
        $identificadorUnico = 'OTTO-' . $notaVenta->id_nota_venta . '-' . time();

        // 1. Verificar si ya existe transacción en BD con URL válida
        $transaccionExistente = TransaccionLibelula::where('nota_venta_id', $notaVenta->id_nota_venta)
            ->whereNotNull('url_pasarela')
            ->latest()
            ->first();

        if ($transaccionExistente && $transaccionExistente->url_pasarela) {
            Log::info('Transacción existente en BD', ['id' => $transaccionExistente->id]);
            return [
                'success' => true,
                'qr_url' => $transaccionExistente->qr_url,
                'url_pasarela' => $transaccionExistente->url_pasarela,
                'id_transaccion' => $transaccionExistente->id_transaccion_libelula,
                'codigo_recaudacion' => $transaccionExistente->codigo_recaudacion
            ];
        }

        // 2. Preparar items con el nombre REAL del producto
        $items = [];
        // Cargar relaciones necesarias
$notaVenta->load('detalles.item');

$items = [];
foreach ($notaVenta->detalles as $detalle) {
    // Obtener nombre del item directamente
    $nombreProducto = $detalle->item->nombre ?? 'Producto';

    // Si no tiene item, buscar en productos por id_item
    if (empty($nombreProducto) || $nombreProducto == 'Producto') {
        $producto = \App\Models\Producto::where('id_item', $detalle->id_item)->first();
        if ($producto) {
            $nombreProducto = $producto->nombre;
        }
    }

    // NUNCA enviar null o vacío
    if (empty($nombreProducto)) {
        $nombreProducto = 'Producto Panadería Otto';
    }

    $items[] = [
        "concepto" => $nombreProducto,
        "cantidad" => (int) $detalle->cantidad,
        "costo_unitario" => (float) $detalle->precio,
        "descuento_unitario" => 0
    ];
}

        if (empty($items)) {
            $items[] = [
                "concepto" => "Pedido Panadería Otto",
                "cantidad" => 1,
                "costo_unitario" => (float) $notaVenta->monto_total,
                "descuento_unitario" => 0
            ];
        }

        // 3. Datos del cliente
        $nombreCliente = $notaVenta->cliente->nombre ?? 'Cliente';
        $apellidoCliente = $notaVenta->cliente->apellido ?? '';
        $emailCliente = 'cliente@panaderiaotto.com';

        if ($notaVenta->cliente && $notaVenta->cliente->usuarios()->exists()) {
            $emailCliente = $notaVenta->cliente->usuarios()->first()->correo ?? $emailCliente;
        }

        // 4. Payload con identificador ÚNICO
        $payload = [
            "appkey" => $this->appkey,
            "email_cliente" => $emailCliente,
            "identificador" => $identificadorUnico,
            "callback_url" => $this->callbackUrl,
            "url_retorno" => route('landing'),
            "descripcion" => "Pedido #{$notaVenta->id_nota_venta} - Panadería Otto",
            "nombre_cliente" => $nombreCliente,
            "apellido_cliente" => $apellidoCliente,
            "ci" => "0",
            "moneda" => "BOB",
            "lineas_detalle_deuda" => $items
        ];

        Log::info('Enviando a Libélula:', [
            'identificador' => $identificadorUnico,
            'nota_id' => $notaVenta->id_nota_venta,
            'productos' => array_column($items, 'concepto')
        ]);

        // 5. Llamada a la API
        try {
            $response = Http::timeout(30)->post("{$this->baseUrl}/deuda/registrar", $payload);
            $data = $response->json();

            Log::info('Respuesta Libélula:', [
                'status' => $response->status(),
                'error' => $data['error'] ?? 'N/A',
                'mensaje' => $data['mensaje'] ?? 'N/A',
                'tiene_url' => isset($data['url_pasarela_pagos']) ? 'SI' : 'NO'
            ]);

            // 6. Si hay URL de pago, GUARDAR Y RETORNAR ÉXITO
            $urlPasarela = $data['url_pasarela_pagos'] ?? null;

            if ($urlPasarela) {
                //  Usar updateOrCreate para no duplicar
               $transaccion = TransaccionLibelula::create([
    'nota_venta_id' => $notaVenta->id_nota_venta,
    'identificador' => $identificadorUnico,
    'id_transaccion_libelula' => $data['id_transaccion'] ?? null,
    'codigo_recaudacion' => $data['codigo_recaudacion'] ?? null,
    'monto' => $notaVenta->monto_total,
    'qr_url' => $data['qr_simple_url'] ?? null,
    'url_pasarela' => $urlPasarela,
    'respuesta_api' => $data,
    'estado' => 'pendiente'
]);

                Log::info('Transacción guardada/actualizada', [
                    'id' => $transaccion->id,
                    'identificador' => $identificadorUnico,
                    'url' => $urlPasarela
                ]);

                return [
                    'success' => true,
                    'qr_url' => $data['qr_simple_url'] ?? null,
                    'url_pasarela' => $urlPasarela,
                    'id_transaccion' => $data['id_transaccion'] ?? null,
                    'codigo_recaudacion' => $data['codigo_recaudacion'] ?? null,
                    'message' => 'Pago registrado correctamente'
                ];
            }

            // 7. Si no hay URL, error real
            Log::error('Libélula no devolvió URL de pago', ['respuesta' => $data]);
            return [
                'success' => false,
                'message' => $data['mensaje'] ?? 'Error al registrar el pago en Libélula'
            ];

        } catch (\Exception $e) {
            Log::error('Excepción Libélula: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error de conexión: ' . $e->getMessage()
            ];
        }
    }

    public function consultarPago($identificador)
    {
        $payload = [
            "appkey" => $this->appkey,
            "identificador" => (string) $identificador
        ];

        try {
            $response = Http::timeout(30)->post("{$this->baseUrl}/deuda/consultar_deudas/por_identificador", $payload);
            $data = $response->json();

            Log::info('Consulta pago Libélula:', ['identificador' => $identificador, 'data' => $data]);

            if (($data['error'] ?? 1) == 0) {
                $datos = $data['datos'] ?? [];
                return [
                    'success' => true,
                    'pagado' => $datos['pagado'] ?? false,
                    'fecha_pago' => $datos['fecha_pago'] ?? null,
                    'monto' => $datos['valor_total'] ?? 0
                ];
            }

            return ['success' => false, 'message' => $data['mensaje'] ?? 'Error al consultar'];

        } catch (\Exception $e) {
            Log::error('Error consulta Libélula: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error de conexión'];
        }
    }
}
