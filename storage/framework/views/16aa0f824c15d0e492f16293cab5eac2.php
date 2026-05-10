<?php $__env->startSection('title', 'Detalles de Compra'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-barcode icon-panaderia"></i> Detalles de Compra</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('detalles-compra.create')); ?>" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Crear Detalle
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
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Detalles de Compra</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Nota #</th>
                        <th>Insumo</th>
                        <th class="text-right">Cantidad</th>
                        <th class="text-right">Precio Unit.</th>
                        <th class="text-right">Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $detallesCompra; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><span class="badge badge-info">#<?php echo e($detalle->id_nota_compra); ?></span></td>
                            <td><i class="fas fa-box text-primary"></i> <?php echo e($detalle->insumo->nombre ?? 'N/A'); ?></td>
                            <td class="text-right"><?php echo e($detalle->cantidad); ?></td>
                            <td class="text-right">$<?php echo e(number_format($detalle->precio, 2)); ?></td>
                            <td class="text-right font-weight-bold">$<?php echo e(number_format($detalle->cantidad * $detalle->precio, 2)); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?php echo e(route('detalles-compra.edit', [$detalle->id_nota_compra, $detalle->id_insumo])); ?>" class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('detalles-compra.destroy', [$detalle->id_nota_compra, $detalle->id_insumo])); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-inbox"></i> No hay detalles de compra registrados
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($detallesCompra->count() > 0): ?>
            <div class="card-footer d-flex justify-content-center">
                <?php echo e($detallesCompra->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/detallecompra/index.blade.php ENDPATH**/ ?>