<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Mis Pedidos - Panadería Otto</title>

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

        .container-custom {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Header compacto */
        .header-compact {
            background: var(--bg-card, #fff);
            border-radius: 15px;
            padding: 15px 25px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-area i {
            font-size: 1.8rem;
            color: var(--color-accent, #D2B48C);
        }

        .logo-area h2 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--color-primary-dark, #5D3A1A);
            margin: 0;
        }

        .header-stats {
            display: flex;
            gap: 15px;
        }

        .stat-badge {
            background: var(--color-bg-lighter, #FFF5E6);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--color-primary, #8B4513);
        }

        /* Tarjeta de pedido compacta */
        .pedido-card {
            background: var(--bg-card, #fff);
            border-radius: 12px;
            margin-bottom: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.2s ease;
        }

        .pedido-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .pedido-header {
            background: var(--color-bg-lighter, #FFF5E6);
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
            border-bottom: 1px solid var(--color-border, #eee);
        }

        .pedido-id {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--color-primary-dark, #5D3A1A);
        }

        .pedido-fecha {
            font-size: 0.7rem;
            color: var(--text-muted, #666);
        }

        .estado-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .estado-pendiente { background: #fff3cd; color: #856404; }
        .estado-pagado { background: #d4edda; color: #155724; }
        .estado-cancelado { background: #f8d7da; color: #721c24; }

        .pedido-body {
            padding: 12px 15px;
        }

        .productos-compact {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
        }

        .producto-tag {
            background: var(--color-bg-light, #FFF9F0);
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.7rem;
            color: var(--text-primary, #333);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid var(--color-border, #eee);
        }

        .total-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--color-primary-dark, #5D3A1A);
        }

        .total-amount {
            font-size: 1rem;
            font-weight: 700;
            color: var(--color-primary, #8B4513);
        }

        .acciones {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-sm-custom {
            padding: 5px 12px;
            font-size: 0.7rem;
            border-radius: 20px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
        }

        .btn-pagar-sm {
            background: linear-gradient(135deg, var(--color-primary, #8B4513) 0%, var(--color-primary-dark, #5D3A1A) 100%);
            color: white;
            border: none;
        }

        .btn-pagar-sm:hover { transform: translateY(-1px); color: white; }

        .btn-cancelar-sm {
            background: #dc3545;
            color: white;
            border: none;
        }

        .btn-cancelar-sm:hover { background: #c82333; color: white; }

        .alert-empty {
            background: var(--bg-card, #fff);
            border-radius: 15px;
            padding: 40px;
            text-align: center;
        }

        .alert-empty i {
            font-size: 2.5rem;
            color: var(--color-accent, #D2B48C);
            margin-bottom: 10px;
        }

        .btn-tienda {
            background: linear-gradient(135deg, var(--color-primary, #8B4513) 0%, var(--color-primary-dark, #5D3A1A) 100%);
            color: white;
            border-radius: 25px;
            padding: 8px 20px;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-block;
        }

        @media (max-width: 600px) {
            .header-compact { flex-direction: column; text-align: center; gap: 10px; }
            .pedido-header { flex-direction: column; text-align: center; }
            .acciones { justify-content: center; }
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

<div class="container-custom">
    
    <div class="header-compact">
        <div class="logo-area">
            <i class="fas fa-bread-slice"></i>
            <h2>Mis Pedidos</h2>
        </div>
        <div class="header-stats">
            <span class="stat-badge"><i class="fas fa-shopping-bag"></i> <?php echo e($pedidos->count()); ?> pedidos</span>
            <a href="<?php echo e(route('landing')); ?>" class="stat-badge" style="text-decoration: none;">
                <i class="fas fa-store"></i> Tienda
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
            <small><i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?></small>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
            <small><i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?></small>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($pedidos->isEmpty()): ?>
        <div class="alert-empty">
            <i class="fas fa-inbox"></i>
            <h5>No tienes pedidos pendientes</h5>
            <p class="text-muted small">Realiza tu primera compra para ver tus pedidos aquí.</p>
            <a href="<?php echo e(route('landing')); ?>" class="btn-tienda">
                <i class="fas fa-shopping-cart"></i> Ir a la tienda
            </a>
        </div>
    <?php else: ?>
        <?php $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $transaccion = $pedido->transaccionLibelula;
                $pagado = $transaccion && $transaccion->estado === 'pagado';
                $cancelado = $pedido->estado === 'cancelado';
            ?>
            <div class="pedido-card">
                <div class="pedido-header">
                    <div>
                        <span class="pedido-id">#<?php echo e($pedido->id_nota_venta); ?></span>
                        <span class="pedido-fecha ms-2">
                            <i class="far fa-calendar-alt"></i> <?php echo e(\Carbon\Carbon::parse($pedido->fecha_venta)->format('d/m/Y H:i')); ?>

                        </span>
                    </div>
                    <?php if($pagado): ?>
                        <span class="estado-badge estado-pagado"><i class="fas fa-check-circle"></i> Pagado</span>
                    <?php elseif($cancelado): ?>
                        <span class="estado-badge estado-cancelado"><i class="fas fa-times-circle"></i> Cancelado</span>
                    <?php else: ?>
                        <span class="estado-badge estado-pendiente"><i class="fas fa-clock"></i> Pendiente</span>
                    <?php endif; ?>
                </div>

                <div class="pedido-body">
                    <div class="productos-compact">
                        <?php $__currentLoopData = $pedido->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="producto-tag"><?php echo e($detalle->cantidad); ?>x <?php echo e($detalle->item->nombre ?? 'Producto'); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="total-row">
                        <span class="total-label">Total</span>
                        <span class="total-amount">Bs. <?php echo e(number_format($pedido->monto_total, 2)); ?></span>
                    </div>

                    <?php if(!$pagado && !$cancelado): ?>
                        <div class="acciones">
                            <a href="<?php echo e(route('pago.reintentar', $pedido->id_nota_venta)); ?>" class="btn-sm-custom btn-pagar-sm">
                                <i class="fas fa-credit-card"></i> Pagar
                            </a>
                            <form action="<?php echo e(route('mis-pedidos.cancelar', $pedido->id_nota_venta)); ?>" 
                                  method="POST" 
                                  onsubmit="return confirm('¿Cancelar este pedido?');"
                                  style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-sm-custom btn-cancelar-sm">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                            </form>
                        </div>
                    <?php elseif($pagado): ?>
                        <div class="text-success small mt-2"><i class="fas fa-check-circle"></i> Pago confirmado</div>
                    <?php elseif($cancelado): ?>
                        <div class="text-danger small mt-2"><i class="fas fa-info-circle"></i> Pedido cancelado</div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/mis-pedidos.blade.php ENDPATH**/ ?>