

<?php $__env->startSection('title', 'Proveedores'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-truck icon-panaderia"></i> Proveedores</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('proveedores.create')); ?>" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Crear Proveedor
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
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Proveedores</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre/Empresa</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $proveedores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proveedor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><span class="badge badge-info"><?php echo e($proveedor->id_proveedor); ?></span></td>
                            <td>
                                <?php if($proveedor->tipo_proveedor === 'persona' && $proveedor->persona): ?>
                                    <i class="fas fa-user text-primary"></i> <?php echo e($proveedor->persona->nombre); ?>

                                <?php elseif($proveedor->tipo_proveedor === 'empresa' && $proveedor->empresa): ?>
                                    <i class="fas fa-building text-success"></i> <?php echo e($proveedor->empresa->razon_social); ?>

                                <?php else: ?>
                                    <em class="text-muted">N/A</em>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($proveedor->correo); ?></td>
                            <td><?php echo e($proveedor->telefono); ?></td>
                            <td><?php echo e($proveedor->direccion); ?></td>
                            <td>
                                <span class="badge badge-<?php echo e($proveedor->tipo_proveedor === 'persona' ? 'info' : 'success'); ?>">
                                    <?php echo e(ucfirst($proveedor->tipo_proveedor)); ?>

                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?php echo e(route('proveedores.show', $proveedor->id_proveedor)); ?>" class="btn btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('proveedores.edit', $proveedor->id_proveedor)); ?>" class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('proveedores.destroy', $proveedor->id_proveedor)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox"></i> No hay proveedores registrados
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($proveedores->count() > 0): ?>
            <div class="card-footer d-flex justify-content-center">
                <?php echo e($proveedores->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/proveedores/index.blade.php ENDPATH**/ ?>