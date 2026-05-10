<?php $__env->startSection('title', 'Gestión de Roles y Permisos'); ?>
<?php $__env->startSection('page-title', 'Gestión Unificada de Roles y Permisos'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card animate-fade-in-up">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Gestión de Roles y Permisos
                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('rol_permisos.create')); ?>" class="btn btn-save btn-sm">
                            <i class="fas fa-plus mr-1"></i>
                            Asignar Permisos
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th>
                                        <i class="fas fa-user-shield mr-1"></i>
                                        Rol
                                        <i class="fas fa-info-circle text-muted ml-1" 
                                        data-toggle="tooltip" 
                                        data-html="true" 
                                        data-placement="bottom"
                                        title="<?php $__currentLoopData = $todosRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>&#8226; <?php echo e($rol->nombre); ?><br><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>"
                                        style="cursor: help; font-size: 0.8rem; opacity: 0.6;">
                                        </i>
                                    </th>
                                    <th>
                                        <i class="fas fa-lock mr-1"></i>
                                        Permisos
                                        <i class="fas fa-info-circle text-muted ml-1" 
                                        data-toggle="tooltip" 
                                        data-html="true" 
                                        data-placement="bottom"
                                        title="<?php $__currentLoopData = $todosPermisos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permiso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>&#8226; <?php echo e($permiso->nombre); ?><br><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>"
                                        style="cursor: help; font-size: 0.8rem; opacity: 0.6;">
                                        </i>
                                    </th>
                                    <th class="text-center" style="width: 25%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <span class="badge badge-primary badge-pill">
                                                <?php echo e($rol->nombre); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                <?php echo e($rol->permisos->count()); ?> permiso(s)
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                
                                                <button class="btn btn-outline-info btn-show-permisos" 
                                                        data-role-id="<?php echo e($rol->id_rol); ?>" 
                                                        title="Ver permisos">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                
                                                <a href="<?php echo e(route('roles.edit', $rol->id_rol)); ?>" 
                                                   class="btn btn-outline-primary"
                                                   title="Editar rol">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                
                                                <form action="<?php echo e(route('roles.clear-permissions', $rol->id_rol)); ?>" 
                                                      method="POST" 
                                                      style="display:inline;"
                                                      onsubmit="return confirm('¿Eliminar TODOS los permisos del rol <?php echo e($rol->nombre); ?>?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" 
                                                            class="btn btn-outline-warning"
                                                            title="Quitar todos los permisos">
                                                        <i class="fas fa-eraser"></i>
                                                    </button>
                                                </form>
                                                
                                                
                                                <form action="<?php echo e(route('roles.destroy', $rol->id_rol)); ?>" 
                                                      method="POST" 
                                                      style="display:inline;"
                                                      onsubmit="return confirm('¿Eliminar el rol <?php echo e($rol->nombre); ?> y TODAS sus asignaciones?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger"
                                                            title="Eliminar rol">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr class="permisos-row collapse" id="permisos-<?php echo e($rol->id_rol); ?>">
                                        <td></td>
                                        <td colspan="3" class="p-0">
                                            <div class="p-3 bg-light">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0">
                                                        <i class="fas fa-lock mr-2"></i>
                                                        Permisos de <strong><?php echo e($rol->nombre); ?></strong>
                                                    </h6>
                                                    <a href="<?php echo e(route('rol_permisos.create')); ?>?rol=<?php echo e($rol->id_rol); ?>" 
                                                       class="btn btn-xs btn-primary">
                                                        <i class="fas fa-plus mr-1"></i> Agregar permisos
                                                    </a>
                                                </div>
                                                <table class="table table-sm table-striped mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Permiso</th>
                                                            <th>Estado</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__empty_2 = true; $__currentLoopData = $rol->permisos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permiso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                            <tr>
                                                                <td><code><?php echo e($permiso->nombre); ?></code></td>
                                                                <td>
                                                                    <?php if($permiso->pivot && $permiso->pivot->estado == 'activo'): ?>
                                                                        <span class="badge badge-success">Activo</span>
                                                                    <?php elseif($permiso->pivot): ?>
                                                                        <span class="badge badge-danger">Inactivo</span>
                                                                    <?php else: ?>
                                                                        <span class="badge badge-secondary">Sin estado</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <a href="<?php echo e(route('rol_permisos.edit', $permiso->pivot->id_rol_permiso ?? 0)); ?>" 
                                                                       class="btn btn-xs btn-outline-primary"
                                                                       title="Editar estado">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <form action="<?php echo e(route('rol_permisos.destroy', $permiso->pivot->id_rol_permiso ?? 0)); ?>" 
                                                                          method="POST" style="display:inline;"
                                                                          onsubmit="return confirm('¿Eliminar esta asignación?');">
                                                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                                        <button class="btn btn-xs btn-outline-danger"
                                                                                title="Eliminar asignación">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                            <tr>
                                                                <td colspan="3" class="text-center text-muted">
                                                                    Sin permisos asignados
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-inbox text-muted mr-2"></i>
                                            No hay roles registrados
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Total de roles: <strong><?php echo e(count($roles)); ?></strong>
                    </small>
                    <a href="<?php echo e(route('home')); ?>" class="btn btn-back btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(function () {
    // Tooltips
    $('[data-toggle="tooltip"]').tooltip({
        html: true,
        placement: 'bottom',
        trigger: 'hover',
        container: 'body',
        boundary: 'window'
    });

    // Toggle permisos
    $(document).on('click', '.btn-show-permisos', function () {
        var roleId = $(this).data('role-id');
        var row = $('#permisos-' + roleId);
        var btn = $(this);
        
        if (row.hasClass('show')) {
            row.collapse('hide');
        } else {
            $('.permisos-row.show').collapse('hide');
            row.collapse('show');
        }
    });

    $('.permisos-row').on('shown.bs.collapse', function () {
        var roleId = $(this).attr('id').replace('permisos-', '');
        $('.btn-show-permisos[data-role-id="'+ roleId +'"]')
            .html('<i class="fas fa-eye-slash"></i>')
            .removeClass('btn-outline-info').addClass('btn-info');
    }).on('hidden.bs.collapse', function () {
        var roleId = $(this).attr('id').replace('permisos-', '');
        $('.btn-show-permisos[data-role-id="'+ roleId +'"]')
            .html('<i class="fas fa-eye"></i>')
            .removeClass('btn-info').addClass('btn-outline-info');
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.fa-info-circle {
    cursor: help;
    opacity: 0.7;
    color: rgba(255, 255, 255, 0.8) !important;
}

.fa-info-circle:hover {
    opacity: 1;
    color: #ffffff !important;
}

.tooltip .tooltip-inner {
    max-width: 300px;
    padding: 10px 15px;
    text-align: left;
    background-color: #343a40;
    font-size: 0.85rem;
    border-radius: 4px;
}

.tooltip .tooltip-inner br:last-child {
    display: none;
}

.tooltip .arrow::before {
    border-bottom-color: #343a40;
}

.btn-group .btn {
    margin-right: 2px;
}
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/rol_permisos/index.blade.php ENDPATH**/ ?>