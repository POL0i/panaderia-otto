{{-- resources/views/usuarios/personas.blade.php --}}
@extends('layouts.adminlte')

@section('title', 'Directorio de Personas')
@section('page-title', 'Directorio de Personas')
@section('page-description', 'Empleados y clientes registrados')

@section('content')
<div class="container-fluid">
    
    {{-- Estadísticas rápidas --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="info-box bg-white shadow-sm">
                <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total</span>
                    <span class="info-box-number">{{ $total }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box bg-white shadow-sm">
                <span class="info-box-icon bg-success"><i class="fas fa-user-tie"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Empleados</span>
                    <span class="info-box-number">{{ $empleadosCount }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box bg-white shadow-sm">
                <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Clientes</span>
                    <span class="info-box-number">{{ $clientesCount }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box bg-white shadow-sm">
                <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Sin Usuario</span>
                    <span class="info-box-number">{{ $sinUsuario }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros y búsqueda --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="btn-group">
                        <a href="{{ route('personas.index', ['filtro' => 'todos', 'buscar' => $buscar]) }}" 
                           class="btn btn-sm btn-outline-secondary {{ $filtro == 'todos' ? 'active' : '' }}">
                            <i class="fas fa-list mr-1"></i> Todos
                        </a>
                        <a href="{{ route('personas.index', ['filtro' => 'empleados', 'buscar' => $buscar]) }}" 
                           class="btn btn-sm btn-outline-primary {{ $filtro == 'empleados' ? 'active' : '' }}">
                            <i class="fas fa-user-tie mr-1"></i> Empleados
                        </a>
                        <a href="{{ route('personas.index', ['filtro' => 'clientes', 'buscar' => $buscar]) }}" 
                           class="btn btn-sm btn-outline-info {{ $filtro == 'clientes' ? 'active' : '' }}">
                            <i class="fas fa-user mr-1"></i> Clientes
                        </a>
                        <a href="{{ route('personas.index', ['filtro' => 'sin_usuario', 'buscar' => $buscar]) }}" 
                           class="btn btn-sm btn-outline-warning {{ $filtro == 'sin_usuario' ? 'active' : '' }}">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Sin usuario
                        </a>
                        <a href="{{ route('personas.index', ['filtro' => 'con_usuario', 'buscar' => $buscar]) }}" 
                           class="btn btn-sm btn-outline-success {{ $filtro == 'con_usuario' ? 'active' : '' }}">
                            <i class="fas fa-check mr-1"></i> Con usuario
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <form method="GET" action="{{ route('personas.index') }}" class="input-group input-group-sm">
                        <input type="hidden" name="filtro" value="{{ $filtro }}">
                        <input type="text" name="buscar" class="form-control" 
                               placeholder="Buscar por nombre o teléfono..." value="{{ $buscar }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            @if($buscar)
                                <a href="{{ route('personas.index', ['filtro' => $filtro]) }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla unificada --}}
    <div class="card">
        <div class="card-header bg-gradient-dark">
            <h3 class="card-title">
                <i class="fas fa-address-book mr-2"></i>
                Listado de Personas ({{ $total }})
            </h3>
            <div class="card-tools">
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#createEmpleadoModal">
                    <i class="fas fa-plus mr-1"></i> Empleado
                </button>
                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#createClienteModal">
                    <i class="fas fa-plus mr-1"></i> Cliente
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 5%">#</th>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Info Extra</th>
                            <th>¿Usuario?</th>
                            <th>Email Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personas as $persona)
                            <tr class="{{ !$persona['tiene_usuario'] ? 'table-warning' : '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge badge-{{ $persona['color_tipo'] }}">
                                        <i class="fas {{ $persona['icono_tipo'] }} mr-1"></i>
                                        {{ $persona['tipo'] }}
                                    </span>
                                </td>
                                <td><strong>{{ $persona['nombre'] }}</strong></td>
                                <td>{{ $persona['telefono'] }}</td>
                                <td>{{ $persona['direccion'] }}</td>
                                <td>{{ $persona['info_extra'] }}</td>
                                <td>
                                    @if($persona['tiene_usuario'])
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> Sí
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times"></i> No
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($persona['tiene_usuario'])
                                        <code>{{ $persona['usuario_correo'] }}</code>
                                        <br>
                                        <small class="text-muted">{{ ucfirst($persona['usuario_estado']) }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$persona['tiene_usuario'])
                                        <button class="btn btn-primary btn-xs crear-usuario-btn"
                                                data-tipo="{{ strtolower($persona['tipo']) }}"
                                                data-id="{{ $persona['id'] }}"
                                                data-nombre="{{ $persona['nombre'] }}">
                                            <i class="fas fa-user-plus"></i> Crear Usuario
                                        </button>
                                    @else
                                        <span class="text-muted small">Ya asignado</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-inbox text-muted mr-2"></i>
                                    No se encontraron personas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Modales --}}
@include('usuarios.partials.modal-create-empleado')
@include('usuarios.partials.modal-create-cliente')
@include('usuarios.partials.modal-create-usuario', [
    'empleados' => $empleados ?? \App\Models\Empleado::all(),
    'clientes' => $clientes ?? \App\Models\Cliente::all()
])
@endsection

@push('scripts')
<script>
$(function() {
    // Botón "Crear Usuario" desde persona
    $('.crear-usuario-btn').on('click', function() {
        var tipo = $(this).data('tipo');
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        
        // Abrir modal de crear usuario
        $('#createUsuarioModal').modal('show');
        $('#tipo_usuario').val(tipo).trigger('change');
        
        // Seleccionar la persona según tipo
        setTimeout(function() {
            if (tipo === 'empleado') {
                $('#id_empleado').val(id);
            } else if (tipo === 'cliente') {
                $('#id_cliente').val(id);
            }
            toastr.info('Creando usuario para: ' + nombre);
        }, 300);
    });
    
    // Recargar al cerrar modal de crear usuario (para actualizar estados)
    $('#createUsuarioModal').on('hidden.bs.modal', function() {
        // Recargar solo si se creó un usuario
        if ($(this).data('usuario-creado')) {
            location.reload();
        }
    });
    
    // Marcar cuando se crea un usuario exitosamente
    $('#formCrearUsuario').on('submit', function() {
        $(this).closest('.modal').data('usuario-creado', true);
    });
});
</script>
@endpush