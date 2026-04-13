@extends('layouts.adminlte')

@section('title', 'Roles')
@section('page-title', 'Gestión de Roles')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card animate-fade-in-up">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title">
                        <i class="fas fa-user-shield mr-2"></i>
                        Lista de Roles
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('roles.create') }}" class="btn btn-save btn-sm">
                            <i class="fas fa-plus mr-1"></i>
                            Crear Rol
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
                                    <th>Nombre del Rol</th>
                                    <th class="text-center">Permisos Asignados</th>
                                    <th style="width: 15%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles ?? [] as $rol)
                                    <tr class="animate-slide-in-right">
                                        <td>{{ $rol->id_rol }}</td>
                                        <td>
                                            <i class="fas fa-circle text-success mr-2"></i>
                                            <strong>{{ $rol->nombre }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-pill badge-info">{{ $rol->permisos()->count() ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('roles.edit', $rol->id_rol) }}" class="btn btn-outline-primary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('roles.destroy', $rol->id_rol) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de que desea eliminar este rol?');">
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
                        Total: <strong>{{ count($roles ?? []) }}</strong> roles
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
