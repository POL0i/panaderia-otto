{{-- resources/views/usuarios/personas.blade.php --}}
@extends('layouts.adminlte')

@section('title', 'Directorio de Personas')
@section('page-title', 'Directorio de Personas')
@section('page-description', 'Empleados y clientes registrados')

@push('styles')
<style>
    .info-box {
        border-radius: var(--border-radius-md);
        transition: transform 0.2s ease;
    }
    .info-box:hover { transform: translateY(-2px); }
    .info-box .info-box-icon {
        border-radius: var(--border-radius-sm);
        width: 60px;
    }
    .info-box .info-box-text {
        color: var(--text-muted);
        font-size: 0.85rem;
    }
    .info-box .info-box-number {
        color: var(--color-primary-dark);
        font-weight: 700;
        font-size: 1.3rem;
    }

    .info-box-primary .info-box-icon { background: var(--color-primary); }
    .info-box-success .info-box-icon { background: var(--badge-success); }
    .info-box-info .info-box-icon    { background: var(--badge-info); }
    .info-box-warning .info-box-icon { background: var(--badge-warning); }

    .filter-btn-group .btn {
        border-radius: 20px;
        margin-right: 2px;
        margin-bottom: 2px;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }
    .filter-btn-group .btn:hover { transform: translateY(-1px); }
    .filter-btn-group .btn.active { font-weight: 600; }

    .persona-row-warning {
        background-color: rgba(255, 193, 7, 0.06);
    }
    .persona-tipo-badge {
        font-size: 0.8rem;
        padding: 0.3rem 0.6rem;
        border-radius: 15px;
        color: white;
    }
    .persona-tipo-empleado { background: var(--color-primary); }
    .persona-tipo-cliente  { background: var(--badge-info); }
    .persona-usuario-si { background: var(--badge-success); color: white; }
    .persona-usuario-no { background: var(--badge-danger); color: white; }

    .card-header-dark {
        background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
        color: var(--text-on-primary);
    }
    .card-header-dark .card-title { color: var(--text-on-primary); }

    .table-personas th, .table-personas td {
        vertical-align: middle;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    {{-- Estadísticas rápidas --}}
    <div class="row mb-3">
        <div class="col-md-3 col-6">
            <div class="info-box info-box-primary bg-panaderia-light shadow-sm">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total</span>
                    <span class="info-box-number">{{ $total }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-box info-box-success bg-panaderia-light shadow-sm">
                <span class="info-box-icon"><i class="fas fa-user-tie"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Empleados</span>
                    <span class="info-box-number">{{ $empleadosCount }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-box info-box-info bg-panaderia-light shadow-sm">
                <span class="info-box-icon"><i class="fas fa-user"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Clientes</span>
                    <span class="info-box-number">{{ $clientesCount }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-box info-box-warning bg-panaderia-light shadow-sm">
                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Sin Usuario</span>
                    <span class="info-box-number">{{ $sinUsuario }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="filter-btn-group">
                        <a href="{{ route('personas.index', ['filtro' => 'todos', 'buscar' => $buscar]) }}" 
                           class="btn btn-sm btn-outline-secondary {{ $filtro == 'todos' ? 'active' : '' }}">
                            Todos
                        </a>
                        <a href="{{ route('personas.index', ['filtro' => 'empleados', 'buscar' => $buscar]) }}" 
                           class="btn btn-sm btn-outline-primary {{ $filtro == 'empleados' ? 'active' : '' }}">
                            Empleados
                        </a>
                        <a href="{{ route('personas.index', ['filtro' => 'clientes', 'buscar' => $buscar]) }}" 
                           class="btn btn-sm btn-outline-info {{ $filtro == 'clientes' ? 'active' : '' }}">
                            Clientes
                        </a>
                        <a href="{{ route('personas.index', ['filtro' => 'sin_usuario', 'buscar' => $buscar]) }}" 
                           class="btn btn-sm btn-outline-warning {{ $filtro == 'sin_usuario' ? 'active' : '' }}">
                            Sin usuario
                        </a>
                        <a href="{{ route('personas.index', ['filtro' => 'con_usuario', 'buscar' => $buscar]) }}" 
                           class="btn btn-sm btn-outline-success {{ $filtro == 'con_usuario' ? 'active' : '' }}">
                            Con usuario
                        </a>
                    </div>
                </div>
                <div class="col-md-4 mt-2 mt-md-0">
                    <form method="GET" action="{{ route('personas.index') }}" class="input-group input-group-sm">
                        <input type="hidden" name="filtro" value="{{ $filtro }}">
                        <input type="text" name="buscar" class="form-control" 
                               placeholder="Buscar..." value="{{ $buscar }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
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

    {{-- Tabla unificada (REDUCIDA a 7 columnas) --}}
    <div class="card">
        <div class="card-header card-header-dark">
            <h3 class="card-title">
                <i class="fas fa-address-book mr-2"></i> Listado de Personas ({{ $total }})
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
                <table class="table table-hover table-sm mb-0 table-personas">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th>Contacto</th>
                            <th>Info Extra</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personas as $persona)
                            <tr class="{{ !$persona['tiene_usuario'] ? 'persona-row-warning' : '' }}">
                                {{-- Tipo --}}
                                <td>
                                    <span class="persona-tipo-badge persona-tipo-{{ strtolower($persona['tipo']) }}">
                                        <i class="fas {{ $persona['icono_tipo'] }} mr-1"></i>
                                        {{ $persona['tipo'] }}
                                    </span>
                                </td>
                                
                                {{-- Nombre --}}
                                <td><strong>{{ $persona['nombre'] }}</strong></td>
                                
                                {{-- Contacto (teléfono + dirección) --}}
                                <td>
                                    @if($persona['telefono'])
                                        <i class="fas fa-phone text-muted mr-1"></i> {{ $persona['telefono'] }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                    @if($persona['direccion'])
                                        <br><small class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $persona['direccion'] }}</small>
                                    @endif
                                </td>
                                
                                {{-- Info Extra (sueldo, edad, etc.) --}}
                                <td>
                                    <small class="text-muted">{{ $persona['info_extra'] ?: '-' }}</small>
                                </td>
                                
                                {{-- Usuario --}}
                                <td>
                                    @if($persona['tiene_usuario'])
                                        <span class="badge persona-usuario-si">Sí</span>
                                        <br><small class="text-muted">{{ $persona['usuario_correo'] }}</small>
                                    @else
                                        <span class="badge persona-usuario-no">No</span>
                                    @endif
                                </td>
                                
                                {{-- Acción --}}
                                <td>
                                    @if(!$persona['tiene_usuario'])
                                        <button class="btn btn-primary btn-sm crear-usuario-btn"
                                                data-tipo="{{ strtolower($persona['tipo']) }}"
                                                data-id="{{ $persona['id'] }}"
                                                data-nombre="{{ $persona['nombre'] }}">
                                            <i class="fas fa-user-plus"></i> Crear Usuario
                                        </button>
                                    @else
                                        <span class="text-muted small">Asignado</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
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
$(document).ready(function() {
    // Al hacer clic en "Crear Usuario", abrir el modal con datos prellenados
    $(document).on('click', '.crear-usuario-btn', function() {
        const tipo = $(this).data('tipo');
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        
        $('#tipo_usuario').val(tipo).trigger('change');
        $('#createUsuarioModal').modal('show');
        
        if (tipo === 'empleado') {
            $('#id_empleado').val(id);
        } else if (tipo === 'cliente') {
            $('#id_cliente').val(id);
        }
        
        // Prellenar correo sugerido
        const nombreLimpio = nombre.toLowerCase().replace(/\s+/g, '.');
        $('#correo').val(nombreLimpio + '@panaderia.com');
    });
});
</script>
@endpush