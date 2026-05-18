<?php $__env->startSection('title', 'Ver Receta: ' . $receta->nombre); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="fas fa-book-open mr-2"></i> Receta: <?php echo e($receta->nombre); ?>

            </h1>
            <small class="text-muted">Detalles de la receta</small>
        </div>
        <div class="col-md-4 text-right">
            <a href="<?php echo e(route('produccion.recetas.detalles', $receta)); ?>" class="btn btn-success btn-sm">
                <i class="fas fa-boxes"></i> Gestionar Insumos
            </a>
            <a href="<?php echo e(route('recetas.edit', $receta)); ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="<?php echo e(route('recetas.index')); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm animate-fade-in-up">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información General</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th style="width: 150px;">ID Receta:</th>
                            <td><span class="badge badge-info">#<?php echo e($receta->id_receta); ?></span></td>
                        </tr>
                        <tr>
                            <th>Nombre:</th>
                            <td><strong><?php echo e($receta->nombre); ?></strong></td>
                        </tr>
                        <tr>
                            <th>Producto:</th>
                            <td>
                                <?php if($receta->producto && $receta->producto->item): ?>
                                    <span class="badge badge-success">
                                        <?php echo e($receta->producto->item->nombre); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">No asignado</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Rinde:</th>
                            <td><strong><?php echo e($receta->cantidad_requerida); ?></strong> unidad(es)</td>
                        </tr>
                        <tr>
                            <th>Total Insumos:</th>
                            <td>
                                <span class="badge badge-warning badge-pill px-3">
                                    <?php echo e($receta->detalles->count()); ?> insumo(s)
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Creada:</th>
                            <td><?php echo e($receta->created_at->format('d/m/Y H:i')); ?></td>
                        </tr>
                        <tr>
                            <th>Actualizada:</th>
                            <td><?php echo e($receta->updated_at->format('d/m/Y H:i')); ?></td>
                        </tr>
                    </table>

                    <?php if($receta->descripcion): ?>
                        <hr>
                        <p><strong><i class="fas fa-align-left"></i> Descripción:</strong></p>
                        <p class="text-muted"><?php echo e($receta->descripcion); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            
            <div class="card shadow-sm animate-fade-in-up">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Insumos de la Receta</h5>
                    <a href="<?php echo e(route('produccion.recetas.detalles', $receta)); ?>" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Agregar Insumos
                    </a>
                </div>
                <div class="card-body">
                    <?php if($receta->detalles->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 60px;">ID</th>
                                        <th>Insumo</th>
                                        <th>Categoría</th>
                                        <th style="width: 130px;" class="text-center">Cantidad</th>
                                        <th style="width: 80px;" class="text-center">Unidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $receta->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $nombreInsumo = $detalle->insumo->item->nombre ?? $detalle->insumo->nombre ?? 'N/A';
                                            $categoria = $detalle->insumo->categoria->nombre ?? 'Sin categoría';
                                            $unidad = $detalle->unidad_medida ?? $detalle->insumo->item->unidad_medida ?? 'unidad';
                                        ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-secondary">#<?php echo e($detalle->id_detalle_receta); ?></span>
                                            </td>
                                            <td>
                                                <strong><?php echo e($nombreInsumo); ?></strong>
                                            </td>
                                            <td>
                                                <small class="text-muted"><?php echo e($categoria); ?></small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-info px-3 py-1">
                                                    <?php echo e(number_format($detalle->cantidad_requerida, 3)); ?>

                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge-unidad" style="background: #5D3A1A; color: white; padding: 3px 8px; border-radius: 20px; font-size: 11px;">
                                                    <?php echo e($unidad); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-box-open fa-3x mb-3"></i>
                            <p>No hay insumos asignados a esta receta.</p>
                            <a href="<?php echo e(route('produccion.recetas.detalles', $receta)); ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Agregar Insumos
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mt-3 animate-fade-in-up">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <a href="<?php echo e(route('recetas.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver a Recetas
                        </a>
                    </div>
                    <form action="<?php echo e(route('recetas.destroy', $receta)); ?>" method="POST" id="deleteRecetaForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                            <i class="fas fa-trash"></i> Eliminar Receta
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar la receta <strong><?php echo e($receta->nombre); ?></strong>?</p>
                <p class="text-danger mb-0">
                    <i class="fas fa-info-circle"></i> 
                    Si la receta tiene producciones asociadas, no podrá ser eliminada.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmDelete">
                    <i class="fas fa-trash"></i> Eliminar Receta
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmDelete() {
    $('#confirmDeleteModal').modal('show');
}

$(document).ready(function() {
    $('#btnConfirmDelete').on('click', function() {
        $('#deleteRecetaForm').submit();
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/produccion/recetas/show.blade.php ENDPATH**/ ?>