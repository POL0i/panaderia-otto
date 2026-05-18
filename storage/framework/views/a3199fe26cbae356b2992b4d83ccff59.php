<?php $__env->startSection('title', 'Nota de Compra #' . $notaCompra->id_nota_compra); ?>
<?php $__env->startSection('page-title', 'Nota de Compra'); ?>
<?php $__env->startSection('page-description', 'Detalle del comprobante'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .recibo-header { background-color: var(--color-bg-lighter); }
    .recibo-footer { background-color: var(--color-bg-lighter); border-top: 1px solid var(--color-border); }
    .recibo-empresa { color: var(--color-primary-dark); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    <div class="row mb-3">
        <div class="col-12">
            <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            
            <div class="p-3 border-bottom recibo-header">
                <div class="row">
                    <div class="col-6">
                        <h4 class="recibo-empresa"><strong>PANADERÍA OTTO</strong></h4>
                        <small class="text-muted">NIT: 123456789</small><br>
                        <small class="text-muted">Av. Principal #123, Santa Cruz</small>
                    </div>
                    <div class="col-6 text-right">
                        <h5><strong>NOTA DE COMPRA</strong></h5>
                        <h6><span class="badge badge-info">#<?php echo e($notaCompra->id_nota_compra); ?></span></h6>
                        <small>Fecha: <?php echo e($notaCompra->fecha_compra ? $notaCompra->fecha_compra->format('d/m/Y') : 'Sin fecha'); ?></small>
                    </div>
                </div>
            </div>

            
            <div class="p-3 border-bottom">
                <div class="row">
                    <div class="col-6">
                        <strong>Proveedor:</strong><br>
                        <?php echo e($notaCompra->proveedor->persona->nombre ?? $notaCompra->proveedor->empresa->razon_social ?? 'No asignado'); ?><br>
                        <small>Tel: <?php echo e($notaCompra->proveedor->telefono ?? 'N/A'); ?></small>
                    </div>
                    <div class="col-6 text-right">
                        <strong>Registrado por:</strong><br>
                        <?php echo e($notaCompra->empleado->nombre ?? 'No asignado'); ?>

                    </div>
                </div>
            </div>

            
            <div class="p-3">
                <h6><i class="fas fa-boxes"></i> Insumos</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Cant.</th>
                                <th>Insumo</th>
                                <th>Almacén</th>
                                <th class="text-right">P. Unit.</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $notaCompra->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($detalle->cantidad); ?></td>
                                <td><?php echo e($detalle->insumo->nombre ?? $detalle->item->nombre ?? 'N/A'); ?></td>
                                <td><?php echo e($detalle->almacen->nombre ?? 'N/A'); ?></td>
                                <td class="text-right">Bs. <?php echo e(number_format($detalle->precio, 2)); ?></td>
                                <td class="text-right">Bs. <?php echo e(number_format($detalle->cantidad * $detalle->precio, 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Sin insumos registrados</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                <td class="text-right"><strong>Bs. <?php echo e(number_format($notaCompra->monto_total ?? 0, 2)); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            
            <div class="p-3 recibo-footer d-flex justify-content-between align-items-center">
                <?php
                    $estado = $notaCompra->estado ?? '';
                    $estadoClass = match($estado) {
                        'completada' => 'success',
                        'pendiente' => 'warning',
                        'cancelada' => 'danger',
                        default => 'secondary'
                    };
                ?>
                <span class="badge badge-<?php echo e($estadoClass); ?>" style="font-size:0.9rem;padding:0.5rem 1rem;">
                    <?php echo e($estado ? ucfirst($estado) : 'Sin estado'); ?>

                </span>
                <small class="text-muted">Documento generado electrónicamente</small>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/notas-compra/show.blade.php ENDPATH**/ ?>