

<?php $__env->startSection('title', 'Traspasos de Inventario'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-exchange-alt icon-panaderia"></i> Traspasos de Inventario</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('traspasos.create')); ?>" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Nuevo Traspaso
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

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-times-circle"></i> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list-check"></i> Listado de Traspasos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                        <th>Almacén Origen</th>
                        <th>Almacén Destino</th>
                        <th>Item</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $traspasos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $traspaso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><span class="badge badge-info"><?php echo e($traspaso->id_traspaso); ?></span></td>
                            <td><?php echo e($traspaso->almacenOrigen->nombre ?? 'N/A'); ?></td>
                            <td><?php echo e($traspaso->almacenDestino->nombre ?? 'N/A'); ?></td>
                            <td><?php echo e($traspaso->item->nombre ?? 'N/A'); ?></td>
                            <td><?php echo e($traspaso->cantidad); ?></td>
                            <td>$<?php echo e(number_format($traspaso->precio_unitario, 2)); ?></td>
                            <td><?php echo e($traspaso->fecha_traspaso->format('d/m/Y')); ?></td>
                            <td>
                                <span class="badge badge-<?php echo e($traspaso->estado == 'completado' ? 'success' : ($traspaso->estado == 'pendiente' ? 'warning' : 'danger')); ?>">
                                    <?php echo e(ucfirst($traspaso->estado)); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('traspasos.show', $traspaso)); ?>" class="btn btn-info btn-xs">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if($traspaso->estado === 'pendiente'): ?>
                                    <form action="<?php echo e(route('traspasos.completar', $traspaso)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <button type="submit" class="btn btn-success btn-xs" title="Completar">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('traspasos.cancelar', $traspaso)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <button type="submit" class="btn btn-warning btn-xs" title="Cancelar">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <a href="<?php echo e(route('traspasos.edit', $traspaso)); ?>" class="btn btn-warning btn-xs">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('traspasos.destroy', $traspaso)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('¿Está seguro?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">No hay traspasos registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="pagination justify-content-center">
                <?php echo e($traspasos->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/inventario/traspasos/index.blade.php ENDPATH**/ ?>