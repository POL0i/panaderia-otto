

<?php $__env->startSection('title', 'Traspaso #' . $traspaso->id_traspaso); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-exchange-alt"></i> Traspaso #<?php echo e($traspaso->id_traspaso); ?></h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('traspasos.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Traspaso</h5>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> #<?php echo e($traspaso->id_traspaso); ?></p>
                   <p><strong>Fecha:</strong> <?php echo e(\Carbon\Carbon::parse($traspaso->fecha_traspaso)->format('d/m/Y H:i')); ?></p>
                    <p><strong>Empleado:</strong> <?php echo e($traspaso->empleado->nombre ?? 'N/A'); ?></p>
                    <p><strong>Descripción:</strong> <?php echo e($traspaso->descripcion ?? '-'); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-warehouse"></i> Almacenes</h5>
                </div>
                <div class="card-body">
                    <?php if($traspaso->detalles->isNotEmpty()): ?>
                        <?php $primerDetalle = $traspaso->detalles->first(); ?>
                        <p><strong>Origen:</strong> <?php echo e($primerDetalle->almacenOrigen()->nombre ?? 'N/A'); ?></p>
                        <p><strong>Destino:</strong> <?php echo e($primerDetalle->almacenDestino()->nombre ?? 'N/A'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Items Traspasados</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                    <th>Unidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $traspaso->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($detalle->item()->nombre ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo e($detalle->item()->tipo_item == 'producto' ? 'success' : 'warning'); ?>">
                                            <?php echo e($detalle->item()->tipo_item ?? 'N/A'); ?>

                                        </span>
                                    </td>
                                    <td><strong><?php echo e($detalle->cantidad); ?></strong></td>
                                    <td><?php echo e($detalle->item()->unidad_medida ?? 'unidad'); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="mt-3">
        <form action="<?php echo e(route('traspasos.destroy', $traspaso)); ?>" method="POST" style="display:inline;"
              onsubmit="return confirm('¿Eliminar este traspaso? Se revertirá el stock.')">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Eliminar y Revertir
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/inventario/traspasos/show.blade.php ENDPATH**/ ?>