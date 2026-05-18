<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Comercial</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { color: #8B4513; text-align: center; }
        .resumen { margin: 20px 0; }
        .resumen div { display: inline-block; width: 30%; text-align: center; padding: 10px; }
        .verde { color: #28a745; font-size: 20px; }
        .rojo { color: #dc3545; font-size: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #8B4513; color: white; padding: 6px; }
        td { border: 1px solid #ddd; padding: 5px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>PANADERÍA OTTO</h1>
    <p style="text-align:center;">Reporte Comercial del <?php echo e($inicio); ?> al <?php echo e($fin); ?></p>

    <div class="resumen">
        <div><strong>Total Ventas</strong><br><span class="verde">Bs. <?php echo e(number_format($totalVentas, 2)); ?></span></div>
        <div><strong>Total Compras</strong><br><span class="rojo">Bs. <?php echo e(number_format($totalCompras, 2)); ?></span></div>
        <div><strong>Diferencia</strong><br><span>Bs. <?php echo e(number_format($totalVentas - $totalCompras, 2)); ?></span></div>
    </div>

    <h2>Ventas</h2>
    <table>
        <thead><tr><th>#</th><th>Fecha</th><th>Cliente</th><th class="text-right">Monto</th></tr></thead>
        <tbody>
            <?php $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($v->id_nota_venta); ?></td>
                <td><?php echo e($v->fecha_venta->format('d/m/Y')); ?></td>
                <td><?php echo e($v->cliente->nombre ?? 'N/A'); ?></td>
                <td class="text-right">Bs. <?php echo e(number_format($v->monto_total, 2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/reportes/pdf/comercial.blade.php ENDPATH**/ ?>