<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Pagar Pedido #<?php echo e($notaVenta->id_nota_venta); ?> - Panadería Otto</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <?php
        $theme = session('theme', 'jovenes');
        $mode  = session('mode', 'auto');
    ?>

    <link rel="stylesheet" href="<?php echo e(asset("css/themes/{$theme}/light.css")); ?>">
    <link rel="stylesheet" href="<?php echo e(asset("css/themes/{$theme}/dark.css")); ?>">

    <style>
        * { font-family: 'Poppins', sans-serif; }

        body {
            background: linear-gradient(135deg, var(--color-primary-dark, #5D3A1A) 0%, var(--color-primary, #8B4513) 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .payment-card {
            max-width: 480px;
            margin: 0 auto;
            background: var(--bg-card, #fff);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .payment-header {
            background: linear-gradient(135deg, var(--color-primary-dark, #5D3A1A) 0%, var(--color-primary, #8B4513) 100%);
            color: white;
            padding: 15px 20px;
            text-align: center;
        }

        .payment-header i {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .payment-header h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
        }

        .payment-header p {
            font-size: 0.75rem;
            opacity: 0.9;
            margin: 0;
        }

        .order-info {
            background: var(--color-bg-lighter, #FFF5E6);
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            border-bottom: 1px solid var(--color-border, #eee);
        }

        .order-number {
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--color-primary-dark, #5D3A1A);
        }

        .order-total {
            font-weight: 700;
            font-size: 1rem;
            color: var(--color-primary, #8B4513);
        }

        .status-badge {
            background: #ffc107;
            color: #856404;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .payment-body {
            padding: 20px;
        }

        .qr-container {
            background: var(--color-bg-light, #FFF9F0);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            margin-bottom: 15px;
        }

        .qr-container img {
            max-width: 150px;
            border-radius: 10px;
            background: white;
            padding: 8px;
        }

        .qr-container h6 {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--color-primary-dark, #5D3A1A);
            margin-bottom: 8px;
        }

        .btn-pagar {
            background: linear-gradient(135deg, var(--color-primary, #8B4513) 0%, var(--color-primary-dark, #5D3A1A) 100%);
            color: white;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            text-align: center;
        }

        .btn-pagar:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--color-border, #ddd);
            color: var(--text-primary, #333);
            border-radius: 50px;
            padding: 8px 15px;
            font-size: 0.8rem;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            text-align: center;
        }

        .btn-outline:hover {
            background: var(--color-bg-lighter, #FFF5E6);
        }

        .alert-info-sm {
            background: var(--color-bg-lighter, #FFF5E6);
            border-radius: 10px;
            padding: 10px;
            font-size: 0.7rem;
            margin-top: 15px;
        }

        .divider {
            margin: 15px 0;
            border-top: 1px solid var(--color-border, #eee);
        }

        .spinner-sm {
            width: 30px;
            height: 30px;
            border: 3px solid var(--color-bg-light, #f3f3f3);
            border-top: 3px solid var(--color-primary, #8B4513);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 10px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body data-theme="<?php echo e($theme); ?>" data-mode="<?php echo e($mode); ?>">

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
    <div class="payment-header">
        <i class="fas fa-bread-slice"></i>
        <h3>Panadería Otto</h3>
        <p>Reintento de pago</p>
    </div>

    <div class="order-info">
        <span class="order-number"><i class="fas fa-receipt"></i> Pedido #<?php echo e($notaVenta->id_nota_venta); ?></span>
        <span class="order-total">Bs. <?php echo e(number_format($notaVenta->monto_total, 2)); ?></span>
    </div>

    <div class="payment-body">
        <div class="text-center mb-3">
            <span class="status-badge"><i class="fas fa-clock"></i> Pendiente de pago</span>
        </div>

        <?php if(!empty($qr_url)): ?>
        <div class="qr-container">
            <h6><i class="fas fa-qrcode"></i> Código QR de pago</h6>
            <img src="<?php echo e($qr_url); ?>" alt="QR de pago">
            <p class="small text-muted mt-2 mb-0">Escanea con tu app bancaria</p>
        </div>
        <?php endif; ?>

        <?php if(!empty($url_pasarela)): ?>
        <a href="<?php echo e($url_pasarela); ?>" target="_blank" class="btn-pagar">
            <i class="fas fa-credit-card"></i> Pagar en línea
        </a>
        <p class="small text-muted text-center mt-2 mb-0">
            Se abrirá la pasarela en otra ventana
        </p>
        <?php else: ?>
        <div class="text-center">
            <div class="spinner-sm"></div>
            <p class="small text-muted mt-2">Cargando información de pago...</p>
        </div>
        <?php endif; ?>

        <div class="divider"></div>

        <a href="<?php echo e(route('mis-pedidos')); ?>" class="btn-outline">
            <i class="fas fa-arrow-left"></i> Volver a mis pedidos
        </a>

        <div class="alert-info-sm">
            <i class="fas fa-info-circle"></i>
            <strong>¿Ya pagaste?</strong> Espera unos minutos y verifica en "Mis Pedidos".
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let verificaciones = 0;
    const maxVerificaciones = 12;
    
    function verificarPago() {
        verificaciones++;
        $.get('/pago/verificar/<?php echo e($notaVenta->id_nota_venta); ?>')
            .done(function(response) {
                if (response.pagado) {
                    window.location.href = '<?php echo e(route("pago.exito", $notaVenta->id_nota_venta)); ?>';
                } else if (verificaciones < maxVerificaciones) {
                    setTimeout(verificarPago, 10000);
                }
            });
    }
    
    setTimeout(verificarPago, 15000);
</script>
</body>
</html><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/cliente/reintentar-pago.blade.php ENDPATH**/ ?>