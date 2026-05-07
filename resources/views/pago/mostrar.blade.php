<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar Pedido #{{ $notaVenta->id_nota_venta }} - Panadería Otto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #5D3A1A 0%, #8B4513 100%);
            padding: 20px;
        }
        .payment-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
        }
        .payment-card .logo-icon {
            font-size: 3rem;
            color: #D2B48C;
            margin-bottom: 15px;
        }
        .payment-card h3 {
            color: #5D3A1A;
            margin-bottom: 5px;
            font-weight: 700;
        }
        .payment-card .order-id {
            color: #8B4513;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 20px;
        }
        .amount-display {
            background: linear-gradient(135deg, #5D3A1A 0%, #8B4513 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .amount-display .label {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        .amount-display .amount {
            font-size: 2.5rem;
            font-weight: 700;
        }
        .amount-display .currency {
            font-size: 1rem;
            opacity: 0.8;
        }
        .qr-container {
            background: #FFF9F0;
            border: 2px dashed #D2B48C;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .qr-container h5 {
            color: #5D3A1A;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .qr-container img {
            max-width: 180px;
            border-radius: 10px;
            background: white;
            padding: 10px;
        }
        .qr-container .qr-hint {
            color: #8B4513;
            font-size: 0.85rem;
            margin-top: 10px;
        }
        .btn-pagar {
            background: linear-gradient(135deg, #5D3A1A 0%, #8B4513 100%);
            color: white;
            border: none;
            padding: 14px 35px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.05rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
            width: 100%;
            box-sizing: border-box;
        }
        .btn-pagar:hover {
            background: linear-gradient(135deg, #3E2510 0%, #5D3A1A 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            color: white;
        }
        .btn-pagar i {
            margin-right: 8px;
        }
        .btn-verify {
            background: transparent;
            color: #28a745;
            border: 2px solid #28a745;
            border-radius: 50px;
            padding: 10px 25px;
            cursor: pointer;
            font-weight: 500;
            margin-top: 15px;
            transition: all 0.3s ease;
            width: 100%;
        }
        .btn-verify:hover {
            background: #28a745;
            color: white;
        }
        .btn-verify:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .btn-back {
            background: transparent;
            color: #8B4513;
            border: 1px solid #8B4513;
            border-radius: 50px;
            padding: 8px 20px;
            cursor: pointer;
            font-size: 0.9rem;
            margin-top: 12px;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
        }
        .btn-back:hover {
            background: #8B4513;
            color: white;
        }
        .alert-error {
            background: #FFF3CD;
            border: 1px solid #FFC107;
            color: #856404;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
        }
        .alert-error a {
            color: #8B4513;
            font-weight: 600;
            text-decoration: underline;
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #8B4513;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 15px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @media (max-width: 576px) {
            .payment-card {
                padding: 25px;
            }
            .amount-display .amount {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="payment-card">
        {{-- Logo --}}
        <div class="logo-icon">
            <i class="fas fa-bread-slice"></i>
        </div>
        <h3>Panadería Otto</h3>
        <p class="order-id">Pedido #{{ $notaVenta->id_nota_venta }}</p>

        {{-- Monto a pagar --}}
        <div class="amount-display">
            <div class="label">Total a pagar</div>
            <div class="amount">Bs. {{ number_format($notaVenta->monto_total, 2) }}</div>
        </div>

        {{-- QR de pago (si existe) --}}
        @if(!empty($qr_url))
        <div class="qr-container">
            <h5><i class="fas fa-qrcode"></i> Código QR de pago</h5>
            <img src="{{ $qr_url }}" alt="QR de pago Libélula">
            <p class="qr-hint">📱 Escanea con tu aplicación bancaria</p>
        </div>
        @endif

        {{-- Botón de pago --}}
        @if(!empty($url_pasarela))
        <a href="{{ $url_pasarela }}" target="_blank" class="btn-pagar">
            <i class="fas fa-credit-card"></i> Ir a Pagar Ahora
        </a>
        <p class="text-muted small mt-2">Serás redirigido a la pasarela de pago segura</p>
        @else
        <div class="alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            No se pudo generar el enlace de pago.<br>
            <a href="{{ route('landing') }}">← Volver a la tienda</a>
        </div>
        @endif

        {{-- Botón verificar pago --}}
        <button class="btn-verify" onclick="verificarPago()" id="btnVerificar">
            <i class="fas fa-sync-alt"></i> Ya realicé el pago
        </button>

        {{-- Volver --}}
        <a href="{{ route('landing') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Volver a la tienda
        </a>
    </div>

    {{-- Font Awesome --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <script>
        const idVenta = {{ $notaVenta->id_nota_venta }};
        let verificando = false;

        function verificarPago() {
            if (verificando) return;
            verificando = true;

            const btn = document.getElementById('btnVerificar');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner" style="display:inline-block;width:18px;height:18px;border-width:2px;margin:0;"></span> Verificando...';

            fetch('/pago/verificar/' + idVenta)
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.pagado) {
                        window.location.href = '/pago/exito/' + idVenta;
                    } else {
                        alert('⏳ El pago aún no ha sido confirmado.\n\nIntenta nuevamente en unos segundos.');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-sync-alt"></i> Ya realicé el pago';
                        verificando = false;
                    }
                })
                .catch(function() {
                    alert('Error al verificar el pago. Intenta de nuevo.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-sync-alt"></i> Ya realicé el pago';
                    verificando = false;
                });
        }

        // Verificar automáticamente cada 8 segundos
        setInterval(function() {
            if (verificando) return;

            fetch('/pago/verificar/' + idVenta)
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.pagado) {
                        window.location.href = '/pago/exito/' + idVenta;
                    }
                })
                .catch(function() {});
        }, 8000);
    </script>
</body>
</html>
