

<?php $__env->startSection('title', 'Items'); ?>
<?php $__env->startSection('page-title', 'Items'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Items</h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('items.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Crear Item
                    </a>
                </div>
            </div> <!-- Cierre del card-header -->

            <div class="card-body">
                <?php if($message = Session::get('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Éxito!</strong> <?php echo e($message); ?>

                    </div>
                <?php endif; ?>

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo de Item</th>
                            <th>Unidad de Medida</th>
                            <th>Producto/Insumo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($item->id_item); ?></td>
                            <td>
                                <span class="badge badge-secondary"><?php echo e(ucfirst($item->tipo_item)); ?></span>
                            </td>
                            <td><?php echo e($item->unidad_medida); ?></td>
                            <td>
                                <?php if($item->tipo_item === 'producto' && $item->producto): ?>
                                    <span class="badge badge-success"><?php echo e($item->producto->nombre); ?></span>
                                <?php elseif($item->tipo_item === 'insumo' && $item->insumo): ?>
                                    <span class="badge badge-info"><?php echo e($item->insumo->nombre); ?></span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Sin relación</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('items.show', $item->id_item)); ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('items.edit', $item->id_item)); ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('items.destroy', $item->id_item)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center">
                                    No hay items registrados
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div> <!-- Cierre del card-body -->
        </div> <!-- Cierre del card -->
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/item/index.blade.php ENDPATH**/ ?>