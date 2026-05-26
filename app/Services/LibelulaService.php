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

    public function registrarPago(NotaVenta $notaVenta, array $datosCliente = [])
{
    // 1. Cargar relaciones una sola vez
    $notaVenta->load('detalles.item', 'cliente');

    // 2. Verificar si ya existe transacción en BD con URL válida
    $transaccionExistente = TransaccionLibelula::where('nota_venta_id', $notaVenta->id_nota_venta)
        ->whereNotNull('id_transaccion_libelula')
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

    // 3. Preparar items (sin cargar relaciones nuevamente)
    $items = [];
    foreach ($notaVenta->detalles as $detalle) {
        $nombreProducto = $detalle->item->nombre ?? 'Producto';

        if (empty($nombreProducto) || $nombreProducto == 'Producto') {
            $producto = \App\Models\Producto::where('id_item', $detalle->id_item)->first();
            if ($producto) {
                $nombreProducto = $producto->nombre;
            }
        }

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

    // 4. Datos del cliente - PRIORIDAD: parámetro > nota_venta > default
    $nombreCliente = $datosCliente['nombre_cliente'] ?? ($notaVenta->cliente->nombre ?? 'Cliente');
    $apellidoCliente = $datosCliente['apellido_cliente'] ?? ($notaVenta->cliente->apellido ?? '');
    $emailCliente = $datosCliente['email_cliente'] ?? 'cliente@panaderiaotto.com';
    
    // ✅ Si hay usuario autenticado, usar su email (prioridad máxima)
    if (auth()->check()) {
        $usuario = auth()->user();
        $emailCliente = $usuario->correo;
        // Si el usuario es cliente, actualizar nombre también
        if ($usuario->cliente) {
            $nombreCliente = $usuario->cliente->nombre;
            $apellidoCliente = $usuario->cliente->apellido ?? '';
        } elseif ($usuario->empleado) {
            $nombreCliente = $usuario->empleado->nombre;
            $apellidoCliente = $usuario->empleado->apellido ?? '';
        }
    }
    
    // ✅ Si no hay usuario autenticado pero el cliente tiene usuario, obtener de allí
    if (($emailCliente === 'cliente@panaderiaotto.com') && $notaVenta->cliente) {
        $usuarioCliente = $notaVenta->cliente->usuarios()->first();
        if ($usuarioCliente) {
            $emailCliente = $usuarioCliente->correo;
        }
    }
    
    Log::info('Datos cliente para Libélula:', [
        'nombre' => $nombreCliente,
        'apellido' => $apellidoCliente,
        'email' => $emailCliente,
        'nota_venta_id' => $notaVenta->id_nota_venta
    ]);

    // 5. Identificador único
    $identificadorUnico = 'OTTO-' . $notaVenta->id_nota_venta . '-' . uniqid();

    // 6. Payload (usando $items, no $lineasDetalle)
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

    Log::info('Payload completo a Libélula', $payload);
    Log::info('Enviando a Libélula:', [
        'identificador' => $identificadorUnico,
        'nota_id' => $notaVenta->id_nota_venta,
        'productos' => array_column($items, 'concepto')
    ]);

    // 7. Llamada a la API
    try {
        $response = Http::timeout(30)->post("{$this->baseUrl}/deuda/registrar", $payload);
        $data = $response->json();

        Log::info('Respuesta Libélula:', [
            'status' => $response->status(),
            'error' => $data['error'] ?? 'N/A',
            'mensaje' => $data['mensaje'] ?? 'N/A',
            'tiene_url' => isset($data['url_pasarela_pagos']) ? 'SI' : 'NO'
        ]);

        $urlPasarela = $data['url_pasarela_pagos'] ?? null;

        if ($urlPasarela) {
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
