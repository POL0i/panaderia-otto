<?php $__env->startSection('title', 'Movimientos de Inventario'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-arrows-alt-v"></i> Movimientos de Inventario</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('produccion.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Movimientos Agrupados</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Referencia</th>
                            <th>Tipo(s)</th>
                            <th>Ingresos</th>
                            <th>Egresos</th>
                            <th>Items</th>
                            <th>Costo Total</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $movimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $tipos = explode(',', $mov->tipos);
                                $esIngreso = in_array('ingreso', $tipos);
                                $esEgreso = in_array('egreso', $tipos);
                                $esTraspaso = in_array('traspaso_origen', $tipos) || in_array('traspaso_destino', $tipos);
                            ?>
                            <tr>
                                <td><?php echo e(\Carbon\Carbon::parse($mov->fecha_movimiento)->format('d/m/Y H:i')); ?></td>
                                <td>
                                    <span class="badge badge-secondary"><?php echo e(ucfirst($mov->referencia_tipo)); ?> #<?php echo e($mov->referencia_id); ?></span>
                                </td>
                                <td>
                                    <?php if($esTraspaso): ?>
                                        <span class="badge badge-info">🔄 Traspaso</span>
                                    <?php elseif($esIngreso && !$esEgreso): ?>
                                        <span class="badge badge-success">📥 Ingreso</span>
                                    <?php elseif($esEgreso && !$esIngreso): ?>
                                        <span class="badge badge-danger">📤 Egreso</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">📦 Mixto</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($mov->total_ingresos > 0): ?>
                                        <span class="text-success">+<?php echo e($mov->total_ingresos); ?></span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($mov->total_egresos > 0): ?>
                                        <span class="text-danger">-<?php echo e($mov->total_egresos); ?></span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge badge-pill badge-light"><?php echo e($mov->items_count); ?></span></td>
                                <td>Bs. <?php echo e(number_format($mov->costo_total, 2)); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo e($mov->estado == 'completado' ? 'success' : 'warning'); ?>">
                                        <?php echo e(ucfirst($mov->estado)); ?>

                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('movimientos.show', $mov->referencia_id)); ?>?tipo=<?php echo e($mov->referencia_tipo); ?>" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">No hay movimientos registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($movimientos->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/inventario/movimientos/index.blade.php ENDPATH**/ ?>