<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirigiendo a la pasarela de pagos - Panadería Otto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #5D3A1A 0%, #8B4513 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .payment-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 90%;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #8B4513;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .btn-pagar {
            background: #8B4513;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            margin-top: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-pagar:hover {
            background: #5D3A1A;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="payment-card">
        <i class="fas fa-credit-card fa-3x" style="color: #8B4513; margin-bottom: 20px;"></i>
        <h3 style="color: #5D3A1A;">Redirigiendo a la pasarela de pagos</h3>
        <p style="color: #666; margin: 20px 0;">
            Monto a pagar: <strong style="color: #8B4513; font-size: 24px;">Bs. {{ number_format($notaVenta->monto_total, 2) }}</strong>
        </p>
        <div class="spinner"></div>
        <p class="text-muted">Por favor espera un momento...</p>
        <button class="btn-pagar" onclick="irAPasarela()">
            <i class="fas fa-external-link-alt"></i> Ir a pagar ahora
        </button>
        <p class="text-muted small mt-3">
            Si no eres redirigido automáticamente, haz clic en el botón.
        </p>
    </div>

    <script>
        const urlPasarela = "{{ $url_pasarela }}";
        
        function irAPasarela() {
            if (urlPasarela) {
                window.location.href = urlPasarela;
            } else {
                alert('Error: No se pudo obtener la URL de pago');
            }
        }
        
        // Redirigir automáticamente después de 2 segundos
        if (urlPasarela) {
            setTimeout(irAPasarela, 2000);
        } else {
            document.querySelector('.payment-card').innerHTML = '<div class="alert alert-danger">Error: No se pudo generar el enlace de pago. <a href="{{ route("landing") }}">Volver a la tienda</a></div>';
        }
    </script>
</body>
</html>