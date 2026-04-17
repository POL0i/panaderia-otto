

<?php $__env->startSection('title', 'Movimientos de Inventario'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-arrows-alt-v icon-panaderia"></i> Movimientos de Inventario</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('movimientos.create')); ?>" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Nuevo Movimiento
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
            <h5 class="mb-0"><i class="fas fa-list-check"></i> Listado de Movimientos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Almacén</th>
                        <th>Item</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Costo Total</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $movimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movimiento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><span class="badge badge-info"><?php echo e($movimiento->id_movimiento); ?></span></td>
                            <td>
                                <span class="badge badge-<?php echo e($movimiento->tipo_movimiento == 'ingreso' ? 'success' : ($movimiento->tipo_movimiento == 'egreso' ? 'danger' : 'info')); ?>">
                                    <?php echo e(ucfirst($movimiento->tipo_movimiento)); ?>

                                </span>
                            </td>
                            <td><?php echo e($movimiento->almacen->nombre ?? 'N/A'); ?></td>
                            <td><?php echo e($movimiento->item->nombre ?? 'N/A'); ?></td>
                            <td><?php echo e($movimiento->cantidad); ?></td>
                            <td>$<?php echo e(number_format($movimiento->precio_unitario, 2)); ?></td>
                            <td>$<?php echo e(number_format($movimiento->costo_total, 2)); ?></td>
                            <td><?php echo e($movimiento->fecha_movimiento->format('d/m/Y H:i')); ?></td>
                            <td>
                                <span class="badge badge-<?php echo e($movimiento->estado == 'completado' ? 'success' : ($movimiento->estado == 'pendiente' ? 'warning' : 'info')); ?>">
                                    <?php echo e(ucfirst($movimiento->estado)); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('movimientos.show', $movimiento)); ?>" class="btn btn-info btn-xs">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('movimientos.edit', $movimiento)); ?>" class="btn btn-warning btn-xs">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('movimientos.destroy', $movimiento)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('\u00bfEst\u00e1 seguro?')" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted">No hay movimientos registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="pagination justify-content-center">
                <?php echo e($movimientos->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/inventario/movimientos/index.blade.php ENDPATH**/ ?>