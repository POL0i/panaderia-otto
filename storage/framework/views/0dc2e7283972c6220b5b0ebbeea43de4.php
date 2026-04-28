

<?php $__env->startSection('title', 'Producciones'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0">
                <i class="fas fa-industry icon-panaderia"></i> Producciones
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('producciones.create')); ?>" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Nueva Producción
            </a>
        </div>
    </div>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h5><i class="fas fa-exclamation-circle"></i> Errores de validación</h5>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list-check"></i> Listado de Producciones</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Receta</th>
                            <th>Cantidad Producida</th>
                            <th>Empleado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $producciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produccion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><span class="badge badge-info"><?php echo e($produccion->id_produccion); ?></span></td>
                                <td><?php echo e($produccion->fecha_produccion->format('d/m/Y')); ?></td>
                                <td><strong><?php echo e($produccion->receta->nombre ?? 'N/A'); ?></strong></td>
                                <td><span class="badge badge-success"><?php echo e($produccion->cantidad_producida); ?></span></td>
                                <td><?php echo e($produccion->empleado->nombre ?? 'N/A'); ?></td>
                                <td>
                                    <a href="<?php echo e(route('producciones.show', $produccion)); ?>" class="btn btn-info btn-xs" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('producciones.edit', $produccion)); ?>" class="btn btn-warning btn-xs" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('producciones.destroy', $produccion)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('¿Está seguro de que desea eliminar esta producción?')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="fas fa-inbox"></i> No hay producciones registradas
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="pagination justify-content-center">
                <?php echo e($producciones->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/produccion/producciones/index.blade.php ENDPATH**/ ?>