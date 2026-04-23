

<?php $__env->startSection('title', 'Notas de Venta'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-file-invoice-dollar icon-panaderia"></i> Notas de Venta</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('notas-venta.create')); ?>" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Crear Nota
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
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Notas de Venta</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Empleado</th>
                        <th class="text-right">Monto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $notasVenta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><span class="badge badge-info"><?php echo e($nota->id_nota_venta); ?></span></td>
                            <td><?php echo e($nota->fecha_venta->format('d/m/Y')); ?></td>
                            <td><i class="fas fa-users text-primary"></i> <?php echo e($nota->cliente->nombre ?? 'N/A'); ?></td>
                            <td><i class="fas fa-user text-success"></i> <?php echo e($nota->empleado->nombre ?? 'N/A'); ?></td>
                            <td class="text-right font-weight-bold">$<?php echo e(number_format($nota->monto_total, 2)); ?></td>
                            <td>
                                <span class="badge badge-<?php echo e($nota->estado === 'completada' ? 'success' : ($nota->estado === 'pendiente' ? 'warning' : 'danger')); ?>">
                                    <?php echo e(ucfirst($nota->estado)); ?>

                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?php echo e(route('notas-venta.show', $nota->id_nota_venta)); ?>" class="btn btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('notas-venta.edit', $nota->id_nota_venta)); ?>" class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('notas-venta.destroy', $nota->id_nota_venta)); ?>" method="POST" style="display:inline;">
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
                                <i class="fas fa-inbox"></i> No hay notas de venta registradas
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($notasVenta->count() > 0): ?>
            <div class="card-footer d-flex justify-content-center">
                <?php echo e($notasVenta->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/notaventa/index.blade.php ENDPATH**/ ?>