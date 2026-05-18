<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Producción</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        h1 { color: #8B4513; text-align: center; margin-bottom: 5px; }
        .fecha { text-align: center; color: #666; margin-bottom: 20px; }
        h2 { color: #5D3A1A; border-bottom: 2px solid #D2B48C; padding-bottom: 5px; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background: #8B4513; color: white; padding: 8px; font-size: 11px; text-align: left; }
        td { border: 1px solid #ddd; padding: 6px; font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .resumen { margin: 15px 0; padding: 10px; background: #f9f9f9; border-radius: 5px; }
        .resumen strong { color: #8B4513; }
        .barra { background: #D2B48C; height: 8px; border-radius: 4px; margin-top: 3px; }
        .barra-llena { background: #8B4513; height: 8px; border-radius: 4px; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <h1>PANADERÍA OTTO</h1>
    <p class="fecha">Reporte de Producción - <?php echo e(date('d/m/Y H:i')); ?></p>

    <div class="resumen">
        <strong>Total Producciones:</strong> <?php echo e(\App\Models\Produccion::where('estado', 'aprobado')->count()); ?> | 
        <strong>Unidades Producidas:</strong> <?php echo e(number_format(\App\Models\DetalleProduccion::where('tipo_movimiento', 'ingreso')->sum('cantidad'), 0)); ?>

    </div>

    <h2>Productos Más Creados</h2>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Órdenes</th>
                <th class="text-center">%</th>
            </tr>
        </thead>
        <tbody>
            <?php $maxProd = $productosMasCreados->max('total_producido') ?? 1; ?>
            <?php $__currentLoopData = $productosMasCreados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($p->nombre); ?></td>
                <td class="text-center"><?php echo e(number_format($p->total_producido, 0)); ?></td>
                <td class="text-center"><?php echo e($p->total_ordenes); ?></td>
                <td class="text-center">
                    <?php echo e(round(($p->total_producido / $maxProd) * 100)); ?>%
                    <div class="barra"><div class="barra-llena" style="width:<?php echo e(($p->total_producido/$maxProd)*100); ?>%"></div></div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <h2>Insumos Más Usados</h2>
    <table>
        <thead>
            <tr>
                <th>Insumo</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Órdenes</th>
                <th class="text-center">%</th>
            </tr>
        </thead>
        <tbody>
            <?php $maxIns = $insumosMasUsados->max('total_consumido') ?? 1; ?>
            <?php $__currentLoopData = $insumosMasUsados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($i->nombre); ?></td>
                <td class="text-center"><?php echo e(number_format($i->total_consumido, 2)); ?></td>
                <td class="text-center"><?php echo e($i->total_ordenes); ?></td>
                <td class="text-center">
                    <?php echo e(round(($i->total_consumido / $maxIns) * 100)); ?>%
                    <div class="barra"><div class="barra-llena" style="width:<?php echo e(($i->total_consumido/$maxIns)*100); ?>%"></div></div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="footer">
        Documento generado automáticamente - Panadería Otto &copy; <?php echo e(date('Y')); ?>

    </div>
</body>
</html><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/reportes/pdf/produccion.blade.php ENDPATH**/ ?>