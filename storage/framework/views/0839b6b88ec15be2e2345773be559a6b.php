<?php $__env->startSection('title', 'Asignar Permisos a Rol'); ?>
<?php $__env->startSection('page-title', 'Asignar Permisos a Rol'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10">
            <div class="card animate-fade-in-up">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Asignar Múltiples Permisos a un Rol
                    </h3>
                </div>
                <form action="<?php echo e(route('rol_permisos.store')); ?>" method="POST" id="formAsignarPermisos">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        
                       <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_rol">
                                        <i class="fas fa-user-shield mr-2 text-primary"></i>
                                        Rol
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <select class="form-control <?php $__errorArgs = ['id_rol'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                id="id_rol" name="id_rol" required>
                                            <option value="">Seleccionar rol...</option>
                                            <?php $__currentLoopData = $roles ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($rol->id_rol); ?>" 
                                                    <?php echo e(old('id_rol', $selectedRol ?? '') == $rol->id_rol ? 'selected' : ''); ?>>
                                                    <?php echo e($rol->nombre); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-primary" 
                                                    data-toggle="modal" data-target="#createRolModal"
                                                    title="Crear nuevo rol">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <?php $__errorArgs = ['id_rol'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="invalid-feedback d-block"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <label>
                                <i class="fas fa-lock mr-2 text-primary"></i>
                                Permisos a Asignar
                                <span class="text-danger">*</span>
                            </label>
                            
                            
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded">
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mr-1" id="selectAllPermisos">
                                        <i class="fas fa-check-square"></i> Seleccionar Todos
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mr-1" id="deselectAllPermisos">
                                        <i class="fas fa-square"></i> Deseleccionar Todos
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success" id="selectActivePermisos">
                                        <i class="fas fa-toggle-on"></i> Solo Activos
                                    </button>
                                </div>
                                <div>
                                    <span class="badge badge-primary" id="contadorPermisos">0</span> 
                                    <small class="text-muted">seleccionados de <?php echo e(count($permisos ?? [])); ?></small>
                                </div>
                            </div>
                            
                            
                            <div class="row permisos-container" style="max-height: 400px; overflow-y: auto;">
                                <?php $__empty_1 = true; $__currentLoopData = $permisos ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permiso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" 
                                                   class="custom-control-input permiso-checkbox" 
                                                   id="permiso_<?php echo e($permiso->id_permiso); ?>" 
                                                   name="permisos[]" 
                                                   value="<?php echo e($permiso->id_permiso); ?>"
                                                   <?php echo e(in_array($permiso->id_permiso, old('permisos', [])) ? 'checked' : ''); ?>>
                                            <label class="custom-control-label" for="permiso_<?php echo e($permiso->id_permiso); ?>">
                                                <code><?php echo e($permiso->nombre); ?></code>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="col-12">
                                        <div class="alert alert-warning mb-0">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            No hay permisos disponibles. 
                                            <a href="<?php echo e(route('permisos.create')); ?>" class="alert-link">Crear nuevo permiso</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php $__errorArgs = ['permisos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="text-danger d-block mt-2"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="form-group">
                            <label for="estado">
                                <i class="fas fa-toggle-on mr-2 text-primary"></i>
                                Estado por Defecto
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control w-auto <?php $__errorArgs = ['estado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="estado" name="estado" required>
                                <option value="activo" <?php echo e(old('estado') == 'activo' ? 'selected' : ''); ?>>Activo</option>
                                <option value="inactivo" <?php echo e(old('estado') == 'inactivo' ? 'selected' : ''); ?>>Inactivo</option>
                            </select>
                            <?php $__errorArgs = ['estado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="invalid-feedback d-block"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-save" id="btnGuardar">
                            <i class="fas fa-check mr-2"></i>
                            Guardar Asignaciones
                        </button>
                        <a href="<?php echo e(route('rol_permisos.index')); ?>" class="btn btn-back">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php echo $__env->make('usuarios.partials.modal-create-rol', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(function() {
    // ============================================
    // CONTADOR DE PERMISOS SELECCIONADOS
    // ============================================
    function actualizarContador() {
        var total = $('.permiso-checkbox:checked').length;
        $('#contadorPermisos').text(total);
        
        // Cambiar color del badge según cantidad
        if (total > 0) {
            $('#contadorPermisos').removeClass('badge-secondary').addClass('badge-success');
        } else {
            $('#contadorPermisos').removeClass('badge-success').addClass('badge-secondary');
        }
    }

    // Eventos de selección/deselección
    $(document).on('change', '.permiso-checkbox', actualizarContador);

    // Seleccionar todos
    $('#selectAllPermisos').on('click', function() {
        $('.permiso-checkbox').prop('checked', true);
        actualizarContador();
    });

    // Deseleccionar todos
    $('#deselectAllPermisos').on('click', function() {
        $('.permiso-checkbox').prop('checked', false);
        actualizarContador();
    });

    // Seleccionar solo activos (placeholder - puedes personalizar según lógica)
    $('#selectActivePermisos').on('click', function() {
        // Por ahora selecciona todos, puedes filtrar según tu lógica
        $('.permiso-checkbox').each(function() {
            // Ejemplo: seleccionar solo los primeros 5
            var index = $(this).closest('.col-md-6').index();
            $(this).prop('checked', index < 5);
        });
        actualizarContador();
    });

    // Inicializar contador
    actualizarContador();

    // ============================================
    // VALIDACIÓN DEL FORMULARIO
    // ============================================
    $('#formAsignarPermisos').on('submit', function(e) {
        var selectedPermisos = $('.permiso-checkbox:checked').length;
        
        if (selectedPermisos === 0) {
            e.preventDefault();
            toastr.error('Debe seleccionar al menos un permiso');
            return false;
        }
        
        var selectedRol = $('#id_rol').val();
        if (!selectedRol) {
            e.preventDefault();
            toastr.error('Debe seleccionar un rol');
            return false;
        }

        // Deshabilitar botón para evitar doble envío
        var btn = $('#btnGuardar');
        btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...').prop('disabled', true);
        
        // El formulario se envía normalmente
        return true;
    });

    // ============================================
    // CREAR ROL DESDE MODAL
    // ============================================
    $('#formCrearRol').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('button[type="submit"]');
        var originalText = btn.html();
        
        btn.html('<i class="fas fa-spinner fa-spin"></i> Creando...').prop('disabled', true);
        
        $.ajax({
            url: '<?php echo e(route("roles.store-ajax")); ?>',
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Cerrar modal
                    $('#createRolModal').modal('hide');
                    
                    // Agregar nuevo rol al select
                    var newOption = new Option(
                        response.rol.nombre, 
                        response.rol.id_rol, 
                        true, 
                        true
                    );
                    $('#id_rol').append(newOption).val(response.rol.id_rol);
                    
                    // Limpiar formulario
                    form[0].reset();
                    
                    // Mostrar éxito
                    toastr.success(response.message || 'Rol creado exitosamente');
                } else {
                    toastr.error(response.message || 'Error al crear rol');
                }
            },
            error: function(xhr) {
                var message = 'Error al crear rol';
                if (xhr.responseJSON?.errors) {
                    var errors = xhr.responseJSON.errors;
                    message = Object.values(errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            },
            complete: function() {
                btn.html(originalText).prop('disabled', false);
            }
        });
    });

    // Limpiar formulario del modal al cerrar
    $('#createRolModal').on('hidden.bs.modal', function() {
        $('#formCrearRol')[0].reset();
    });

    // ============================================
    // BÚSQUEDA RÁPIDA DE PERMISOS (OPCIONAL)
    // ============================================
    // Incluir campo de búsqueda si hay muchos permisos
    if ($('.permiso-checkbox').length > 20) {
        var searchBox = `
            <div class="mb-3">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input type="text" id="buscarPermiso" class="form-control" 
                           placeholder="Buscar permiso...">
                </div>
            </div>
        `;
        $('.permisos-container').before(searchBox);
        
        $('#buscarPermiso').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.permiso-checkbox').each(function() {
                var label = $(this).next('label').text().toLowerCase();
                $(this).closest('.col-md-6').toggle(label.indexOf(value) > -1);
            });
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.permisos-container {
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    padding: 15px;
    background: #fafafa;
}

.permisos-container::-webkit-scrollbar {
    width: 8px;
}

.permisos-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.permisos-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.permisos-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.custom-control-label code {
    font-size: 0.9rem;
    color: #495057;
}

.custom-control-input:checked ~ .custom-control-label code {
    color: #007bff;
    font-weight: 600;
}

.badge-success {
    transition: all 0.3s ease;
}
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/rol_permisos/create.blade.php ENDPATH**/ ?>