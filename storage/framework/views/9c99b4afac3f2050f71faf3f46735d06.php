

<?php $__env->startSection('title', 'Ver Receta'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Receta: <?php echo e($receta->nombre); ?></h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="<?php echo e(route('recetas.edit', $receta)); ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="<?php echo e(route('recetas.index')); ?>" class="btn btn-back btn-sm">
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

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Detalles de la Receta</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong><i class="fas fa-hashtag"></i> ID Receta:</strong> <?php echo e($receta->id_receta); ?></p>
                    <p><strong><i class="fas fa-tag"></i> Nombre:</strong> <?php echo e($receta->nombre); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong><i class="fas fa-balance-scale"></i> Cantidad Requerida:</strong> <?php echo e($receta->cantidad_requerida); ?></p>
                    <p><strong><i class="fas fa-list"></i> Total Insumos:</strong> <span class="badge badge-info"><?php echo e($receta->detalles->count()); ?></span></p>
                </div>
            </div>

            <?php if($receta->descripcion): ?>
                <hr>
                <div>
                    <p><strong><i class="fas fa-align-left"></i> Descripción:</strong></p>
                    <p class="text-muted"><?php echo e($receta->descripcion); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card shadow-sm mt-3 animate-fade-in-up">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-boxes"></i> Insumos de la Receta</h5>
        </div>
        <div class="card-body">
            <?php if($receta->detalles->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>ID Insumo</th>
                                <th>Nombre Insumo</th>
                                <th>Cantidad Requerida</th>
                                <th style="width: 100px;" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $receta->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($detalle->id_insumo); ?></td>
                                    <td><?php echo e($detalle->insumo->nombre ?? 'N/A'); ?></td>
                                    <td><?php echo e($detalle->cantidad_requerida); ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo e(route('detalles-receta.edit', $detalle)); ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('detalles-receta.destroy', $detalle)); ?>" method="POST" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted text-center"><i class="fas fa-inbox"></i> No hay insumos asignados a esta receta</p>
            <?php endif; ?>
        </div>
        <div class="card-footer">
            <a href="<?php echo e(route('detalles-receta.create')); ?>" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Agregar Insumo
            </a>
        </div>
    </div>

    <div class="card-footer mt-3">
        <div class="d-flex justify-content-between">
            <div>
                <a href="<?php echo e(route('recetas.edit', $receta)); ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="<?php echo e(route('recetas.index')); ?>" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            <form action="<?php echo e(route('recetas.destroy', $receta)); ?>" method="POST" style="display:inline;">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar esta receta?')">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
            <i class="fas fa-times"></i> Cancelar
        </a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/produccion/recetas/show.blade.php ENDPATH**/ ?>