{{-- resources/views/usuarios/acceso.blade.php --}}
@extends('layouts.adminlte')

@section('title', 'Módulo de Acceso - Panadería Otto')
{{-- El título y descripción se muestran alineados con los botones más abajo --}}

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/panaderia-theme.css') }}">
<style>
    /* ==========================================
        MODERN UI/UX PANADERÍA (Coffee & Cream)
       ========================================== */
    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8f6f0;
    }
    
    .admin-title {
        font-weight: 700;
        color: #4a3525;
        letter-spacing: -0.5px;
    }

    /* Tarjetas Glassmorphism */
    .card-modern {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255, 0.6);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(74, 53, 37, 0.08);
        overflow: hidden;
        margin-bottom: 25px;
    }

    .card-modern .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.04);
        padding: 1.5rem;
    }

    /* Tabla moderna compacta */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0 8px;
        margin-top: -8px;
        width: 100%;
    }
    .table-modern thead th {
        border: none;
        color: #8c7361;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 15px;
    }
    .table-modern tbody tr {
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        border-radius: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .table-modern tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(74, 53, 37, 0.08);
    }
    .table-modern tbody td {
        border: none;
        padding: 4px 10px; /* Reducido para comprimir el espacio vertical */
        vertical-align: middle;
        font-size: 0.9rem;
        color: #4a3525;
    }
    .table-modern tbody td:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }
    .table-modern tbody td:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    /* Botones píldora */
    .quick-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-bottom: 1.5rem;
    }
    .quick-actions .btn {
        border-radius: 50px;
        padding: 0.6rem 1.8rem;
        font-weight: 500;
        font-size: 0.95rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .quick-actions .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .btn-coffee { background: #4a3525; color: white; border: none; }
    .btn-coffee:hover { background: #362519; color: white; }
    .btn-caramel { background: #c88647; color: white; border: none; }
    .btn-caramel:hover { background: #b07238; color: white; }
    .btn-cream { background: white; color: #4a3525; border: 1px solid #eaddd3; }
    .btn-cream:hover { background: #f4f1ea; color: #4a3525; }

    /* Buscador */
    .search-modern {
        background: #f4f1ea;
        border: none;
        border-radius: 50px;
        padding: 0.5rem 1.2rem;
        font-size: 0.9rem;
        width: 250px;
        color: #4a3525;
    }
    .search-modern:focus {
        outline: none;
        background: white;
        box-shadow: 0 0 0 2px #c88647;
    }

    /* Badges compactos */
    .badge-compact {
        padding: 4px 8px;
        font-weight: 500;
        font-size: 0.75rem;
        border-radius: 8px;
    }
    .badge-coffee { background: rgba(74, 53, 37, 0.1); color: #4a3525; }
    .badge-caramel { background: rgba(200, 134, 71, 0.15); color: #b07238; }
    .badge-gray { background: #f4f1ea; color: #8c7361; }
    .badge-success-soft { background: rgba(40, 167, 69, 0.1); color: #28a745; }
    .badge-danger-soft { background: rgba(220, 53, 69, 0.1); color: #dc3545; }

    /* Botón flotante para correo */
    .email-btn {
        background: #f4f1ea;
        color: #8c7361;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        cursor: pointer;
    }
    .email-btn:hover {
        background: #c88647;
        color: white;
    }

    /* Estadísticas al pie */
    .stats-footer {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        margin-top: 0.5rem;
        flex-wrap: wrap;
    }
    .stat-compact {
        background: white;
        border-radius: 16px;
        padding: 0.8rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(74, 53, 37, 0.05);
        border: 1px solid rgba(74, 53, 37, 0.05);
    }
    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    .stat-info h4 { margin: 0; font-size: 1.3rem; font-weight: 700; color: #4a3525; line-height: 1; }
    .stat-info p { margin: 0; font-size: 0.75rem; color: #8c7361; font-weight: 600; text-transform: uppercase; }

    .icon-users { background: rgba(74, 53, 37, 0.1); color: #4a3525; }
    .icon-roles { background: rgba(200, 134, 71, 0.15); color: #c88647; }
    .icon-perms { background: rgba(40, 167, 69, 0.1); color: #28a745; }
    
    /* Avatar / Icono de Usuario */
    .user-avatar {
        width: 35px;
        height: 35px;
        border-radius: 10px;
        background: #f4f1ea;
        color: #c88647;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 10px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-fade-in" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- Stats movidas al final --}}

    {{-- Botones de acción rápida modernos movidos a la cabecera --}}
    <div class="row mb-3 align-items-center">
        <div class="col-md-5">
            <h2 class="mb-1" style="color: #4a3525; font-weight: 700;">Módulo de Gestión de Acceso</h2>
            <p class="text-muted mb-0" style="color: #7b6b59 !important;">Administración de usuarios, roles y permisos</p>
        </div>
        <div class="col-md-7 text-right">
            <div class="quick-actions justify-content-end mb-0">
                <button type="button" class="btn btn-coffee" data-toggle="modal" data-target="#createUsuarioModal">
                    <i class="fas fa-user-plus mr-2"></i> Nuevo Usuario
                </button>
                <a href="{{ route('personas.index') }}" class="btn btn-cream">
                    <i class="fas fa-address-book mr-2"></i> Directorio
                </a>
                <a href="{{ route('rol_permisos.index') }}" class="btn btn-caramel">
                    <i class="fas fa-shield-alt mr-2"></i> Roles y Permisos
                </a>
            </div>
        </div>
    </div>

    {{-- Lista de Usuarios (Comprimida) --}}
    <div class="row">
        <div class="col-12">
            <div class="card card-modern glass-card">
                <div class="card-header bakery-header d-flex justify-content-between align-items-center" style="border-bottom: 1px solid rgba(74, 53, 37, 0.1);">
                    <h4 class="mb-0 admin-title font-weight-bold" style="color: #4a3525;">
                        Control de Accesos
                    </h4>
                    <div class="card-tools ml-auto">
                        <div class="input-group" style="background: #f4f1ea; border-radius: 50px; overflow: hidden;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-transparent border-0 text-muted"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" id="searchUsuario" class="form-control border-0 bg-transparent shadow-none" placeholder="Buscar usuario...">
                        </div>
                    </div>
                </div>
                <div class="card-body p-2 p-md-3">
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" style="border-radius: 12px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle mr-2"></i> {{ $message }}
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 12px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table-modern w-100" id="usuariosTable">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th class="text-center">Correo</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Roles</th>
                    <th>Permisos</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody id="usuariosContainer">
                @forelse($usuarios as $usuario)
                    @php 
                        $nombreCompleto = $usuario->empleado ? $usuario->empleado->nombre . ' ' . $usuario->empleado->apellido : ($usuario->cliente ? $usuario->cliente->nombre : 'Usuario');
                        $inicial = substr($nombreCompleto, 0, 1);
                    @endphp
                    <tr class="usuario-row">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar">{{ strtoupper($inicial) }}</div>
                                <strong>{{ $nombreCompleto }}</strong>
                            </div>
                        </td>
                        <td class="text-center">
                            {{-- Correo comprimido con tooltip --}}
                            <button class="email-btn" data-toggle="tooltip" data-placement="top" title="{{ $usuario->correo }}">
                                <i class="fas fa-envelope"></i>
                            </button>
                        </td>
                        <td>
                            <span class="badge-compact {{ $usuario->tipo_usuario == 'empleado' ? 'badge-coffee' : 'badge-caramel' }}">
                                {{ ucfirst($usuario->tipo_usuario) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-compact {{ $usuario->estado == 'activo' ? 'badge-success-soft' : 'badge-danger-soft' }}">
                                <i class="fas fa-circle mr-1" style="font-size: 8px;"></i> {{ ucfirst($usuario->estado) }}
                            </span>
                        </td>
                        <td>
                            {{-- Roles comprimidos --}}
                            @php $rolesUsuario = $usuario->obtenerRoles(); @endphp
                            @if(count($rolesUsuario) > 0)
                                <div class="d-flex flex-wrap gap-1" style="gap: 4px;">
                                    @foreach(array_slice($rolesUsuario, 0, 2) as $rol)
                                        <span class="badge-compact badge-gray">{{ $rol }}</span>
                                    @endforeach
                                    @if(count($rolesUsuario) > 2)
                                        <span class="badge-compact badge-gray" data-toggle="tooltip" title="{{ implode(', ', array_slice($rolesUsuario, 2)) }}">+{{ count($rolesUsuario) - 2 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted small">Sin roles</span>
                            @endif
                        </td>
                        <td>
                            @php $totalPermisos = count($usuario->obtenerPermisos()); @endphp
                            <span class="badge-compact badge-gray" data-toggle="tooltip" title="Ver o gestionar permisos"><i class="fas fa-key mr-1"></i> {{ $totalPermisos }}</span>
                        </td>
                        <td class="text-right">
                            <div class="d-flex justify-content-end gap-1" style="gap: 6px;">
                                <button class="btn btn-sm btn-outline-secondary btn-edit-usuario" 
                                        style="border-radius: 8px; width: 32px; height: 32px; padding: 0;"
                                        data-id="{{ $usuario->id_usuario }}"
                                        data-correo="{{ $usuario->correo }}"
                                        data-tipo="{{ $usuario->tipo_usuario }}"
                                        data-estado="{{ $usuario->estado }}"
                                        data-id-empleado="{{ $usuario->id_empleado ?? '' }}"
                                        data-id-cliente="{{ $usuario->id_cliente ?? '' }}"
                                        data-toggle="tooltip" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </button>
                                
                                @if($usuario->tipo_usuario !== 'cliente')
                                    <button class="btn btn-sm btn-outline-warning btn-gestionar-permisos" 
                                            style="border-radius: 8px; width: 32px; height: 32px; padding: 0; color: #c88647; border-color: #c88647;"
                                            data-id="{{ $usuario->id_usuario }}"
                                            data-nombre="{{ $nombreCompleto }}"
                                            data-toggle="tooltip" title="Permisos">
                                        <i class="fas fa-shield-alt"></i>
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled 
                                            style="border-radius: 8px; width: 32px; height: 32px; padding: 0; opacity: 0.5;"
                                            data-toggle="tooltip" title="Sin permisos asignables">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <div class="py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3" style="color: #eaddd3;"></i>
                                <h5>No hay usuarios registrados</h5>
                                <p class="mb-0">Crea uno nuevo usando el botón "Nuevo Usuario".</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
            </div>
        </div>
    </div>
    
    {{-- Estadísticas como Conclusión al final --}}
    <div class="row">
        <div class="col-12">
            <div class="stats-footer">
                <div class="stat-compact">
                    <div class="stat-icon icon-users"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <h4>{{ $usuarios->count() }}</h4>
                        <p>Usuarios</p>
                    </div>
                </div>
                <div class="stat-compact">
                    <div class="stat-icon icon-roles"><i class="fas fa-tags"></i></div>
                    <div class="stat-info">
                        <h4>{{ $roles->count() }}</h4>
                        <p>Roles</p>
                    </div>
                </div>
                <div class="stat-compact">
                    <div class="stat-icon icon-perms"><i class="fas fa-key"></i></div>
                    <div class="stat-info">
                        <h4>{{ $permisos->count() }}</h4>
                        <p>Permisos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Incluir modales --}}
@include('usuarios.partials.modal-create-usuario', [
    'empleados' => $empleados,
    'clientes' => $clientes
])
@include('usuarios.partials.modal-create-empleado')
@include('usuarios.partials.modal-create-cliente')
@include('usuarios.partials.modal-create-rol')
@include('usuarios.partials.modal-create-permiso')
@include('usuarios.partials.modal-asignar-permiso-rol', [
    'roles' => $roles,
    'permisos' => $permisos
])
@include('usuarios.partials.modal-gestionar-permisos', [
    'rolPermisos' => $rolPermisos,
    'roles' => $roles
])
{{-- Incluir el modal de edición --}}
@include('usuarios.partials.modal-edit-usuario', [
    'empleados' => $empleados,
    'clientes' => $clientes
])

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Activar tooltips de Bootstrap
    $('[data-toggle="tooltip"]').tooltip();

    // Búsqueda de usuarios EN LA TABLA
    $('#searchUsuario').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.usuario-row').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // ============================================
    // CREAR USUARIO
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

    // Prevenir envíos múltiples del formulario de usuario
    var isSubmitting = false;
    
    $('#formCrearUsuario').on('submit', function(e) {
        e.preventDefault();
        
        if (isSubmitting) {
            toastr.warning('Espere, ya se está procesando la solicitud');
            return false;
        }
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalBtnText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Creando...').prop('disabled', true);
        isSubmitting = true;
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    form[0].reset();
                    $('#empleado_container, #cliente_container').hide();
                    $('#createUsuarioModal').modal('hide');
                    toastr.success(response.message || 'Usuario creado exitosamente');
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
                submitBtn.html(originalBtnText).prop('disabled', false);
                isSubmitting = false;
            }
        });
    });

    // Mostrar/ocultar contraseña
    $('#togglePasswordModal').on('click', function() {
        const input = $('#contraseña');
        const icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Validar requisitos de contraseña
    $('#contraseña').on('input', function() {
        const password = $(this).val();
        updateModalRequirement('modal-req-length', password.length >= 8);
        updateModalRequirement('modal-req-uppercase', /[A-Z]/.test(password));
        updateModalRequirement('modal-req-lowercase', /[a-z]/.test(password));
        updateModalRequirement('modal-req-number', (password.match(/\d/g) || []).length >= 2);
        updateModalRequirement('modal-req-special', /[!@#$%^&*()_+\-=\[\]{}]/.test(password));
    });

    function updateModalRequirement(id, isValid) {
        const el = $('#' + id);
        if (!el.length) return;
        if (isValid) {
            el.removeClass('text-danger').addClass('text-success');
            el.find('i').removeClass('fa-times-circle').addClass('fa-check-circle');
        } else {
            el.removeClass('text-success').addClass('text-danger');
            el.find('i').removeClass('fa-check-circle').addClass('fa-times-circle');
        }
    }

    // Resetear requisitos al cerrar modal
    $('#createUsuarioModal').on('hidden.bs.modal', function() {
        $('#contraseña').val('');
        $('#formCrearUsuario')[0].reset();
        $('#empleado_container, #cliente_container').hide();
        $('#id_empleado, #id_cliente').prop('required', false);
        isSubmitting = false;
        ['modal-req-length', 'modal-req-uppercase', 'modal-req-lowercase', 'modal-req-number', 'modal-req-special'].forEach(function(id) {
            updateModalRequirement(id, false);
        });
    });

    // Limpiar formulario cuando se cierra el modal
    $('#createUsuarioModal').on('hidden.bs.modal', function() {
        $('#formCrearUsuario')[0].reset();
        $('#empleado_container, #cliente_container').hide();
        $('#id_empleado, #id_cliente').prop('required', false);
        isSubmitting = false;
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
        const idEmpleado = $(this).data('idEmpleado');
        const idCliente = $(this).data('idCliente');
        
        console.log('Editando usuario:', {userId, correo, tipo, estado, idEmpleado, idCliente});
        
        // Asignar valores básicos
        $('#edit_id_usuario').val(userId);
        $('#edit_correo').val(correo);
        $('#edit_estado').val(estado);
        $('#edit_tipo_usuario').val(tipo);
        
        // Resetear selects
        $('#edit_id_empleado').val('');
        $('#edit_id_cliente').val('');
        
        // Mostrar/ocultar contenedores según tipo
        $('#edit_empleado_container, #edit_cliente_container').hide();
        $('#edit_id_empleado, #edit_id_cliente').prop('required', false);
        
        if (tipo === 'empleado') {
            $('#edit_empleado_container').show();
            $('#edit_id_empleado').prop('required', true);
            
            // Seleccionar empleado si tiene ID
            if (idEmpleado && idEmpleado !== '') {
                // Verificar que el select tenga opciones
                if ($('#edit_id_empleado option[value="' + idEmpleado + '"]').length > 0) {
                    $('#edit_id_empleado').val(idEmpleado);
                    console.log('Empleado seleccionado:', idEmpleado);
                } else {
                    console.warn('Opción de empleado no encontrada en el select');
                }
            }
        } else if (tipo === 'cliente') {
            $('#edit_cliente_container').show();
            $('#edit_id_cliente').prop('required', true);
            
            // Seleccionar cliente si tiene ID
            if (idCliente && idCliente !== '') {
                if ($('#edit_id_cliente option[value="' + idCliente + '"]').length > 0) {
                    $('#edit_id_cliente').val(idCliente);
                    console.log('Cliente seleccionado:', idCliente);
                } else {
                    console.warn('Opción de cliente no encontrada en el select');
                }
            }
        }
        
        $('#edit_contraseña').val('');
        $('#editUsuarioModal').modal('show');
        
        // Por si acaso, reintentar después de que el modal se muestre
        $('#editUsuarioModal').on('shown.bs.modal', function() {
            if (tipo === 'empleado' && idEmpleado && idEmpleado !== '') {
                $('#edit_id_empleado').val(idEmpleado);
            } else if (tipo === 'cliente' && idCliente && idCliente !== '') {
                $('#edit_id_cliente').val(idCliente);
            }
        }).off('shown.bs.modal.editUsuario'); // Evitar múltiples bindings
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
            
            // ✅ Mostrar errores de validación (422)
            if (xhr.status === 422 && xhr.responseJSON) {
                if (xhr.responseJSON.errors) {
                    // Errores de validación de Laravel
                    var errors = xhr.responseJSON.errors;
                    message = Object.values(errors).flat().join('\n');
                } else if (xhr.responseJSON.message) {
                    // Mensaje personalizado (nuestra validación de empleado duplicado)
                    message = xhr.responseJSON.message;
                }
            } else if (xhr.responseJSON?.message) {
                message = xhr.responseJSON.message;
            }
            
            toastr.error(message);
            console.error('Error details:', xhr.responseJSON); // Debug
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
            url: '{{ route("roles.store-ajax") }}',
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
            url: '{{ route("permisos.store-ajax") }}',
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
@endpush