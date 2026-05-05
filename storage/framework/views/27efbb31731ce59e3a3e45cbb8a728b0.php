

<?php $__env->startSection('title', 'Detalle de Movimiento'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1 class="h3 mb-0">
                <i class="fas fa-arrows-alt-v"></i> 
                <?php echo e(ucfirst($tipo)); ?> #<?php echo e($referenciaId); ?>

            </h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('movimientos.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3><?php echo e($movimientos->count()); ?></h3>
                    <p>Movimientos</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3><?php echo e($movimientos->sum(function($m) { return $m->cantidad > 0 ? $m->cantidad : 0; })); ?></h3>
                    <p>Total Ingresos</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3><?php echo e($movimientos->sum(function($m) { return $m->cantidad < 0 ? abs($m->cantidad) : 0; })); ?></h3>
                    <p>Total Egresos</p>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($encabezado): ?>
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información General</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Tipo:</strong> <?php echo e(ucfirst($tipo)); ?></p>
                    <p><strong>Referencia:</strong> #<?php echo e($referenciaId); ?></p>
                    <p><strong>Fecha:</strong> <?php echo e($encabezado->fecha_movimiento->format('d/m/Y H:i')); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Estado:</strong> 
                        <span class="badge badge-<?php echo e($encabezado->estado == 'completado' ? 'success' : 'warning'); ?>">
                            <?php echo e(ucfirst($encabezado->estado)); ?>

                        </span>
                    </p>
                    <p><strong>Observaciones:</strong> <?php echo e($encabezado->observaciones ?? '-'); ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Detalle de Movimientos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tipo</th>
                            <th>Almacén</th>
                            <th>Item</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Costo Total</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $movimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $mov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td>
                                <?php switch($mov->tipo_movimiento):
                                    case ('ingreso'): ?>
                                        <span class="badge badge-success">📥 Ingreso</span>
                                        <?php break; ?>
                                    <?php case ('egreso'): ?>
                                        <span class="badge badge-danger">📤 Egreso</span>
                                        <?php break; ?>
                                    <?php case ('traspaso_origen'): ?>
                                        <span class="badge badge-warning">🔄 Traspaso (Salida)</span>
                                        <?php break; ?>
                                    <?php case ('traspaso_destino'): ?>
                                        <span class="badge badge-info">🔄 Traspaso (Entrada)</span>
                                        <?php break; ?>
                                    <?php default: ?>
                                        <span class="badge badge-secondary"><?php echo e($mov->tipo_movimiento); ?></span>
                                <?php endswitch; ?>
                            </td>
                            <td><?php echo e(\App\Models\Almacen::find($mov->id_almacen)->nombre ?? 'N/A'); ?></td>
                            <td><?php echo e(\App\Models\Item::find($mov->id_item)->nombre ?? 'N/A'); ?></td>
                            <td>
                                <strong class="<?php echo e($mov->cantidad >= 0 ? 'text-success' : 'text-danger'); ?>">
                                    <?php echo e($mov->cantidad >= 0 ? '+' : ''); ?><?php echo e($mov->cantidad); ?>

                                </strong>
                            </td>
                            <td>Bs. <?php echo e(number_format($mov->precio_unitario, 2)); ?></td>
                            <td>Bs. <?php echo e(number_format(abs($mov->costo_total), 2)); ?></td>
                            <td><small><?php echo e($mov->observaciones ?? '-'); ?></small></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-light">
                            <td colspan="4" class="text-right"><strong>Totales:</strong></td>
                            <td>
                                <strong>
                                    Ingresos: <?php echo e($movimientos->sum(function($m) { return $m->cantidad > 0 ? $m->cantidad : 0; })); ?><br>
                                    Egresos: <?php echo e($movimientos->sum(function($m) { return $m->cantidad < 0 ? abs($m->cantidad) : 0; })); ?>

                                </strong>
                            </td>
                            <td></td>
                            <td><strong>Bs. <?php echo e(number_format($movimientos->sum('costo_total'), 2)); ?></strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    
    
<div class="text-center mt-3">
    <?php if($tipo == 'compra' && $referenciaId): ?>
        <a href="#" class="btn btn-primary" onclick="verDetalleNota(<?php echo e($referenciaId); ?>)">
            <i class="fas fa-external-link-alt"></i> Ver Compra #<?php echo e($referenciaId); ?>

        </a>
    <?php elseif($tipo == 'venta' && $referenciaId): ?>
        <a href="#" class="btn btn-primary" onclick="verDetalleNotaVenta(<?php echo e($referenciaId); ?>)">
            <i class="fas fa-external-link-alt"></i> Ver Venta #<?php echo e($referenciaId); ?>

        </a>
    <?php elseif($tipo == 'produccion' && $referenciaId): ?>
        <a href="<?php echo e(route('producciones.show', $referenciaId)); ?>" class="btn btn-primary">
            <i class="fas fa-external-link-alt"></i> Ver Producción #<?php echo e($referenciaId); ?>

        </a>
    <?php elseif($tipo == 'traspaso' && $referenciaId): ?>
        <a href="<?php echo e(route('traspasos.show', $referenciaId)); ?>" class="btn btn-primary">
            <i class="fas fa-external-link-alt"></i> Ver Traspaso #<?php echo e($referenciaId); ?>

        </a>
    <?php endif; ?>
</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/inventario/movimientos/show.blade.php ENDPATH**/ ?>