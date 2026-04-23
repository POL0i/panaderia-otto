

<?php $__env->startSection('title', 'Empresas Proveedoras'); ?>
<?php $__env->startSection('page-title', 'Empresas Proveedoras'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Empresas Proveedoras</h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('pempresa.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Crear Empresa
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if($message = Session::get('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Éxito!</strong> <?php echo e($message); ?>

                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="50">ID</th>
                                <th>Razón Social</th>
                                <th>Proveedor (Correo)</th>
                                <th width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $empresas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empresa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($empresa->id_empresa); ?></td>
                                <td><?php echo e($empresa->razon_social); ?></td>
                                <td><?php echo e($empresa->proveedor->correo ?? 'N/A'); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo e(route('pempresa.show', $empresa->id_empresa)); ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('pempresa.edit', $empresa->id_empresa)); ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('pempresa.destroy', $empresa->id_empresa)); ?>" method="POST" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar esta empresa?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <div class="alert alert-info text-center py-4 mb-0">
                                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                                            <p>No hay empresas proveedoras registradas.</p>
                                            <a href="<?php echo e(route('pempresa.create')); ?>" class="btn btn-primary btn-sm mt-2">
                                                <i class="fas fa-plus"></i> Crear la primera empresa
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if(method_exists($empresas, 'links') && $empresas->hasPages()): ?>
            <div class="card-footer">
                <?php echo e($empresas->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/pempresa/index.blade.php ENDPATH**/ ?>