<?php $__env->startSection('title', 'Notas y Detalles de Venta - Panadería Otto'); ?>
<?php $__env->startSection('page-title', 'Ventas'); ?>
<?php $__env->startSection('page-description', 'Historial de notas y detalles de venta'); ?>

<?php $__env->startPush('styles'); ?>
<style>
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
    .ver-btn {
        transition: all 0.2s ease;
    }
    .ver-btn:hover {
        transform: scale(1.1);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0">
                <i class="fas fa-history icon-panaderia"></i> Historial de Ventas
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('ventas.index')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-cart-plus"></i> Nueva Venta
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
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab-notas" role="tab">
                        <i class="fas fa-file-invoice-dollar mr-1"></i> Notas de Venta
                        <span class="badge badge-primary ml-1"><?php echo e($notasVenta->total()); ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab-detalles" role="tab">
                        <i class="fas fa-list-alt mr-1"></i> Detalles de Venta
                        <span class="badge badge-info ml-1"><?php echo e($detallesVenta->total()); ?></span>
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
                                    <th>Cliente</th>
                                    <th>Empleado</th>
                                    <th>Método</th>
                                    <th class="text-right">Monto</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $notasVenta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><span class="badge badge-info">#<?php echo e($nota->id_nota_venta); ?></span></td>
                                        <td><?php echo e($nota->fecha_venta->format('d/m/Y H:i')); ?></td>
                                        <td><?php echo e($nota->cliente->nombre ?? 'N/A'); ?></td>
                                        <td><?php echo e($nota->empleado->nombre ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if($nota->metodo_pago === 'libelula'): ?>
                                                <span class="badge badge-info">Libélula</span>
                                            <?php elseif($nota->metodo_pago === 'efectivo'): ?>
                                                <span class="badge badge-success">Efectivo</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary"><?php echo e($nota->metodo_pago ?? 'N/A'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right monto-total">Bs. <?php echo e(number_format($nota->monto_total, 2)); ?></td>
                                        <td>
                                            <?php
                                                $estadoClass = match($nota->estado) {
                                                    'completado' => 'success',
                                                    'pendiente' => 'warning',
                                                    'cancelado' => 'danger',
                                                    default => 'secondary'
                                                };
                                            ?>
                                            <span class="badge badge-<?php echo e($estadoClass); ?>">
                                                <?php echo e(ucfirst($nota->estado)); ?>

                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo e(route('notas-venta.show', $nota->id_nota_venta)); ?>" 
                                               class="btn btn-info btn-sm ver-btn" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8">
                                            <div class="empty-state">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                                <p>No hay notas de venta registradas</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if($notasVenta->count() > 0): ?>
                        <div class="d-flex justify-content-center mt-3">
                            <?php echo e($notasVenta->appends(['tab' => 'notas'])->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="tab-pane fade" id="tab-detalles" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Nota #</th>
                                    <th>Producto</th>
                                    <th>Almacén</th>
                                    <th class="text-right">Cant.</th>
                                    <th class="text-right">Precio U.</th>
                                    <th class="text-right">Subtotal</th>
                                    <th class="text-center">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $detallesVenta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <span class="badge badge-info">#<?php echo e($detalle->id_nota_venta); ?></span>
                                        </td>
                                        <td>
                                            <i class="fas fa-box icon-panaderia mr-1"></i>
                                            <?php echo e($detalle->item->nombre ?? 'N/A'); ?>

                                        </td>
                                        <td>
                                            <i class="fas fa-warehouse text-muted mr-1"></i>
                                            <?php echo e($detalle->almacen->nombre ?? 'N/A'); ?>

                                        </td>
                                        <td class="text-right"><?php echo e($detalle->cantidad); ?></td>
                                        <td class="text-right">Bs. <?php echo e(number_format($detalle->precio, 2)); ?></td>
                                        <td class="text-right monto-total">
                                            Bs. <?php echo e(number_format($detalle->cantidad * $detalle->precio, 2)); ?>

                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo e(route('notas-venta.show', $detalle->id_nota_venta)); ?>" 
                                               class="btn btn-info btn-sm ver-btn" title="Ver nota">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <i class="fas fa-list-alt"></i>
                                                <p>No hay detalles de venta registrados</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if($detallesVenta->count() > 0): ?>
                        <div class="d-flex justify-content-center mt-3">
                            <?php echo e($detallesVenta->appends(['tab' => 'detalles'])->links()); ?>

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
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/detalleventa/index.blade.php ENDPATH**/ ?>