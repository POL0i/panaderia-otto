<?php $__env->startSection('title', 'Asignar Permisos a Rol'); ?>
<?php $__env->startSection('page-title', 'Asignar Permisos a Rol'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ==========================================
       ESTILOS ESPECÍFICOS - ASIGNAR PERMISOS
       ========================================== */
    
    /* Contenedor de permisos */
    .permisos-container {
        border: 1px solid var(--color-border);
        border-radius: var(--border-radius-sm);
        padding: 15px;
        background: var(--color-bg-lighter);
        max-height: 400px;
        overflow-y: auto;
    }
    
    /* Scrollbar del contenedor */
    .permisos-container::-webkit-scrollbar { width: 8px; }
    .permisos-container::-webkit-scrollbar-track {
        background: var(--color-bg-light);
        border-radius: 4px;
    }
    .permisos-container::-webkit-scrollbar-thumb {
        background: var(--color-border);
        border-radius: 4px;
    }
    .permisos-container::-webkit-scrollbar-thumb:hover {
        background: var(--color-accent);
    }
    
    /* Toolbar de acciones */
    .permisos-toolbar {
        background: var(--color-bg-light);
        border-radius: var(--border-radius-sm);
        padding: 0.75rem;
        margin-bottom: 1rem;
        border: 1px solid var(--color-border);
    }
    
    /* Contador de permisos */
    .permisos-counter {
        background: var(--color-primary);
        color: var(--text-on-primary);
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .permisos-counter.active { background: var(--badge-success); }
    
    /* Checkbox personalizado */
    .custom-checkbox-item {
        transition: background 0.2s ease;
        padding: 0.35rem 0.5rem;
        border-radius: var(--border-radius-sm);
    }
    .custom-checkbox-item:hover {
        background: var(--color-bg-light);
    }
    
    .custom-control-label code {
        font-size: 0.85rem;
        color: var(--color-primary-dark);
        background: var(--color-bg-lighter);
        padding: 2px 6px;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    
    .custom-control-input:checked ~ .custom-control-label code {
        color: var(--text-on-primary);
        background: var(--color-primary);
        font-weight: 600;
    }
    
    /* Búsqueda rápida */
    .permisos-search {
        margin-bottom: 0.75rem;
    }
    .permisos-search .input-group-text {
        background: var(--color-accent);
        color: var(--color-primary-dark);
        border-color: var(--color-accent);
    }
    .permisos-search .form-control {
        border-color: var(--color-accent);
    }
    .permisos-search .form-control:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 0.2rem var(--color-focus-ring);
    }
    
    /* Card header */
    .card-header-primary {
        background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
        color: var(--text-on-primary);
    }
    .card-header-primary .card-title { color: var(--text-on-primary); }
    
    /* Labels con iconos */
    .label-icon { color: var(--color-primary); }
    
    /* Badge de filtro activo */
    .btn-outline-primary.active {
        background: var(--color-primary);
        color: var(--text-on-primary);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10">
            <div class="card animate-fade-in-up">
                <div class="card-header card-header-primary">
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
                                        <i class="fas fa-user-shield mr-2 label-icon"></i>
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
                                <i class="fas fa-lock mr-2 label-icon"></i>
                                Permisos a Asignar
                                <span class="text-danger">*</span>
                            </label>
                            
                            
                            <div class="permisos-toolbar d-flex justify-content-between align-items-center">
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
                                    <span class="permisos-counter" id="contadorPermisos">0</span> 
                                    <small class="text-muted ml-1">de <?php echo e(count($permisos ?? [])); ?></small>
                                </div>
                            </div>
                            
                            
                            <div class="permisos-container">
                                <div class="row">
                                    <?php $__empty_1 = true; $__currentLoopData = $permisos ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permiso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="col-md-6 col-lg-4 mb-1">
                                            <div class="custom-checkbox-item">
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
                                <i class="fas fa-toggle-on mr-2 label-icon"></i>
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
                            <i class="fas fa-save mr-2"></i>
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
        var $contador = $('#contadorPermisos');
        
        $contador.text(total);
        
        if (total > 0) {
            $contador.addClass('active');
        } else {
            $contador.removeClass('active');
        }
    }

    $(document).on('change', '.permiso-checkbox', actualizarContador);

    // Seleccionar todos
    $('#selectAllPermisos').on('click', function() {
        $('.permiso-checkbox').prop('checked', true);
        actualizarContador();
        toastr.success('Todos los permisos seleccionados');
    });

    // Deseleccionar todos
    $('#deselectAllPermisos').on('click', function() {
        $('.permiso-checkbox').prop('checked', false);
        actualizarContador();
        toastr.info('Todos los permisos deseleccionados');
    });

    // Seleccionar solo activos (placeholder)
    $('#selectActivePermisos').on('click', function() {
        $('.permiso-checkbox').each(function() {
            var index = $(this).closest('.col-lg-4').index();
            $(this).prop('checked', index < 5);
        });
        actualizarContador();
        toastr.info('Filtro aplicado');
    });

    actualizarContador();

    // ============================================
    // VALIDACIÓN DEL FORMULARIO
    // ============================================
    $('#formAsignarPermisos').on('submit', function(e) {
        var selectedPermisos = $('.permiso-checkbox:checked').length;
        var selectedRol = $('#id_rol').val();
        
        if (!selectedRol) {
            e.preventDefault();
            toastr.error('Debe seleccionar un rol');
            return false;
        }
        
        if (selectedPermisos === 0) {
            e.preventDefault();
            toastr.error('Debe seleccionar al menos un permiso');
            return false;
        }

        var btn = $('#btnGuardar');
        btn.html('<i class="fas fa-spinner fa-spin"></i> Guardando...').prop('disabled', true);
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
                    $('#createRolModal').modal('hide');
                    
                    var newOption = new Option(
                        response.rol.nombre, 
                        response.rol.id_rol, 
                        true, 
                        true
                    );
                    $('#id_rol').append(newOption).val(response.rol.id_rol);
                    
                    form[0].reset();
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

    $('#createRolModal').on('hidden.bs.modal', function() {
        $('#formCrearRol')[0].reset();
    });

    // ============================================
    // BÚSQUEDA RÁPIDA DE PERMISOS
    // ============================================
    if ($('.permiso-checkbox').length > 20) {
        var searchBox = `
            <div class="permisos-search">
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
        $('.permisos-container').prepend(searchBox);
        
        $('#buscarPermiso').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.permiso-checkbox').each(function() {
                var label = $(this).next('label').text().toLowerCase();
                $(this).closest('.col-lg-4').toggle(label.indexOf(value) > -1);
            });
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/rol_permisos/create.blade.php ENDPATH**/ ?>