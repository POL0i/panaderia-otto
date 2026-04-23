

<?php $__env->startSection('title', 'Ver Producción'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-eye icon-panaderia"></i> Producción #<?php echo e($produccion->id_produccion); ?></h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="<?php echo e(route('producciones.index')); ?>" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Detalles de la Producción</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">ID Producción:</label>
                        <p><?php echo e($produccion->id_produccion); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-calendar"></i> Fecha de Producción:</label>
                        <p><?php echo e($produccion->fecha_produccion->format('d/m/Y')); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-book"></i> Receta:</label>
                        <p><?php echo e($produccion->receta->nombre ?? 'N/A'); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-cubes"></i> Cantidad Producida:</label>
                        <p><?php echo e($produccion->cantidad_producida); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-user"></i> Empleado:</label>
                        <p><?php echo e($produccion->empleado->nombre ?? 'N/A'); ?></p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-clock"></i> Creado:</label>
                        <p><?php echo e($produccion->created_at->format('d/m/Y H:i')); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-3 animate-fade-in-up">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-cubes"></i> Insumos Requeridos de la Receta</h5>
        </div>
        <div class="card-body">
            <?php if($produccion->receta && $produccion->receta->detalles->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Insumo</th>
                                <th>Cantidad Requerida</th>
                                <th>Total para esta Producción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $produccion->receta->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($detalle->insumo->nombre ?? 'N/A'); ?></td>
                                    <td><?php echo e($detalle->cantidad_requerida); ?></td>
                                    <td><?php echo e($detalle->cantidad_requerida * $produccion->cantidad_producida); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center"><i class="fas fa-inbox"></i> No hay insumos asignados a esta receta</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-footer mt-3">
        <div class="d-flex justify-content-between">
            <a href="<?php echo e(route('producciones.index')); ?>" class="btn btn-cancel">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <div>
                <a href="<?php echo e(route('producciones.edit', $produccion)); ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <form action="<?php echo e(route('producciones.destroy', $produccion)); ?>" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar esta producción?')">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/produccion/producciones/show.blade.php ENDPATH**/ ?>