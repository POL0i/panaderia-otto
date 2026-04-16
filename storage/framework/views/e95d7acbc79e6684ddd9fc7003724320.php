

<?php $__env->startSection('title', 'Productos'); ?>
<?php $__env->startSection('page-title', 'Productos'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Productos</h3>
                <div class="card-tools">
        <a href="<?php echo e(route('productos.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Crear Producto
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
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Item</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($producto->id_producto); ?></td>
                            <td><?php echo e($producto->nombre); ?></td>
                            <td><span class="badge badge-primary"><?php echo e($producto->categoria->nombre ?? 'N/A'); ?></span></td>
                            <td><?php echo e($producto->item->id_item ?? 'N/A'); ?></td>
                            <td>$<?php echo e(number_format($producto->precio, 2)); ?></td>
                            <td>
                                <a href="<?php echo e(route('productos.show', $producto->id_producto)); ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('productos.edit', $producto->id_producto)); ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('productos.destroy', $producto->id_producto)); ?>" method="POST" style="display:inline;">
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
                <?php echo e($productos->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/producto/index.blade.php ENDPATH**/ ?>