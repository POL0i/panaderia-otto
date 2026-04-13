@extends('layouts.adminlte')

@section('title', 'Editar Usuario - Módulo de Acceso')
@section('page-title', 'Editar Usuario - Gestión Completa de Acceso')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-edit"></i> Módulo de Gestión de Acceso de Usuario
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-{{ $usuario->estado == 'activo' ? 'success' : 'danger' }}">
                            {{ ucfirst($usuario->estado) }}
                        </span>
                <form action="{{ route('usuarios.update-access', $usuario->id_usuario) }}" method="POST" id="accessForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Tabs Navigation -->
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="accessTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="basic-tab" data-toggle="pill" href="#basicInfo" role="tab" aria-controls="basicInfo" aria-selected="true">
                                    <i class="fas fa-user"></i> Información Básica
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="empleado-tab" data-toggle="pill" href="#empleadoInfo" role="tab" aria-controls="empleadoInfo" aria-selected="false">
                                    <i class="fas fa-briefcase"></i> Información de Empleado
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="cliente-tab" data-toggle="pill" href="#clienteInfo" role="tab" aria-controls="clienteInfo" aria-selected="false">
                                    <i class="fas fa-shopping-bag"></i> Información de Cliente
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="permisos-tab" data-toggle="pill" href="#permisosInfo" role="tab" aria-controls="permisosInfo" aria-selected="false">
                                    <i class="fas fa-lock"></i> Roles y Permisos
                                </a>
                            </li>
                        </ul>
                    <!-- Tabs Content -->
                    <div class="tab-content" id="accessTabsContent">
                        
                        <!-- Tab 1: Información Básica -->
                        <div class="tab-pane fade show active" id="basicInfo" role="tabpanel" aria-labelledby="basic-tab">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="correo">
                                                <i class="fas fa-envelope"></i> Correo Electrónico
                                            </label>
                                            <input type="email" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ $usuario->correo }}" required>
                                            @error('correo')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contraseña">
                                                <i class="fas fa-key"></i> Contraseña (Dejar en blanco para mantener actual)
                                            </label>
                                            <input type="password" class="form-control @error('contraseña') is-invalid @enderror" id="contraseña" name="contraseña" placeholder="Dejar en blanco para mantener la actual">
                                            @error('contraseña')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tipo_usuario">
                                                <i class="fas fa-user-tag"></i> Tipo de Usuario
                                            </label>
                                            <select class="form-control @error('tipo_usuario') is-invalid @enderror" id="tipo_usuario" name="tipo_usuario" required>
                                                <option value="cliente" {{ $usuario->tipo_usuario == 'cliente' ? 'selected' : '' }}>Cliente</option>
                                                <option value="empleado" {{ $usuario->tipo_usuario == 'empleado' ? 'selected' : '' }}>Empleado</option>
                                            </select>
                                            @error('tipo_usuario')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="estado">
                                                <i class="fas fa-toggle-on"></i> Estado
                                            </label>
                                            <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                                <option value="activo" {{ $usuario->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                                                <option value="inactivo" {{ $usuario->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                            </select>
                                            @error('estado')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> 
                                            <strong>ID del Usuario:</strong> #{{ $usuario->id_usuario }} | 
                                            <strong>Creado:</strong> {{ $usuario->created_at->format('d/m/Y H:i') }}
                        <!-- Tab 2: Información de Empleado -->
                        <div class="tab-pane fade" id="empleadoInfo" role="tabpanel" aria-labelledby="empleado-tab">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id_empleado">
                                                <i class="fas fa-id-card"></i> Trabajador/Empleado
                                            </label>
                                            <select class="form-control @error('id_empleado') is-invalid @enderror" id="id_empleado" name="id_empleado">
                                                <option value="">Sin asignación</option>
                                                @foreach($empleados as $empleado)
                                                    <option value="{{ $empleado->id_empleado }}" {{ $usuario->id_empleado == $empleado->id_empleado ? 'selected' : '' }}>
                                                        {{ $empleado->nombre ?? $empleado->id_empleado }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_empleado')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                @if($usuario->empleado)
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card bg-light">
                                                <div class="card-body pb-2">
                                                    <h6 class="card-title mb-3">
                                                        <i class="fas fa-info-circle"></i> Información del Empleado Asociado
                                                    </h6>
                                                    <p class="mb-1"><strong>Nombre:</strong> {{ $usuario->empleado->nombre ?? 'N/A' }}</p>
                                                    <p class="mb-1"><strong>Correo:</strong> {{ $usuario->empleado->correo ?? 'N/A' }}</p>
                                                    <p class="mb-0"><strong>Teléfono:</strong> {{ $usuario->empleado->telefono ?? 'N/A' }}</p>
                                @endif

                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-triangle"></i> Gestiona la información del empleado asociado a este usuario.
                        <!-- Tab 3: Información de Cliente -->
                        <div class="tab-pane fade" id="clienteInfo" role="tabpanel" aria-labelledby="cliente-tab">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id_cliente">
                                                <i class="fas fa-user-circle"></i> Cliente
                                            </label>
                                            <select class="form-control @error('id_cliente') is-invalid @enderror" id="id_cliente" name="id_cliente">
                                                <option value="">Sin asignación</option>
                                                @foreach($clientes as $cliente)
                                                    <option value="{{ $cliente->id_cliente }}" {{ $usuario->id_cliente == $cliente->id_cliente ? 'selected' : '' }}>
                                                        {{ $cliente->nombre ?? $cliente->id_cliente }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_cliente')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                @if($usuario->cliente)
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card bg-light">
                                                <div class="card-body pb-2">
                                                    <h6 class="card-title mb-3">
                                                        <i class="fas fa-info-circle"></i> Información del Cliente Asociado
                                                    </h6>
                                                    <p class="mb-1"><strong>Nombre:</strong> {{ $usuario->cliente->nombre ?? 'N/A' }}</p>
                                                    <p class="mb-1"><strong>Correo:</strong> {{ $usuario->cliente->correo ?? 'N/A' }}</p>
                                                    <p class="mb-0"><strong>Teléfono:</strong> {{ $usuario->cliente->telefono ?? 'N/A' }}</p>
                                @endif

                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-triangle"></i> Gestiona la información del cliente asociado a este usuario.
                        <!-- Tab 4: Roles y Permisos -->
                        <div class="tab-pane fade" id="permisosInfo" role="tabpanel" aria-labelledby="permisos-tab">
                            <div class="card-body">
                                <h5 class="mb-3">
                                    <i class="fas fa-lock"></i> Asignación de Roles y Permisos
                                </h5>

                                @if($rolPermisos->count() > 0)
                                    <div class="alert alert-info mb-3">
                                        <i class="fas fa-info-circle"></i> 
                                        <strong>Permisos actuales asignados:</strong> 
                                        <span class="badge badge-primary">{{ count($usuarioRolPermisos) }}</span>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-borderless">
                                            <thead>
                                                <tr class="bg-light">
                                                    <th style="width: 40px;">
                                                        <input type="checkbox" id="selectAllPermisos" title="Seleccionar todos">
                                                    </th>
                                                    <th>Rol</th>
                                                    <th>Permiso</th>
                                                    <th>Descripción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($rolPermisos as $rolPermiso)
                                                    @if($rolPermiso->estado == 'activo')
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" name="rol_permiso_ids[]" value="{{ $rolPermiso->id_rol_permiso }}" class="permisoCheckbox" {{ in_array($rolPermiso->id_rol_permiso, $usuarioRolPermisos) ? 'checked' : '' }}>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-primary">{{ $rolPermiso->rol->nombre ?? 'N/A' }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-info">{{ $rolPermiso->permiso->nombre ?? 'N/A' }}</span>
                                                            </td>
                                                            <td>
                                                                <small class="text-muted">{{ $rolPermiso->permiso->descripcion ?? '-' }}</small>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllBtn">
                                            <i class="fas fa-check-square"></i> Seleccionar Todos
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="unselectAllBtn">
                                            <i class="fas fa-square"></i> Deseleccionar Todos
                                        </button>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-info-circle"></i> No hay roles y permisos disponibles. Crea roles y permisos primero.
                                @endif

                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-lightbulb"></i> Selecciona/deselecciona los roles y permisos que deseas asignar a este usuario.
                    <!-- Form Footer -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Actualizar Usuario y Acceso
                        </button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                </form>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllBtn = document.getElementById('selectAllBtn');
    const unselectAllBtn = document.getElementById('unselectAllBtn');
    const selectAllCheckbox = document.getElementById('selectAllPermisos');
    const permisoCheckboxes = document.querySelectorAll('.permisoCheckbox');

    selectAllBtn.addEventListener('click', function() {
        permisoCheckboxes.forEach(checkbox => checkbox.checked = true);
        selectAllCheckbox.checked = true;
    });

    unselectAllBtn.addEventListener('click', function() {
        permisoCheckboxes.forEach(checkbox => checkbox.checked = false);
        selectAllCheckbox.checked = false;
    });

    selectAllCheckbox.addEventListener('change', function() {
        permisoCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    permisoCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(permisoCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(permisoCheckboxes).some(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
        });
    });
});
</script>

@endsection
