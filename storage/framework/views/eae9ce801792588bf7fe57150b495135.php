

<?php $__env->startSection('title', 'Lotes de Inventario'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-boxes"></i> Lotes de Inventario</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('lotes.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Lote
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Lotes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Almacén</th>
                            <th>Item</th>
                            <th>Cant. Inicial</th>
                            <th>Cant. Disponible</th>
                            <th>Precio Unit.</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $lotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>#<?php echo e($lote->id_lote); ?></td>
<td><?php echo e($lote->almacen_nombre); ?></td>
<td><?php echo e($lote->item_nombre); ?></td>
                                <td><?php echo e($lote->cantidad_inicial); ?></td>
                                <td><?php echo e($lote->cantidad_disponible); ?></td>
                                <td>Bs. <?php echo e(number_format($lote->precio_unitario, 2)); ?></td>
                                <td><?php echo e($lote->metodo_valuacion ?? 'PEPS'); ?></td>
                                <td>
                                    <?php if($lote->estado == 'disponible'): ?>
                                        <span class="badge badge-success">Disponible</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger"><?php echo e(ucfirst($lote->estado)); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('lotes.show', $lote)); ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">No hay lotes registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($lotes->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/inventario/lotes/index.blade.php ENDPATH**/ ?>