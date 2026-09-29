{{-- resources/views/usuarios/personas.blade.php --}}
@extends('layouts.adminlte')

@section('title', 'Directorio de Personas')
@section('page-title', 'Directorio de Personas')
@section('page-description', 'Empleados y clientes registrados')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/panaderia-theme.css') }}">
<style>
    body {
        font-family: 'Outfit', sans-serif;
        background-color: #f8f6f0;
    }
    .admin-title {
        font-weight: 700;
        color: #4a3525;
        letter-spacing: -0.5px;
    }
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
    .table-modern tbody td:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
    .table-modern tbody td:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }

    .filter-btn-group .btn {
        border-radius: 50px;
        padding: 0.5rem 1.2rem;
        font-size: 0.85rem;
        font-weight: 500;
        margin-right: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        transition: all 0.2s;
        border: 1px solid #eaddd3;
        background: white;
        color: #8c7361;
    }
    .filter-btn-group .btn:hover, .filter-btn-group .btn.active {
        background: #4a3525;
        color: white;
        border-color: #4a3525;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(74, 53, 37, 0.1);
    }
    .search-modern {
        background: #f4f1ea;
        border: none;
        border-radius: 50px;
        padding: 0.5rem 1.2rem;
        font-size: 0.9rem;
        width: 100%;
        color: #4a3525;
    }
    .search-modern:focus { outline: none; background: white; box-shadow: 0 0 0 2px #c88647; }

    .badge-compact { padding: 4px 8px; font-weight: 500; font-size: 0.75rem; border-radius: 8px; }
    .badge-empleado { background: rgba(74, 53, 37, 0.1); color: #4a3525; }
    .badge-cliente { background: rgba(200, 134, 71, 0.15); color: #b07238; }
    .badge-success-soft { background: rgba(40, 167, 69, 0.1); color: #28a745; }
    .badge-danger-soft { background: rgba(220, 53, 69, 0.1); color: #dc3545; }

    .btn-create {
        border-radius: 50px;
        padding: 0.5rem 1.2rem;
        font-weight: 500;
        font-size: 0.85rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .btn-create-empleado { background: #4a3525; color: white; border: none; }
    .btn-create-empleado:hover { background: #362519; color: white; }
    .btn-create-cliente { background: #c88647; color: white; border: none; }
    .btn-create-cliente:hover { background: #b07238; color: white; }
    
    .stats-footer {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        margin-top: 1rem;
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

    .icon-total { background: rgba(74, 53, 37, 0.1); color: #4a3525; }
    .icon-empleados { background: rgba(40, 167, 69, 0.1); color: #28a745; }
    .icon-clientes { background: rgba(23, 162, 184, 0.1); color: #17a2b8; }
    .icon-sin-usuario { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    {{-- Estadísticas movidas al final --}}

    {{-- Filtros Modernos --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <div class="filter-btn-group">
                <a href="{{ route('personas.index', ['filtro' => 'todos', 'buscar' => $buscar]) }}" class="btn {{ $filtro == 'todos' ? 'active' : '' }}">Todos</a>
                <a href="{{ route('personas.index', ['filtro' => 'empleados', 'buscar' => $buscar]) }}" class="btn {{ $filtro == 'empleados' ? 'active' : '' }}">Empleados</a>
                <a href="{{ route('personas.index', ['filtro' => 'clientes', 'buscar' => $buscar]) }}" class="btn {{ $filtro == 'clientes' ? 'active' : '' }}">Clientes</a>
                <a href="{{ route('personas.index', ['filtro' => 'sin_usuario', 'buscar' => $buscar]) }}" class="btn {{ $filtro == 'sin_usuario' ? 'active' : '' }}">Sin Usuario</a>
                <a href="{{ route('personas.index', ['filtro' => 'con_usuario', 'buscar' => $buscar]) }}" class="btn {{ $filtro == 'con_usuario' ? 'active' : '' }}">Con Usuario</a>
            </div>
        </div>
        <div class="col-md-4 mt-2 mt-md-0">
            <form method="GET" action="{{ route('personas.index') }}" class="input-group" style="background: #f4f1ea; border-radius: 50px; overflow: hidden;">
                <input type="hidden" name="filtro" value="{{ $filtro }}">
                <div class="input-group-prepend" style="position: absolute; z-index: 10; padding: 10px 15px; color: #8c7361; background: transparent;">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text" name="buscar" class="form-control search-modern border-0 pl-5 shadow-none" placeholder="Buscar..." value="{{ $buscar }}" style="background: transparent;">
                @if($buscar)
                    <div class="input-group-append" style="position: absolute; right: 0; z-index: 10;">
                        <a href="{{ route('personas.index', ['filtro' => $filtro]) }}" class="btn btn-link text-muted" style="padding: 10px 15px;"><i class="fas fa-times"></i></a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Tabla Unificada --}}
    <div class="card card-modern glass-card">
        <div class="card-header bakery-header d-flex justify-content-between align-items-center" style="border-bottom: 1px solid rgba(74, 53, 37, 0.1);">
            <h4 class="mb-0 admin-title font-weight-bold" style="color: #4a3525;"><i class="fas fa-address-book mr-2" style="color: #c88647;"></i> Directorio ({{ $total }})</h4>
            <div class="card-tools">
                <button class="btn btn-create btn-create-empleado" data-toggle="modal" data-target="#createEmpleadoModal">
                    <i class="fas fa-plus mr-1"></i> Empleado
                </button>
                <button class="btn btn-create btn-create-cliente" data-toggle="modal" data-target="#createClienteModal">
                    <i class="fas fa-plus mr-1"></i> Cliente
                </button>
            </div>
        </div>
        <div class="card-body p-2 p-md-3">
            <div class="table-responsive">
                <table class="table-modern w-100">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th>Contacto</th>
                            <th>Info Extra</th>
                            <th>Usuario</th>
                            <th class="text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personas as $persona)
                            <tr>
                                <td>
                                    <span class="badge-compact {{ strtolower($persona['tipo']) == 'empleado' ? 'badge-empleado' : 'badge-cliente' }}">
                                        <i class="fas {{ $persona['icono_tipo'] }} mr-1"></i> {{ $persona['tipo'] }}
                                    </span>
                                </td>
                                <td><strong>{{ $persona['nombre'] }}</strong></td>
                                <td>
                                    @if($persona['telefono'])
                                        <div><i class="fas fa-phone text-muted mr-1" style="font-size: 10px;"></i> {{ $persona['telefono'] }}</div>
                                    @endif
                                    @if($persona['direccion'])
                                        <small class="text-muted"><i class="fas fa-map-marker-alt" style="font-size: 10px;"></i> {{ $persona['direccion'] }}</small>
                                    @endif
                                    @if(!$persona['telefono'] && !$persona['direccion'])
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $persona['info_extra'] ?: '-' }}</small></td>
                                <td>
                                    @if($persona['tiene_usuario'])
                                        <span class="badge-compact badge-success-soft"><i class="fas fa-check mr-1"></i> Sí</span>
                                        <br><small class="text-muted">{{ $persona['usuario_correo'] }}</small>
                                    @else
                                        <span class="badge-compact badge-danger-soft"><i class="fas fa-times mr-1"></i> No</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if(!$persona['tiene_usuario'])
                                        <button class="btn btn-create-empleado btn-sm crear-usuario-btn" style="border-radius: 8px; padding: 4px 10px;"
                                                data-tipo="{{ strtolower($persona['tipo']) }}" data-id="{{ $persona['id'] }}" data-nombre="{{ $persona['nombre'] }}">
                                            <i class="fas fa-user-plus"></i> Usuario
                                        </button>
                                    @else
                                        <span class="text-muted small"><i class="fas fa-link"></i> Vinculado</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3" style="color: #eaddd3;"></i>
                                    <h5>No se encontraron personas</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Estadísticas al pie --}}
    <div class="row">
        <div class="col-12">
            <div class="stats-footer">
                <div class="stat-compact">
                    <div class="stat-icon icon-total"><i class="fas fa-users"></i></div>
                    <div class="stat-info"><h4>{{ $total }}</h4><p>Total</p></div>
                </div>
                <div class="stat-compact">
                    <div class="stat-icon icon-empleados"><i class="fas fa-user-tie"></i></div>
                    <div class="stat-info"><h4>{{ $empleadosCount }}</h4><p>Empleados</p></div>
                </div>
                <div class="stat-compact">
                    <div class="stat-icon icon-clientes"><i class="fas fa-user"></i></div>
                    <div class="stat-info"><h4>{{ $clientesCount }}</h4><p>Clientes</p></div>
                </div>
                <div class="stat-compact">
                    <div class="stat-icon icon-sin-usuario"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="stat-info"><h4>{{ $sinUsuario }}</h4><p>Sin Usuario</p></div>
                </div>
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