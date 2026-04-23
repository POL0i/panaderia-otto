

<?php $__env->startSection('title', 'Usuarios con Rol-Permiso'); ?>
<?php $__env->startSection('page-title', 'Gestión de Asignacion de Rol-Permisos a Usuarios'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card animate-fade-in-up">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2"></i>
                        Usuarios con Rol-Permiso Asignado
                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('rol-permiso-usuarios.create')); ?>" class="btn btn-save btn-sm">
                            <i class="fas fa-plus mr-1"></i>
                            Asignar Rol-Permiso
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 5%">
                                        <i class="fas fa-hashtag"></i>
                                    </th>
                                    <th>
                                        <i class="fas fa-user mr-2"></i>
                                        Usuario
                                    </th>
                                    <th>
                                        <i class="fas fa-user-shield mr-2"></i>
                                        Rol
                                    </th>
                                    <th>
                                        <i class="fas fa-lock mr-2"></i>
                                        Permiso
                                    </th>
                                    <th class="text-center">Estado</th>
                                    <th style="width: 15%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $rolPermisoUsuarios ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="animate-slide-in-right">
                                        <td><?php echo e($item->id_rol_permiso_usuario); ?></td>
                                        <td>
                                            <span class="badge badge-pill badge-info"><?php echo e($item->usuario->correo ?? 'N/A'); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary"><?php echo e($item->rolPermiso->rol->nombre ?? 'N/A'); ?></span>
                                        </td>
                                        <td>
                                            <code><?php echo e($item->rolPermiso->permiso->nombre ?? 'N/A'); ?></code>
                                        </td>
                                        <td class="text-center">
                                            <?php if($item->estado == 'activo'): ?>
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Activo
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    Inactivo
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?php echo e(route('rol-permiso-usuarios.edit', $item->id_rol_permiso_usuario)); ?>" class="btn btn-outline-primary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('rol-permiso-usuarios.destroy', $item->id_rol_permiso_usuario)); ?>" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de que desea eliminar esta asignación?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-inbox text-muted mr-2"></i>
                                            No hay asignaciones de rol-permisos a usuarios registradas
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Total: <strong><?php echo e(count($rolPermisoUsuarios ?? [])); ?></strong> asignaciones
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

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/rol_permiso_usuarios/index.blade.php ENDPATH**/ ?>