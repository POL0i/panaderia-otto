<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pagar Pedido #{{ $notaVenta->id_nota_venta }} - Panadería Otto</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- ============================================ --}}
    {{-- TEMAS --}}
    {{-- ============================================ --}}
    @php
        $theme = session('theme', 'jovenes');
        $mode  = session('mode', 'auto');
    @endphp

    <link rel="stylesheet" href="{{ asset("css/themes/{$theme}/light.css") }}">
    <link rel="stylesheet" href="{{ asset("css/themes/{$theme}/dark.css") }}">

    <style>
        * { font-family: 'Poppins', sans-serif; }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--color-primary-dark, #5D3A1A) 0%, var(--color-primary, #8B4513) 100%);
            padding: 20px;
        }

        .payment-card {
            background: var(--bg-card, #fff);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
            color: var(--text-primary, #333);
        }

        .payment-card .logo-icon {
            font-size: 3rem;
            color: var(--color-accent, #D2B48C);
            margin-bottom: 15px;
        }

        .payment-card h3 {
            color: var(--color-primary-dark, #5D3A1A);
            margin-bottom: 5px;
            font-weight: 700;
        }

        .payment-card .order-id {
            color: var(--color-primary, #8B4513);
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .amount-display {
            background: linear-gradient(135deg, var(--color-primary-dark, #5D3A1A) 0%, var(--color-primary, #8B4513) 100%);
            color: #fff;
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

        .qr-container {
            background: var(--color-bg-light, #FFF9F0);
            border: 2px dashed var(--color-accent, #D2B48C);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .qr-container h5 {
            color: var(--color-primary-dark, #5D3A1A);
            font-weight: 600;
            margin-bottom: 15px;
        }

        .qr-container img {
            max-width: 180px;
            border-radius: 10px;
            background: var(--bg-card, #fff);
            padding: 10px;
        }

        .qr-container .qr-hint {
            color: var(--color-primary, #8B4513);
            font-size: 0.85rem;
            margin-top: 10px;
        }

        .btn-pagar {
            background: linear-gradient(135deg, var(--color-primary-dark, #5D3A1A) 0%, var(--color-primary, #8B4513) 100%);
            color: #fff;
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
            background: linear-gradient(135deg, var(--btn-hover-dark, #3E2510) 0%, var(--color-primary-dark, #5D3A1A) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            color: #fff;
        }

        .btn-pagar i { margin-right: 8px; }

        .btn-verify {
            background: transparent;
            color: var(--badge-success, #28a745);
            border: 2px solid var(--badge-success, #28a745);
            border-radius: 50px;
            padding: 10px 25px;
            cursor: pointer;
            font-weight: 500;
            margin-top: 15px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-verify:hover {
            background: var(--badge-success, #28a745);
            color: #fff;
        }

        .btn-verify:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-back {
            background: transparent;
            color: var(--color-primary, #8B4513);
            border: 1px solid var(--color-primary, #8B4513);
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
            background: var(--color-primary, #8B4513);
            color: #fff;
        }

        .alert-error {
            background: var(--color-bg-lighter, #FFF3CD);
            border: 1px solid var(--badge-warning, #FFC107);
            color: var(--text-primary, #856404);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
        }

        .alert-error a {
            color: var(--color-primary, #8B4513);
            font-weight: 600;
            text-decoration: underline;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid var(--color-bg-lighter, #f3f3f3);
            border-top: 4px solid var(--color-primary, #8B4513);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 15px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 576px) {
            .payment-card { padding: 25px; }
            .amount-display .amount { font-size: 2rem; }
        }
    </style>
</head>
<body data-theme="{{ $theme }}" data-mode="{{ $mode }}">

    {{-- Detección automática de modo --}}
    <script>
        (function() {
            const body = document.body;
            let currentMode = body.getAttribute('data-mode');
            if (currentMode === 'auto') {
                const hour = new Date().getHours();
                const isDay = hour >= 6 && hour < 18;
                currentMode = isDay ? 'light' : 'dark';
                body.setAttribute('data-mode', currentMode);
            }
        })();
    </script>

    <div class="payment-card">
        <div class="logo-icon">
            <i class="fas fa-bread-slice"></i>
        </div>
        <h3>Panadería Otto</h3>
        <p class="order-id">Pedido #{{ $notaVenta->id_nota_venta }}</p>

        <div class="amount-display">
            <div class="label">Total a pagar</div>
            <div class="amount">Bs. {{ number_format($notaVenta->monto_total, 2) }}</div>
        </div>

        {{-- QR de pago --}}
        @if(!empty($qr_url))
        <div class="qr-container">
            <h5><i class="fas fa-qrcode"></i> Escanea con tu app bancaria</h5>
            <img src="{{ $qr_url }}" alt="QR de pago Libélula">
            <p class="qr-hint">📱 Apunta la cámara al código QR</p>
        </div>
        @endif

        {{-- Botón de pago --}}
        @if(!empty($url_pasarela))
        <a href="{{ $url_pasarela }}" target="_blank" class="btn-pagar">
            <i class="fas fa-credit-card"></i> Pagar en ventana nueva
        </a>
        <p class="text-muted small mt-2">Se abrirá la pasarela de pago en otra pestaña. Al terminar, vuelve aquí y haz clic en "Ya pagué".</p>
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

        <a href="{{ route('landing') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Volver a la tienda
        </a>
    </div>

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
                .then(r => r.json())
                .then(data => {
                    if (data.pagado) {
                        window.location.href = '/pago/exito/' + idVenta;
                    } else {
                        alert('⏳ El pago aún no ha sido confirmado.\n\nIntenta nuevamente en unos segundos.');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-sync-alt"></i> Ya realicé el pago';
                        verificando = false;
                    }
                })
                .catch(() => {
                    alert('Error al verificar el pago. Intenta de nuevo.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-sync-alt"></i> Ya realicé el pago';
                    verificando = false;
                });
        }

        // Verificar automáticamente cada 8 segundos
        setInterval(() => {
            if (verificando) return;
            fetch('/pago/verificar/' + idVenta).then(r => r.json()).then(data => {
                if (data.pagado) window.location.href = '/pago/exito/' + idVenta;
            }).catch(() => {});
        }, 8000);
    </script>
</body>
</html>