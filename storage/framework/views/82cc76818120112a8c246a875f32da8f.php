

<?php $__env->startSection('title', 'Recetas'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0">
                <i class="fas fa-book icon-panaderia"></i> Recetas
            </h1>
            <small class="text-muted">Administra las recetas de tus productos</small>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('recetas.create')); ?>" class="btn btn-save">
                <i class="fas fa-plus"></i> Nueva Receta
            </a>
        </div>
    </div>

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h5><i class="fas fa-exclamation-circle"></i> Errores de validación</h5>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Recetas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 70px">ID</th>
                            <th>Nombre</th>
                            <th style="width: 150px" class="text-center">Cantidad Requerida</th>
                            <th style="width: 100px" class="text-center">Insumos</th>
                            <th>Descripción</th>
                            <th style="width: 130px" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recetas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="align-middle">
                                    <span class="badge badge-info">#<?php echo e($receta->id_receta); ?></span>
                                </td>
                                <td class="align-middle">
                                    <strong><?php echo e($receta->nombre); ?></strong>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-success badge-pill px-3 py-2">
                                        <?php echo e($receta->cantidad_requerida); ?> unidades
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-warning badge-pill px-3 py-2">
                                        <?php echo e($receta->detalles->count()); ?> insumos
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <?php if($receta->descripcion): ?>
                                        <?php echo e(Str::limit($receta->descripcion, 50)); ?>

                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo e(route('recetas.show', $receta)); ?>" 
                                           class="btn btn-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('recetas.edit', $receta)); ?>" 
                                           class="btn btn-warning" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('recetas.destroy', $receta)); ?>" 
                                              method="POST" 
                                              style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" 
                                                    class="btn btn-danger" 
                                                    onclick="return confirm('¿Está seguro de que desea eliminar esta receta?')" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">No hay recetas registradas</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <div class="mt-3 d-flex justify-content-center">
                <?php echo e($recetas->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/produccion/recetas/index.blade.php ENDPATH**/ ?>