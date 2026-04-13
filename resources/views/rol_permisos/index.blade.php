@extends('layouts.adminlte')

@section('title', 'Mapeo Rol-Permisos')
@section('page-title', 'Gestión de Asignación Rol-Permisos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card animate-fade-in-up">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Permisos Asignados a Roles
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('rol-permisos.create') }}" class="btn btn-save btn-sm">
                            <i class="fas fa-plus mr-1"></i>
                            Asignar Permiso
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
                                @forelse($rolPermisos ?? [] as $rolPermiso)
                                    <tr class="animate-slide-in-right">
                                        <td>{{ $rolPermiso->id_rol_permiso }}</td>
                                        <td>
                                            <span class="badge badge-pill badge-primary">{{ $rolPermiso->rol->nombre ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <code>{{ $rolPermiso->permiso->nombre ?? 'N/A' }}</code>
                                        </td>
                                        <td class="text-center">
                                            @if($rolPermiso->estado == 'activo')
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Activo
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    Inactivo
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('rol-permisos.edit', $rolPermiso->id_rol_permiso) }}" class="btn btn-outline-primary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('rol-permisos.destroy', $rolPermiso->id_rol_permiso) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de que desea eliminar esta asignación?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-inbox text-muted mr-2"></i>
                                            No hay asignaciones de permisos a roles registradas
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Total: <strong>{{ count($rolPermisos ?? []) }}</strong> asignaciones
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
