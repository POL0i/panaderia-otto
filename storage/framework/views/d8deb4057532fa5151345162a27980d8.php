<?php $__env->startSection('title', 'Traspasos de Inventario'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-exchange-alt"></i> Traspasos de Inventario</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('traspasos.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Traspaso
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Traspasos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Empleado</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Items</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $traspasos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $traspaso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php 
                                $primer = $traspaso->detalles->first();
                            ?>
                            <tr>
                                <td><span class="badge badge-info">#<?php echo e($traspaso->id_traspaso); ?></span></td>
                                <td><?php echo e(\Carbon\Carbon::parse($traspaso->fecha_traspaso)->format('d/m/Y H:i')); ?></td>
                                <td><?php echo e($traspaso->empleado->nombre ?? 'N/A'); ?></td>
                                <td><?php echo e($primer->almacenOrigen()->nombre ?? 'N/A'); ?></td>
                                <td><?php echo e($primer->almacenDestino()->nombre ?? 'N/A'); ?></td>
                                <td><span class="badge badge-secondary"><?php echo e($traspaso->detalles->count()); ?> items</span></td>
                                <td><?php echo e(Str::limit($traspaso->descripcion, 30) ?: '-'); ?></td>
                                <td>
                                    <a href="<?php echo e(route('traspasos.show', $traspaso)); ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">No hay traspasos registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($traspasos->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/inventario/traspasos/index.blade.php ENDPATH**/ ?>