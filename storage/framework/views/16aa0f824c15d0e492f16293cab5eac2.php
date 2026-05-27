<?php $__env->startSection('title', 'Historial de Compras - Panadería Otto'); ?>
<?php $__env->startSection('page-title', 'Compras'); ?>
<?php $__env->startSection('page-description', 'Historial de notas y detalles de compra'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .nav-tabs-custom {
        border-radius: var(--border-radius-md);
        overflow: hidden;
    }
    .nav-tabs-custom .nav-link {
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .nav-tabs-custom .nav-link.active {
        background: var(--color-bg-light);
        border-bottom-color: var(--color-bg-light);
        color: var(--color-primary-dark);
        font-weight: 600;
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
    .estado-badge {
        font-size: 0.8rem;
        padding: 0.35rem 0.7rem;
    }
    .action-btn {
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        transform: scale(1.1);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0">
                <i class="fas fa-receipt icon-panaderia"></i> Historial de Compras
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('compras.index')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-cart-plus"></i> Nueva Compra
            </a>
        </div>
    </div>

    
    <?php if($message = Session::get('success')): ?>
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> <?php echo e($message); ?>

        </div>
    <?php endif; ?>

    
    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header p-0">
            <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab-notas" role="tab">
                        <i class="fas fa-receipt mr-1"></i> Notas de Compra
                        <span class="badge badge-primary ml-1"><?php echo e($notasCompra->total()); ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab-detalles" role="tab">
                        <i class="fas fa-barcode mr-1"></i> Detalles de Compra
                        <span class="badge badge-info ml-1"><?php echo e($detallesCompra->total()); ?></span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                
                
                <div class="tab-pane fade show active" id="tab-notas" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Empleado</th>
                                    <th class="text-right">Monto</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $notasCompra; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><span class="badge badge-info">#<?php echo e($nota->id_nota_compra); ?></span></td>
                                        <td><?php echo e($nota->fecha_compra ? $nota->fecha_compra->format('d/m/Y') : 'Sin fecha'); ?></td>
                                        <td><?php echo e($nota->proveedor->persona->nombre ?? $nota->proveedor->empresa->razon_social ?? 'N/A'); ?></td>
                                        <td><?php echo e($nota->empleado->nombre ?? 'N/A'); ?></td>
                                        <td class="text-right monto-total">Bs. <?php echo e(number_format($nota->monto_total, 2)); ?></td>
                                        <td>
                                            <?php
                                                $estadoClass = match($nota->estado) {
                                                    'completada' => 'success',
                                                    'pendiente' => 'warning',
                                                    'cancelada' => 'danger',
                                                    default => 'secondary'
                                                };
                                            ?>
                                            <span class="badge badge-<?php echo e($estadoClass); ?> estado-badge">
                                                <?php echo e(ucfirst($nota->estado)); ?>

                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo e(route('notas-compra.show', $nota->id_nota_compra)); ?>" 
                                               class="btn btn-info btn-sm action-btn" title="Ver comprobante">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <i class="fas fa-receipt"></i>
                                                <p>No hay notas de compra registradas</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if($notasCompra->count() > 0): ?>
                        <div class="d-flex justify-content-center mt-3">
                            <?php echo e($notasCompra->appends(['tab' => 'notas'])->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="tab-pane fade" id="tab-detalles" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Nota #</th>
                                    <th>Insumo</th>
                                    <th>Almacén</th>
                                    <th class="text-right">Cantidad</th>
                                    <th class="text-right">Precio Unit.</th>
                                    <th class="text-right">Subtotal</th>
                                    <th class="text-center">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $detallesCompra; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo e(route('notas-compra.show', $detalle->id_nota_compra)); ?>" 
                                               class="badge badge-info">#<?php echo e($detalle->id_nota_compra); ?></a>
                                        </td>
                                        <td><?php echo e($detalle->item->nombre ?? 'N/A'); ?></td>
                                        <td><?php echo e($detalle->almacen->nombre ?? 'N/A'); ?></td>
                                        <td class="text-right"><?php echo e($detalle->cantidad); ?></td>
                                        <td class="text-right">Bs. <?php echo e(number_format($detalle->precio, 2)); ?></td>
                                        <td class="text-right monto-total">Bs. <?php echo e(number_format($detalle->cantidad * $detalle->precio, 2)); ?></td>
                                        <td class="text-center">
                                            <a href="<?php echo e(route('notas-compra.show', $detalle->id_nota_compra)); ?>" 
                                               class="btn btn-info btn-sm action-btn" title="Ver nota completa">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <i class="fas fa-barcode"></i>
                                                <p>No hay detalles de compra registrados</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if($detallesCompra->count() > 0): ?>
                        <div class="d-flex justify-content-center mt-3">
                            <?php echo e($detallesCompra->appends(['tab' => 'detalles'])->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        if (window.location.hash) {
            $('.nav-tabs a[href="' + window.location.hash + '"]').tab('show');
        }
        $('.nav-tabs a').on('shown.bs.tab', function(e) {
            window.location.hash = e.target.hash;
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/detallecompra/index.blade.php ENDPATH**/ ?>