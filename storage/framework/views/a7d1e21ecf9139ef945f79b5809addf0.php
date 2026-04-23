


<?php $__env->startSection('title', 'Módulo de Acceso - Panadería Otto'); ?>
<?php $__env->startSection('page-title', 'Módulo de Gestión de Acceso'); ?>
<?php $__env->startSection('page-description', 'Administración de usuarios, roles y permisos'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/panaderia-theme.css')); ?>">
<style>
    /* ==========================================
       SECCIÓN DE ESTILOS PARA ACCESO
       ========================================== */
    
    /* Tarjetas de usuario */
    .usuario-card {
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
        height: 100%;
        background: white;
    }
    
    .usuario-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(93, 58, 26, 0.15);
    }
    
    .usuario-card .card-header {
        background: linear-gradient(135deg, var(--color-primary-medium) 0%, var(--color-primary-dark) 100%);
        color: white;
        padding: 1rem;
    }
    
    .usuario-card .card-body {
        background: var(--color-bg-light);
        padding: 1.25rem;
    }
    
    .usuario-card .card-footer {
        background: var(--color-bg-lighter);
        border-top: 1px solid var(--color-accent);
        padding: 1rem;
    }
    
    /* Badges de permisos */
    .permiso-badge {
        display: inline-block;
        margin: 2px;
        padding: 4px 8px;
        font-size: 0.75rem;
        border-radius: 20px;
        background: var(--color-accent);
        color: var(--color-primary-dark);
    }
    
    /* Contenedor de permisos en modal */
    .permisos-container {
        max-height: 350px;
        overflow-y: auto;
        border: 1px solid var(--color-accent);
        border-radius: 10px;
        padding: 10px;
        background: white;
    }
    
    .permisos-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .permisos-container::-webkit-scrollbar-track {
        background: var(--color-bg-light);
        border-radius: 3px;
    }
    
    .permisos-container::-webkit-scrollbar-thumb {
        background: var(--color-accent);
        border-radius: 3px;
    }
    
    /* Botones de acción rápida */
    .quick-actions {
        background: var(--color-bg-lighter);
        border-radius: 50px;
        padding: 0.5rem;
        display: inline-flex;
        gap: 0.5rem;
    }
    
    .quick-actions .btn {
        border-radius: 50px !important;
        padding: 0.6rem 1.8rem !important;
    }
    
    /* Estadísticas */
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border-left: 4px solid var(--color-primary-medium);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--color-primary-dark);
    }
    
    .stat-label {
        color: var(--color-secondary);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number"><?php echo e($usuarios->count()); ?></div>
                <div class="stat-label"><i class="fas fa-users mr-2"></i>Usuarios Totales</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number"><?php echo e($roles->count()); ?></div>
                <div class="stat-label"><i class="fas fa-tags mr-2"></i>Roles</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number"><?php echo e($permisos->count()); ?></div>
                <div class="stat-label"><i class="fas fa-key mr-2"></i>Permisos</div>
            </div>
        </div>
    </div>

    
    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="quick-actions">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createUsuarioModal">
                    <i class="fas fa-user-plus mr-2"></i> Nuevo Usuario
                </button>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createClienteModal">
                    <i class="fas fa-user mr-2"></i> Nuevo Cliente
                </button>
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#asignarPermisoRolModal">
                    <i class="fas fa-link mr-2"></i> Asignar Permiso a Rol
                </button>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-users mr-2"></i> Usuarios del Sistema
                    </h5>
                    <div class="card-tools">
                        <input type="text" id="searchUsuario" class="form-control form-control-sm" 
                               placeholder="Buscar usuario..." style="width: 250px;">
                    </div>
                </div>
                <div class="card-body">
    <?php if($message = Session::get('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Éxito!</strong> <?php echo e($message); ?>

        </div>
    <?php endif; ?>

    <?php if($message = Session::get('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> <?php echo e($message); ?>

        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover" id="usuariosTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Roles</th>
                    <th>Permisos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="usuariosContainer">
                <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="usuario-row">
                        <td><?php echo e($usuario->id_usuario); ?></td>
                        <td>
                            <strong><?php echo e($usuario->empleado ? $usuario->empleado->nombre . ' ' . $usuario->empleado->apellido : ($usuario->cliente ? $usuario->cliente->nombre : 'Usuario')); ?></strong>
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
                            <?php $rolesUsuario = $usuario->obtenerRoles(); ?>
                            <?php if(count($rolesUsuario) > 0): ?>
                                <?php $__currentLoopData = array_slice($rolesUsuario, 0, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge badge-primary mr-1"><?php echo e($rol); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if(count($rolesUsuario) > 2): ?>
                                    <span class="badge badge-secondary">+<?php echo e(count($rolesUsuario) - 2); ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">Sin roles</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php $totalPermisos = count($usuario->obtenerPermisos()); ?>
                            <span class="badge badge-secondary"><?php echo e($totalPermisos); ?> permisos</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1" style="gap: 4px;">
                                <button class="btn btn-warning btn-xs btn-edit-usuario" 
                                        style="padding: 2px 6px; font-size: 11px;"
                                        data-id="<?php echo e($usuario->id_usuario); ?>"
                                        data-correo="<?php echo e($usuario->correo); ?>"
                                        data-tipo="<?php echo e($usuario->tipo_usuario); ?>"
                                        data-estado="<?php echo e($usuario->estado); ?>"
                                        data-id-empleado="<?php echo e($usuario->id_empleado); ?>"
                                        data-id-cliente="<?php echo e($usuario->id_cliente); ?>"
                                        title="Editar usuario">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <?php if($usuario->tipo_usuario !== 'cliente'): ?>
                                    <button class="btn btn-primary btn-xs btn-gestionar-permisos" 
                                            style="padding: 2px 6px; font-size: 11px;"
                                            data-id="<?php echo e($usuario->id_usuario); ?>"
                                            data-nombre="<?php echo e($usuario->empleado ? $usuario->empleado->nombre : ($usuario->cliente ? $usuario->cliente->nombre : $usuario->correo)); ?>"
                                            title="Gestionar permisos">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-xs" disabled 
                                            style="padding: 2px 6px; font-size: 11px;"
                                            title="Los clientes no tienen permisos asignables">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center">
                            <div class="alert alert-info text-center py-4 mb-0">
                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                <p>No hay usuarios registrados. Crea uno nuevo usando el botón "Nuevo Usuario".</p>
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
    'clientes' => $clientes
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('usuarios.partials.modal-create-empleado', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('usuarios.partials.modal-create-cliente', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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

<?php echo $__env->make('usuarios.partials.modal-edit-usuario', [
    'empleados' => $empleados,
    'clientes' => $clientes
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>  // Búsqueda de usuarios
$(document).ready(function() {
    $('#searchUsuario').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.usuario-card-container').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // ============================================
    // CREAR USUARIO (CORREGIDO)
    // ============================================
    $('#tipo_usuario').on('change', function() {
        var tipo = $(this).val();
        $('#empleado_container, #cliente_container').hide();
        $('#id_empleado, #id_cliente').prop('required', false);
        
        if (tipo === 'empleado') {
            $('#empleado_container').show();
            $('#id_empleado').prop('required', true);
        } else if (tipo === 'cliente') {
            $('#cliente_container').show();
            $('#id_cliente').prop('required', true);
        }
    });

    // Prevenir envíos múltiples
    var isSubmitting = false;
    
    $('#formCrearUsuario').on('submit', function(e) {
        e.preventDefault();
        
        // Evitar envíos múltiples
        if (isSubmitting) {
            toastr.warning('Espere, ya se está procesando la solicitud');
            return false;
        }
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalBtnText = submitBtn.html();
        
        // Deshabilitar botón y mostrar loading
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Creando...').prop('disabled', true);
        isSubmitting = true;
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Limpiar formulario
                    form[0].reset();
                    $('#empleado_container, #cliente_container').hide();
                    
                    // Cerrar modal
                    $('#createUsuarioModal').modal('hide');
                    
                    // Mostrar mensaje de éxito
                    toastr.success(response.message || 'Usuario creado exitosamente');
                    
                    // Recargar la página después de un breve delay
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message || 'Error al crear usuario');
                }
            },
            error: function(xhr) {
                var message = 'Error al crear usuario';
                if (xhr.responseJSON?.errors) {
                    var errors = xhr.responseJSON.errors;
                    message = Object.values(errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            },
            complete: function() {
                // Restaurar botón
                submitBtn.html(originalBtnText).prop('disabled', false);
                isSubmitting = false;
            }
        });
    });

    // Limpiar formulario cuando se cierra el modal
    $('#createUsuarioModal').on('hidden.bs.modal', function() {
        $('#formCrearUsuario')[0].reset();
        $('#empleado_container, #cliente_container').hide();
        $('#id_empleado, #id_cliente').prop('required', false);
        isSubmitting = false;
    });

    // ============================================
    // CREAR EMPLEADO (desde modal usuario) - CORREGIDO
    // ============================================
    $(document).on('click', '[data-target="#createEmpleadoModal"]', function(e) {
        e.preventDefault();
        var currentModal = $(this).closest('.modal');
        if (currentModal.length) {
            var currentModalId = currentModal.attr('id');
            openNestedModal('#' + currentModalId, '#createEmpleadoModal');
        } else {
            $('#createEmpleadoModal').modal('show');
        }
    });

    $(document).on('click', '[data-target="#createClienteModal"]', function(e) {
        e.preventDefault();
        var currentModal = $(this).closest('.modal');
        if (currentModal.length) {
            var currentModalId = currentModal.attr('id');
            openNestedModal('#' + currentModalId, '#createClienteModal');
        } else {
            $('#createClienteModal').modal('show');
        }
    });

    var isSubmittingEmpleado = false;
    
    $('#formCrearEmpleado').on('submit', function(e) {
        e.preventDefault();
        
        if (isSubmittingEmpleado) return false;
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Creando...').prop('disabled', true);
        isSubmittingEmpleado = true;
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createEmpleadoModal').modal('hide');
                    toastr.success(response.message);
                    form[0].reset();
                    
                    // Agregar al select
                    var newOption = new Option(
                        response.empleado.nombre + ' ' + response.empleado.apellido,
                        response.empleado.id_empleado,
                        true,
                        true
                    );
                    $('#id_empleado, #edit_id_empleado').append(newOption);
                    
                    // CORREGIDO: $id('#id_empleado') -> $('#id_empleado')
                    $('#id_empleado').val(response.empleado.id_empleado).trigger('change');
                    
                    // Volver al modal anterior
                    returnToPreviousModal();
                } else {
                    toastr.error(response.message || 'Error al crear empleado');
                }
            },
            error: function(xhr) {
                var message = 'Error al crear empleado';
                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            },
            complete: function() {
                submitBtn.html(originalText).prop('disabled', false);
                isSubmittingEmpleado = false;
            }
        });
    });

    // ============================================
    // EDITAR USUARIO
    // ============================================
    $(document).on('change', '#edit_tipo_usuario', function() {
        const tipo = $(this).val();
        $('#edit_empleado_container, #edit_cliente_container').hide();
        $('#edit_id_empleado, #edit_id_cliente').prop('required', false);
        
        if (tipo === 'empleado') {
            $('#edit_empleado_container').show();
            $('#edit_id_empleado').prop('required', true);
        } else if (tipo === 'cliente') {
            $('#edit_cliente_container').show();
            $('#edit_id_cliente').prop('required', true);
        }
    });

    $(document).on('click', '.btn-edit-usuario', function() {
        const userId = $(this).data('id');
        const correo = $(this).data('correo');
        const tipo = $(this).data('tipo');
        const estado = $(this).data('estado');
        const idEmpleado = $(this).data('id-empleado');
        const idCliente = $(this).data('id-cliente');
        
        $('#edit_id_usuario').val(userId);
        $('#edit_correo').val(correo);
        $('#edit_tipo_usuario').val(tipo).trigger('change');
        $('#edit_estado').val(estado);
        
        if (tipo === 'empleado' && idEmpleado) {
            $('#edit_id_empleado').val(idEmpleado);
        } else if (tipo === 'cliente' && idCliente) {
            $('#edit_id_cliente').val(idCliente);
        }
        
        $('#edit_contraseña').val('');
        $('#editUsuarioModal').modal('show');
    });

    $('#formEditarUsuario').on('submit', function(e) {
        e.preventDefault();
        const userId = $('#edit_id_usuario').val();
        const formData = $(this).serialize();
        
        $.ajax({
            url: '/usuarios/' + userId,
            method: 'PUT',
            data: formData,
            success: function(response) {
                $('#editUsuarioModal').modal('hide');
                toastr.success('Usuario actualizado correctamente');
                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                var message = 'Error al actualizar usuario';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                toastr.error(message);
            }
        });
    });

  // ============================================
    // CREAR CLIENTE (desde modal usuario) - CORREGIDO
    // ============================================
    var isSubmittingCliente = false;
    
    $('#formCrearCliente').on('submit', function(e) {
        e.preventDefault();
        
        if (isSubmittingCliente) return false;
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Creando...').prop('disabled', true);
        isSubmittingCliente = true;
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createClienteModal').modal('hide');
                    toastr.success(response.message);
                    form[0].reset();
                    
                    // Crear opción nativa
                    var newOption = new Option(
                        response.cliente.nombre + ' ' + (response.cliente.apellido || ''),
                        response.cliente.id_cliente,
                        true,
                        true
                    );
                    // Agregar a los selects
                    $('#id_cliente, #edit_id_cliente').append(newOption);
                    
                    // Seleccionar automáticamente la nueva opción
                    $('#id_cliente').val(response.cliente.id_cliente).trigger('change');
                    
                    // Volver al modal anterior
                    returnToPreviousModal();
                } else {
                    toastr.error(response.message || 'Error al crear cliente');
                }
            },
            error: function(xhr) {
                var message = 'Error al crear cliente';
                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            },
            complete: function() {
                submitBtn.html(originalText).prop('disabled', false);
                isSubmittingCliente = false;
            }
        });
    });


    // Si se cancela el modal de empleado/cliente, volver al anterior
    $('#createEmpleadoModal, #createClienteModal').on('hidden.bs.modal', function() {
        returnToPreviousModal();
    });

    // ============================================
    // ASIGNAR PERMISO A ROL
    // ============================================
    $('#formAsignarPermisoRol').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#asignarPermisoRolModal').modal('hide');
                    toastr.success(response.message);
                    form[0].reset();
                    setTimeout(() => location.reload(), 1500);
                }
            },
            error: function(xhr) {
                toastr.error('Error al asignar permiso');
            }
        });
    });

    // ============================================
    // CREAR ROL (desde modal asignar permiso)
    // ============================================
    $('#formCrearRol').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: '<?php echo e(route("roles.store-ajax")); ?>',
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createRolModal').modal('hide');
                    toastr.success(response.message);
                    form[0].reset();
                    
                    var newOption = new Option(response.rol.nombre, response.rol.id_rol, true, true);
                    $('select[name="id_rol"]').append(newOption);
                    $('#asignarPermisoRolModal').modal('show');
                }
            },
            error: function() {
                toastr.error('Error al crear rol');
            }
        });
    });

    // ============================================
    // CREAR PERMISO (desde modal asignar permiso)
    // ============================================
    $('#formCrearPermiso').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: '<?php echo e(route("permisos.store-ajax")); ?>',
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createPermisoModal').modal('hide');
                    toastr.success(response.message);
                    form[0].reset();
                    
                    var newOption = new Option(response.permiso.nombre, response.permiso.id_permiso, true, true);
                    $('select[name="id_permiso"]').append(newOption);
                    $('#asignarPermisoRolModal').modal('show');
                }
            },
            error: function() {
                toastr.error('Error al crear permiso');
            }
        });
    });

  // ============================================
    // VARIABLES PARA MANEJAR MODALES ANIDADOS (MEJORADO)
    // ============================================
    var previousModal = null;

    // Función para abrir modal anidado
    function openNestedModal(currentModalId, nextModalId) {
        previousModal = currentModalId;
        $(currentModalId).modal('hide');
        $(nextModalId).modal('show');
    }

    // Función para volver al modal anterior
    function returnToPreviousModal() {
        if (previousModal && $(previousModal).length) {
            // Pequeño delay para asegurar que el modal anterior está listo
            setTimeout(function() {
                $(previousModal).modal('show');
                previousModal = null;
            }, 300);
        }
    }

    // Si se cancela el modal de empleado/cliente, volver al anterior
    $('#createEmpleadoModal, #createClienteModal').on('hidden.bs.modal', function() {
        returnToPreviousModal();
    });

    // Limpiar previousModal cuando se cierra el modal principal
    $('#createUsuarioModal').on('hidden.bs.modal', function() {
        previousModal = null;
    });


    // ============================================
    // GESTIONAR PERMISOS DE USUARIO
    // ============================================
    var usuarioIdActual = null;

    $(document).on('click', '.btn-gestionar-permisos', function() {
        usuarioIdActual = $(this).data('id');
        var usuarioNombre = $(this).data('nombre');
        
        $('#modalUsuarioNombre').text(usuarioNombre);
        $('#usuarioIdInput').val(usuarioIdActual);
        $('#gestionarPermisosModal').modal('show');
        $('#permisosList').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Cargando permisos...</p></div>');
        
        $.getJSON('/usuarios/' + usuarioIdActual + '/permisos')
            .done(function(response) {
                renderPermisos(response);
            })
            .fail(function() {
                $('#permisosList').html('<div class="alert alert-danger">Error al cargar permisos</div>');
            });
    });

    function renderPermisos(response) {
        var actuales = response.permisos_actuales || [];
        var permisos = response.todos_rol_permisos || [];
        var nombresActuales = response.permisos_actuales_nombres || [];
        
        // Mostrar permisos actuales
        if (nombresActuales.length > 0) {
            var badges = nombresActuales.map(n => `<span class="badge badge-success mr-1 mb-1">${n}</span>`).join('');
            $('#permisosActualesList').html(badges);
        } else {
            $('#permisosActualesList').html('<span class="text-muted">Ningún permiso asignado</span>');
        }
        
        // Agrupar por rol
        var porRol = {};
        permisos.forEach(function(item) {
            var rolNombre = item.rol ? item.rol.nombre : 'Sin Rol';
            var rolId = item.rol ? item.rol.id_rol : 'sin-rol';
            if (!porRol[rolNombre]) {
                porRol[rolNombre] = { id: rolId, permisos: [] };
            }
            porRol[rolNombre].permisos.push(item);
        });
        
        // Ordenar roles alfabéticamente
        var rolesOrdenados = Object.keys(porRol).sort();
        
        var html = '';
        rolesOrdenados.forEach(function(rol, index) {
            var rolId = porRol[rol].id;
            var totalPermisosRol = porRol[rol].permisos.length;
            var seleccionadosEnRol = porRol[rol].permisos.filter(p => actuales.includes(p.id_rol_permiso)).length;
            
            // Colapsado por defecto
            var show = '';
            var expanded = 'false';
            var collapsedClass = 'collapsed';
            var iconClass = 'fa-chevron-right';
            
            // Expandir automáticamente los roles que tienen permisos seleccionados
            if (seleccionadosEnRol > 0) {
                show = 'show';
                expanded = 'true';
                collapsedClass = '';
                iconClass = 'fa-chevron-down';
            }
            
            html += `<div class="card mb-2 border-panaderia">`;
            html += `<div class="card-header p-0" style="background: linear-gradient(135deg, var(--color-bg-lighter) 0%, white 100%); border-bottom: 1px solid var(--color-accent);">`;
            html += `<button class="btn btn-link btn-block text-left ${collapsedClass}" type="button" data-toggle="collapse" 
                            data-target="#collapse_${rolId}" aria-expanded="${expanded}" aria-controls="collapse_${rolId}"
                            style="text-decoration: none; color: var(--color-primary-dark); padding: 10px 15px;">`;
            html += `<div class="d-flex justify-content-between align-items-center">`;
            html += `<div>`;
            html += `<i class="fas ${iconClass} mr-2 toggle-icon" style="transition: transform 0.2s;"></i>`;
            html += `<i class="fas fa-tag mr-2 text-panaderia"></i>`;
            html += `<strong>${rol}</strong>`;
            html += `</div>`;
            html += `<div>`;
            html += `<span class="badge badge-primary mr-2">${seleccionadosEnRol}/${totalPermisosRol}</span>`;
            html += `<span class="badge badge-secondary">${totalPermisosRol}</span>`;
            html += `</div>`;
            html += `</div>`;
            html += `</button>`;
            html += `</div>`;
            
            html += `<div id="collapse_${rolId}" class="collapse ${show}" aria-labelledby="heading_${rolId}">`;
            html += `<div class="card-body p-3" style="background: white;">`;
            html += `<div class="row">`;
            
            // Ordenar permisos alfabéticamente
            porRol[rol].permisos.sort((a, b) => (a.permiso?.nombre || '').localeCompare(b.permiso?.nombre || ''));
            
            porRol[rol].permisos.forEach(function(item) {
                var checked = actuales.includes(item.id_rol_permiso) ? 'checked' : '';
                var nombrePermiso = item.permiso ? item.permiso.nombre : 'N/A';
                var descripcion = item.descripcion || '';
                
                html += `<div class="col-md-6 mb-2">`;
                html += `<div class="custom-control custom-checkbox">`;
                html += `<input type="checkbox" class="custom-control-input permiso-checkbox" 
                        id="permiso_${item.id_rol_permiso}" value="${item.id_rol_permiso}" ${checked}
                        data-rol="${rolId}">`;
                html += `<label class="custom-control-label" for="permiso_${item.id_rol_permiso}">`;
                html += `<strong>${nombrePermiso}</strong>`;
                if (descripcion) {
                    html += `<br><small class="text-muted">${descripcion}</small>`;
                }
                html += `</label>`;
                html += `</div></div>`;
            });
            
            html += `</div>`;
            html += `</div>`;
            html += `</div>`;
            html += `</div>`;
        });
        
        $('#permisosList').html(html || '<div class="text-muted text-center py-3">No hay permisos disponibles</div>');
        actualizarContador();
        // Expandir todos los roles
        $('#expandAllRoles').on('click', function() {
            $('.collapse').collapse('show');
        });

        // Colapsar todos los roles
        $('#collapseAllRoles').on('click', function() {
            $('.collapse').collapse('hide');
        });
        
        // Actualizar íconos al expandir/colapsar
        $('.collapse').on('show.bs.collapse', function() {
            $(this).siblings('.card-header').find('.toggle-icon').removeClass('fa-chevron-right').addClass('fa-chevron-down');
        }).on('hide.bs.collapse', function() {
            $(this).siblings('.card-header').find('.toggle-icon').removeClass('fa-chevron-down').addClass('fa-chevron-right');
        });
    }

    function actualizarContador() {
        $('#contadorPermisos').text($('.permiso-checkbox:checked').length);
    }

    $(document).on('change', '.permiso-checkbox', actualizarContador);

    $('#selectAllPermisos').on('click', function() {
        $('.permiso-checkbox').prop('checked', true);
        actualizarContador();
    });

    $('#deselectAllPermisos').on('click', function() {
        $('.permiso-checkbox').prop('checked', false);
        actualizarContador();
    });

    $('#btnGuardarPermisos').on('click', function() {
        var permisosSeleccionados = [];
        $('.permiso-checkbox:checked').each(function() {
            permisosSeleccionados.push($(this).val());
        });
        
        var $btn = $(this);
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...').prop('disabled', true);
        
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
                    toastr.success(response.message);
                    setTimeout(() => location.reload(), 1500);
                }
            },
            error: function() {
                toastr.error('Error al guardar permisos');
            },
            complete: function() {
                $btn.html('<i class="fas fa-save"></i> Guardar Cambios').prop('disabled', false);
            }
        });
    });

});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/usuarios/acceso.blade.php ENDPATH**/ ?>