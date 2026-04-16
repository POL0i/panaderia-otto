

<?php $__env->startSection('title', 'Inventario (Stock)'); ?>
<?php $__env->startSection('page-title', 'Inventario - Stock por Almacén/Item'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Stock en Almacenes</h3>
                <div class="card-tools">
        <a href="<?php echo e(route('almacen-items.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Agregar Stock
                    </a>
            <div class="card-body">
                <?php if($message = Session::get('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Éxito!</strong> <?php echo e($message); ?>

                <?php endif; ?>
    <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Almacén</th>
                            <th>Item</th>
                            <th>Tipo</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $almacenItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $almacenItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($almacenItem->almacen->nombre); ?></td>
                            <td>#<?php echo e($almacenItem->item->id_item); ?></td>
                            <td>
                                <span class="badge badge-<?php echo e($almacenItem->item->tipo_item === 'producto' ? 'success' : 'info'); ?>">
                                    <?php echo e(ucfirst($almacenItem->item->tipo_item)); ?>

                                </span>
                            </td>
                            <td>
                                <strong><?php echo e($almacenItem->stock); ?></strong> <?php echo e($almacenItem->item->unidad_medida); ?>

                            </td>
                            <td>
                                <a href="<?php echo e(route('almacen-items.edit', [$almacenItem->id_almacen, $almacenItem->id_item])); ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('almacen-items.destroy', [$almacenItem->id_almacen, $almacenItem->id_item])); ?>" method="POST" style="display:inline;">
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
            <div class="card-footer">
                <?php echo e($almacenItems->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/almacen-item/index.blade.php ENDPATH**/ ?>