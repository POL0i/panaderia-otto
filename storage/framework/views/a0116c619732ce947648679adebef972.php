<?php $__env->startSection('title', 'Almacenes'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-warehouse icon-panaderia"></i> Almacenes</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('almacenes.create')); ?>" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Crear Almacén
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
            <h5 class="mb-0"><i class="fas fa-list-check"></i> Listado de Almacenes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Ubicación</th>
                        <th>Capacidad</th>
                        <th>Items Almacenados</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                    <tbody>
                        <?php $__currentLoopData = $almacenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $almacen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><span class="badge badge-info"><?php echo e($almacen->id_almacen); ?></span></td>
                            <td><?php echo e($almacen->nombre); ?></td>
                            <td><?php echo e($almacen->ubicacion); ?></td>
                            <td><?php echo e($almacen->capacidad); ?></td>
                            <td><span class="badge badge-info"><?php echo e($almacen->items->count()); ?></span></td>
                            <td>
                                <a href="<?php echo e(route('almacenes.show', $almacen->id_almacen)); ?>" class="btn btn-info btn-xs" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('almacenes.edit', $almacen->id_almacen)); ?>" class="btn btn-warning btn-xs" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('almacenes.destroy', $almacen->id_almacen)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('¿Está seguro?')" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
            </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="pagination justify-content-center">
                <?php echo e($almacenes->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/almacen/index.blade.php ENDPATH**/ ?>