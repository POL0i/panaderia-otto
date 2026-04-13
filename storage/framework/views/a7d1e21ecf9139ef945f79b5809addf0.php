
  

<?php $__env->startSection('title', 'Módulo de Acceso - Gestión de Permisos'); ?>
<?php $__env->startSection('page-title', 'Módulo de Gestión de Acceso'); ?>
<?php $__env->startSection('page-description', 'Administración completa de usuarios, roles y permisos'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .permiso-badge {
        margin: 2px;
        display: inline-block;
    }
    .modal-xl {
        max-width: 90%;
    }
    .permisos-container {
        max-height: 400px;
        overflow-y: auto;
    }
    .rol-header {
        background-color: #f8f9fa;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
    }
    .permiso-item {
        padding: 8px;
        border-bottom: 1px solid #eee;
    }
    .permiso-item:hover {
        background-color: #f5f5f5;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createUsuarioModal">
                    <i class="fas fa-user-plus"></i> Nuevo Usuario
                </button>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#createEmpleadoModal">
                    <i class="fas fa-user-tie"></i> Nuevo Empleado
                </button>
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#createRolModal">
                    <i class="fas fa-tag"></i> Nuevo Rol
                </button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createPermisoModal">
                    <i class="fas fa-key"></i> Nuevo Permiso
                </button>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#asignarPermisoRolModal">
                    <i class="fas fa-link"></i> Asignar Permiso a Rol
                </button>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i> Usuarios del Sistema
                    </h3>
                    <div class="card-tools">
                        <input type="text" id="searchUsuario" class="form-control form-control-sm" 
                               placeholder="Buscar usuario..." style="width: 200px;">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre/Empleado</th>
                                    <th>Correo</th>
                                    <th>Tipo</th>
                                    <th>Estado</th>
                                    <th>Roles</th>
                                    <th>Permisos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($usuario->id_usuario); ?></td>
                                        <td>
                                            <?php if($usuario->empleado): ?>
                                                <strong><?php echo e($usuario->empleado->nombre); ?> <?php echo e($usuario->empleado->apellido ?? ''); ?></strong>
                                                <br><small class="text-muted">Empleado</small>
                                            <?php elseif($usuario->cliente): ?>
                                                <strong><?php echo e($usuario->cliente->nombre ?? 'Cliente'); ?></strong>
                                                <br><small class="text-muted">Cliente</small>
                                            <?php else: ?>
                                                <span class="text-muted">Sin asignar</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($usuario->correo); ?></td>
                                        <td>
                                            <span class="badge badge-info"><?php echo e(ucfirst($usuario->tipo_usuario)); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo e($usuario->estado == 'activo' ? 'badge-success' : 'badge-danger'); ?>">
                                                <?php echo e(ucfirst($usuario->estado)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                                $rolesUsuario = $usuario->obtenerRoles();
                                            ?>
                                            <?php if(count($rolesUsuario) > 0): ?>
                                                <?php $__currentLoopData = $rolesUsuario; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge badge-primary"><?php echo e($rol); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Sin roles</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                                $permisosUsuario = $usuario->obtenerPermisos();
                                                $totalPermisos = count($permisosUsuario);
                                            ?>
                                            <?php if($totalPermisos > 0): ?>
                                                <span class="badge badge-secondary"><?php echo e($totalPermisos); ?> permisos</span>
                                                <button type="button" class="btn btn-xs btn-outline-info btn-ver-permisos" 
                                                        data-toggle="popover" 
                                                        data-trigger="hover"
                                                        title="Permisos de <?php echo e($usuario->correo); ?>"
                                                        data-content="<?php echo e(implode(', ', array_slice($permisosUsuario, 0, 10))); ?><?php echo e($totalPermisos > 10 ? '...' : ''); ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            <?php else: ?>
                                                <span class="text-muted">Sin permisos</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary btn-gestionar-permisos" 
                                                    data-id="<?php echo e($usuario->id_usuario); ?>"
                                                    data-nombre="<?php echo e($usuario->empleado ? $usuario->empleado->nombre : ($usuario->cliente ? $usuario->cliente->nombre : $usuario->correo)); ?>">
                                                <i class="fas fa-lock"></i> Permisos
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle"></i> No hay usuarios registrados.
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php echo $__env->make('usuarios.partials.modal-create-usuario', [
    'empleados' => $empleados,
    'clientes' => $clientes,
    'rolPermisos' => $rolPermisos
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('usuarios.partials.modal-create-empleado', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('usuarios.partials.modal-create-rol', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('usuarios.partials.modal-create-permiso', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('usuarios.partials.modal-asignar-permiso-rol', [
    'roles' => $roles,
    'permisos' => $permisos
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('usuarios.partials.modal-gestionar-permisos', [
    'rolPermisos' => $rolPermisos,
    'roles' => $roles 
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Inicializar popovers
    $('[data-toggle="popover"]').popover({
        placement: 'top',
        container: 'body'
    });

    // Búsqueda en tabla
    $('#searchUsuario').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // ============================================
    // MODAL DE PERMISOS - VERSIÓN SIMPLIFICADA
    // ============================================
    
    var usuarioIdActual = null;

    // Abrir modal
    $('.btn-gestionar-permisos').on('click', function() {
        usuarioIdActual = $(this).data('id');
        var usuarioNombre = $(this).data('nombre');
        
        $('#modalUsuarioNombre').text(usuarioNombre);
        $('#usuarioIdInput').val(usuarioIdActual);
        
        // Mostrar modal inmediatamente
        $('#gestionarPermisosModal').modal('show');
        $('#permisosList').html('<div class="text-center py-3"><i class="fas fa-spinner fa-spin"></i> Cargando permisos...</div>');
        $('#permisosActualesList').text('Cargando...');
        
        // Cargar datos
        $.getJSON('/usuarios/' + usuarioIdActual + '/permisos')
            .done(function(response) {
                console.log('Datos recibidos:', response);
                
                // Mostrar permisos actuales
                var nombres = response.permisos_actuales_nombres || [];
                $('#permisosActualesList').text(nombres.length ? nombres.join(', ') : 'Ninguno');
                
                // Construir HTML
                var html = '';
                var permisos = response.todos_rol_permisos || [];
                var actuales = response.permisos_actuales || [];
                
                // Agrupar por rol
                var porRol = {};
                permisos.forEach(function(item) {
                    var rolNombre = item.rol ? item.rol.nombre : 'Sin Rol';
                    if (!porRol[rolNombre]) porRol[rolNombre] = [];
                    porRol[rolNombre].push(item);
                });
                
                // Generar HTML por rol
                for (var rol in porRol) {
                    html += '<div class="rol-section mb-3">';
                    html += '<div class="rol-header bg-light p-2 mb-2" style="border-left: 4px solid #007bff;">';
                    html += '<strong><i class="fas fa-tag"></i> ' + rol + '</strong>';
                    html += '<span class="badge badge-info ml-2">' + porRol[rol].length + '</span>';
                    html += '</div><div class="row">';
                    
                    porRol[rol].forEach(function(item) {
                        var checked = actuales.includes(item.id_rol_permiso) ? 'checked' : '';
                        var nombrePermiso = item.permiso ? item.permiso.nombre : 'N/A';
                        
                        html += '<div class="col-md-6 mb-2">';
                        html += '<div class="custom-control custom-checkbox">';
                        html += '<input type="checkbox" class="custom-control-input permiso-checkbox" ';
                        html += 'id="permiso_' + item.id_rol_permiso + '" ';
                        html += 'value="' + item.id_rol_permiso + '" ' + checked + '>';
                        html += '<label class="custom-control-label" for="permiso_' + item.id_rol_permiso + '">';
                        html += '<strong>' + nombrePermiso + '</strong>';
                        if (item.descripcion) {
                            html += '<br><small class="text-muted">' + item.descripcion + '</small>';
                        }
                        html += '</label></div></div>';
                    });
                    
                    html += '</div></div>';
                }
                
                $('#permisosList').html(html || '<div class="text-muted text-center py-3">No hay permisos disponibles</div>');
                $('#contadorPermisos').text($('.permiso-checkbox:checked').length);
            })
            .fail(function(xhr, status, error) {
                console.error('Error:', status, error);
                $('#permisosList').html('<div class="alert alert-danger">Error al cargar los permisos</div>');
            });
    });

    // Seleccionar todos
    $(document).on('click', '#selectAllPermisos', function() {
        $('.permiso-checkbox').prop('checked', true);
        $('#contadorPermisos').text($('.permiso-checkbox:checked').length);
    });

    // Deseleccionar todos
    $(document).on('click', '#deselectAllPermisos', function() {
        $('.permiso-checkbox').prop('checked', false);
        $('#contadorPermisos').text(0);
    });

    // Actualizar contador
    $(document).on('change', '.permiso-checkbox', function() {
        $('#contadorPermisos').text($('.permiso-checkbox:checked').length);
    });

    // Guardar cambios
    $(document).on('click', '#btnGuardarPermisos', function() {
        var permisosSeleccionados = [];
        $('.permiso-checkbox:checked').each(function() {
            permisosSeleccionados.push($(this).val());
        });
        
        $.ajax({
            url: '/usuarios/' + usuarioIdActual + '/actualizar-permisos',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                rol_permiso_ids: permisosSeleccionados
            },
            success: function(response) {
                if (response.success) {
                    $('#gestionarPermisosModal').modal('hide');
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Error: ' + (response.message || 'Error desconocido'));
                }
            },
            error: function() {
                alert('Error al guardar los permisos');
            }
        });
    });

});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/usuarios/acceso.blade.php ENDPATH**/ ?>