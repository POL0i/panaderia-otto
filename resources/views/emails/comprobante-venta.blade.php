<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Venta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #5D3A1A 0%, #8B4513 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            border: 1px solid #ddd;
            border-top: none;
            padding: 20px;
            border-radius: 0 0 10px 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #8B4513;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #777;
            font-size: 12px;
        }
        .badge {
            background-color: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">PANADERÍA OTTO</h1>
        <p style="margin: 5px 0 0;">Comprobante de Venta</p>
    </div>
    
    <div class="content">
        <div class="info-row">
            <div>
                <strong>N° Comprobante:</strong> 
                <span class="badge">{{ str_pad($nota->id_nota_venta, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div>
                <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($nota->fecha_venta)->format('d/m/Y H:i') }}
            </div>
        </div>
        
        <div class="info-row">
            <div>
                <strong>Cliente:</strong><br>
                {{ $nota->cliente->nombre ?? 'N/A' }}<br>
                <small>Tel: {{ $nota->cliente->telefono ?? 'N/A' }}</small>
            </div>
            <div>
                <strong>Atendido por:</strong><br>
                {{ $nota->empleado->nombre ?? 'N/A' }}<br>
                <small>ID: {{ $nota->empleado->id_empleado ?? '1' }}</small>
            </div>
        </div>
        
        <h3 style="margin-bottom: 10px;">Detalle de Productos</h3>
        <table>
            <thead>
                <tr>
                    <th>Cant.</th>
                    <th>Producto</th>
                    <th>Almacén</th>
                    <th class="text-right">P. Unit.</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($nota->detalles as $detalle)
                    @php 
                        $subtotal = $detalle->cantidad * $detalle->precio;
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>{{ $detalle->item->producto->nombre ?? 'Producto' }}</td>
                        <td>{{ $detalle->almacen->nombre ?? 'N/A' }}</td>
                        <td class="text-right">Bs. {{ number_format($detalle->precio, 2) }}</td>
                        <td class="text-right">Bs. {{ number_format($subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="total">
            Total: Bs. {{ number_format($nota->monto_total, 2) }}
        </div>
        
        <div style="margin-top: 30px;">
            <p>Gracias por su compra. ¡Esperamos verle pronto!</p>
        </div>
    </div>
    
    <div class="footer">
        <p>Panadería Otto - Av. Principal #123, Santa Cruz<br>
        Tel: (591) 123-45678 | Email: contacto@panaderiaotto.com</p>
        <p>Este es un correo generado automáticamente, por favor no responda a esta dirección.</p>
    </div>
</body>
</html>