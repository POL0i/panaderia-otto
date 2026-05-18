<?php $__env->startSection('title', 'Movimientos de Inventario - Panadería Otto'); ?>
<?php $__env->startSection('page-title', 'Movimientos de Inventario'); ?>
<?php $__env->startSection('page-description', 'Registro de movimientos agrupados por referencia'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .movimiento-badge {
        font-size: 0.8rem;
        padding: 0.35rem 0.7rem;
    }
    .cantidad-ingreso {
        color: var(--badge-success);
        font-weight: 600;
    }
    .cantidad-egreso {
        color: var(--badge-danger);
        font-weight: 600;
    }
    .items-count {
        font-size: 0.85rem;
    }
    .ver-btn {
        transition: all 0.2s ease;
    }
    .ver-btn:hover {
        transform: scale(1.1);
    }
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        color: var(--text-muted);
    }
    .empty-state i {
        font-size: 3rem;
        opacity: 0.4;
        margin-bottom: 1rem;
    }
    .monto-total {
        font-weight: 700;
        color: var(--color-primary-dark);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0">
                <i class="fas fa-arrows-alt-v icon-panaderia"></i> Movimientos de Inventario
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('movimientos.index')); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-sync-alt"></i> Actualizar
            </a>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Movimientos Agrupados
                <span class="badge badge-primary ml-2"><?php echo e($movimientos->total()); ?></span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Referencia</th>
                            <th>Tipo</th>
                            <th class="text-center">Ingresos</th>
                            <th class="text-center">Egresos</th>
                            <th class="text-center">Items</th>
                            <th class="text-right">Costo Total</th>
                            <th>Estado</th>
                            <th class="text-center">Ver</th>
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
                                <td>
                                    <i class="far fa-calendar-alt text-muted mr-1"></i>
                                    <?php echo e(\Carbon\Carbon::parse($mov->fecha_movimiento)->format('d/m/Y H:i')); ?>

                                </td>
                                <td>
                                    <span class="badge badge-secondary movimiento-badge">
                                        <?php echo e(ucfirst($mov->referencia_tipo)); ?> #<?php echo e($mov->referencia_id); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($esTraspaso): ?>
                                        <span class="badge badge-info movimiento-badge">Traspaso</span>
                                    <?php elseif($esIngreso && !$esEgreso): ?>
                                        <span class="badge badge-success movimiento-badge">Ingreso</span>
                                    <?php elseif($esEgreso && !$esIngreso): ?>
                                        <span class="badge badge-danger movimiento-badge">Egreso</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning movimiento-badge">Mixto</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($mov->total_ingresos > 0): ?>
                                        <span class="cantidad-ingreso">+<?php echo e($mov->total_ingresos); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($mov->total_egresos > 0): ?>
                                        <span class="cantidad-egreso">-<?php echo e($mov->total_egresos); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-light items-count"><?php echo e($mov->items_count); ?></span>
                                </td>
                                <td class="text-right monto-total">
                                    Bs. <?php echo e(number_format($mov->costo_total, 2)); ?>

                                </td>
                                <td>
                                    <?php
                                        $estadoClass = match($mov->estado) {
                                            'completado' => 'success',
                                            'pendiente' => 'warning',
                                            'cancelado' => 'danger',
                                            default => 'secondary'
                                        };
                                    ?>
                                    <span class="badge badge-<?php echo e($estadoClass); ?> movimiento-badge">
                                        <?php echo e(ucfirst($mov->estado)); ?>

                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo e(route('movimientos.show', $mov->referencia_id)); ?>?tipo=<?php echo e($mov->referencia_tipo); ?>" 
                                       class="btn btn-info btn-sm ver-btn" 
                                       title="Ver detalle"
                                       data-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <i class="fas fa-arrows-alt-v"></i>
                                        <p>No hay movimientos registrados</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($movimientos->hasPages()): ?>
            <div class="card-footer d-flex justify-content-center">
                <?php echo e($movimientos->onEachSide(1)->links('pagination::bootstrap-4')); ?>

            </div>
        <?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/inventario/movimientos/index.blade.php ENDPATH**/ ?>