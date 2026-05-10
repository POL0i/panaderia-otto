@extends('layouts.adminlte')

@section('title', 'Gestión de Roles y Permisos')
@section('page-title', 'Gestión Unificada de Roles y Permisos')

@section('content')
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
                        <a href="{{ route('rol_permisos.create') }}" class="btn btn-save btn-sm">
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
                                        title="@foreach($todosRoles as $rol)&#8226; {{ $rol->nombre }}<br>@endforeach"
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
                                        title="@foreach($todosPermisos as $permiso)&#8226; {{ $permiso->nombre }}<br>@endforeach"
                                        style="cursor: help; font-size: 0.8rem; opacity: 0.6;">
                                        </i>
                                    </th>
                                    <th class="text-center" style="width: 25%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $rol)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge badge-primary badge-pill">
                                                {{ $rol->nombre }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                {{ $rol->permisos->count() }} permiso(s)
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                {{-- Botón Show --}}
                                                <button class="btn btn-outline-info btn-show-permisos" 
                                                        data-role-id="{{ $rol->id_rol }}" 
                                                        title="Ver permisos">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                {{-- Editar Rol --}}
                                                <a href="{{ route('roles.edit', $rol->id_rol) }}" 
                                                   class="btn btn-outline-primary"
                                                   title="Editar rol">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                {{-- Limpiar permisos del rol --}}
                                                <form action="{{ route('roles.clear-permissions', $rol->id_rol) }}" 
                                                      method="POST" 
                                                      style="display:inline;"
                                                      onsubmit="return confirm('¿Eliminar TODOS los permisos del rol {{ $rol->nombre }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-warning"
                                                            title="Quitar todos los permisos">
                                                        <i class="fas fa-eraser"></i>
                                                    </button>
                                                </form>
                                                
                                                {{-- Eliminar Rol --}}
                                                <form action="{{ route('roles.destroy', $rol->id_rol) }}" 
                                                      method="POST" 
                                                      style="display:inline;"
                                                      onsubmit="return confirm('¿Eliminar el rol {{ $rol->nombre }} y TODAS sus asignaciones?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger"
                                                            title="Eliminar rol">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    {{-- Fila oculta con los permisos del rol --}}
                                    <tr class="permisos-row collapse" id="permisos-{{ $rol->id_rol }}">
                                        <td></td>
                                        <td colspan="3" class="p-0">
                                            <div class="p-3 bg-light">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0">
                                                        <i class="fas fa-lock mr-2"></i>
                                                        Permisos de <strong>{{ $rol->nombre }}</strong>
                                                    </h6>
                                                    <a href="{{ route('rol_permisos.create') }}?rol={{ $rol->id_rol }}" 
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
                                                        @forelse($rol->permisos as $permiso)
                                                            <tr>
                                                                <td><code>{{ $permiso->nombre }}</code></td>
                                                                <td>
                                                                    @if($permiso->pivot && $permiso->pivot->estado == 'activo')
                                                                        <span class="badge badge-success">Activo</span>
                                                                    @elseif($permiso->pivot)
                                                                        <span class="badge badge-danger">Inactivo</span>
                                                                    @else
                                                                        <span class="badge badge-secondary">Sin estado</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <a href="{{ route('rol_permisos.edit', $permiso->pivot->id_rol_permiso ?? 0) }}" 
                                                                       class="btn btn-xs btn-outline-primary"
                                                                       title="Editar estado">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <form action="{{ route('rol_permisos.destroy', $permiso->pivot->id_rol_permiso ?? 0) }}" 
                                                                          method="POST" style="display:inline;"
                                                                          onsubmit="return confirm('¿Eliminar esta asignación?');">
                                                                        @csrf @method('DELETE')
                                                                        <button class="btn btn-xs btn-outline-danger"
                                                                                title="Eliminar asignación">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3" class="text-center text-muted">
                                                                    Sin permisos asignados
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-inbox text-muted mr-2"></i>
                                            No hay roles registrados
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Total de roles: <strong>{{ count($roles) }}</strong>
                    </small>
                    <a href="{{ route('home') }}" class="btn btn-back btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
@endpush

@push('styles')
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
@endpush