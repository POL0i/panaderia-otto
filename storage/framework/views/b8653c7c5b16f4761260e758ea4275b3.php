

<?php $__env->startSection('title', 'Ver Detalle de Receta'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-eye icon-panaderia"></i> Detalle de Receta #<?php echo e($detalleReceta->id_detalle_receta); ?></h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="<?php echo e(route('detalles-receta.edit', $detalleReceta)); ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="<?php echo e(route('detalles-receta.index')); ?>" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Detalles del Detalle de Receta</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">ID Detalle Receta:</label>
                        <p><?php echo e($detalleReceta->id_detalle_receta); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-book"></i> Receta:</label>
                        <p><?php echo e($detalleReceta->receta->nombre ?? 'N/A'); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-warehouse"></i> Insumo:</label>
                        <p><?php echo e($detalleReceta->insumo->nombre ?? 'N/A'); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-balance-scale"></i> Cantidad Requerida:</label>
                        <p><?php echo e($detalleReceta->cantidad_requerida); ?> unidades</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <a href="<?php echo e(route('detalles-receta.index')); ?>" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <div>
                    <a href="<?php echo e(route('detalles-receta.edit', $detalleReceta)); ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <form action="<?php echo e(route('detalles-receta.destroy', $detalleReceta)); ?>" method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar?')">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/produccion/detalles-receta/show.blade.php ENDPATH**/ ?>