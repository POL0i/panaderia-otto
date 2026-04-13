@extends('layouts.adminlte')

@section('title', 'Crear Usuario - Módulo de Acceso')
@section('page-title', 'Crear Nuevo Usuario')
@section('page-description', 'Gestión completa de acceso para empleados y clientes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus"></i> Módulo de Gestión de Acceso
                    </h3>
                </div>

                <form action="{{ route('usuarios.store-access') }}" method="POST" id="accessForm">
                    @csrf
                    
                    <!-- Tabs Navigation -->
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="accessTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="basic-tab" data-toggle="pill" href="#basicInfo" role="tab">
                                    <i class="fas fa-user"></i> Información Básica
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="empleado-tab" data-toggle="pill" href="#empleadoInfo" role="tab">
                                    <i class="fas fa-briefcase"></i> Información de Empleado
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="cliente-tab" data-toggle="pill" href="#clienteInfo" role="tab">
                                    <i class="fas fa-shopping-bag"></i> Información de Cliente
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="permisos-tab" data-toggle="pill" href="#permisosInfo" role="tab">
                                    <i class="fas fa-lock"></i> Roles y Permisos
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Tabs Content -->
                    <div class="tab-content" id="accessTabsContent">
                        
                        <!-- Tab 1: Información Básica -->
                        <div class="tab-pane fade show active" id="basicInfo" role="tabpanel">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="correo">
                                                <i class="fas fa-envelope"></i> Correo Electrónico <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control @error('correo') is-invalid @enderror" 
                                                   id="correo" name="correo" placeholder="usuario@email.com" 
                                                   value="{{ old('correo') }}" required>
                                            @error('correo')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contraseña">
                                                <i class="fas fa-key"></i> Contraseña <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" class="form-control @error('contraseña') is-invalid @enderror" 
                                                   id="contraseña" name="contraseña" placeholder="Mínimo 8 caracteres" required>
                                            @error('contraseña')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tipo_usuario">
                                                <i class="fas fa-user-tag"></i> Tipo de Usuario <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control @error('tipo_usuario') is-invalid @enderror" 
                                                    id="tipo_usuario" name="tipo_usuario" required>
                                                <option value="">Seleccionar tipo...</option>
                                                <option value="cliente" {{ old('tipo_usuario') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                                                <option value="empleado" {{ old('tipo_usuario') == 'empleado' ? 'selected' : '' }}>Empleado</option>
                                            </select>
                                            @error('tipo_usuario')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="estado">
                                                <i class="fas fa-toggle-on"></i> Estado
                                            </label>
                                            <select class="form-control @error('estado') is-invalid @enderror" 
                                                    id="estado" name="estado" required>
                                                <option value="activo" {{ old('estado', 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                                                <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                            </select>
                                            @error('estado')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Completa la información básica del usuario. Los otros campos dependerán del tipo de usuario seleccionado.
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2: Información de Empleado -->
                        <div class="tab-pane fade" id="empleadoInfo" role="tabpanel">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id_empleado">
                                                <i class="fas fa-id-card"></i> Trabajador/Empleado
                                            </label>
                                            <select class="form-control @error('id_empleado') is-invalid @enderror" 
                                                    id="id_empleado" name="id_empleado">
                                                <option value="">Sin asignación</option>
                                                @foreach($empleados as $empleado)
                                                    <option value="{{ $empleado->id_empleado }}" {{ old('id_empleado') == $empleado->id_empleado ? 'selected' : '' }}>
                                                        {{ $empleado->nombre ?? $empleado->id_empleado }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_empleado')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Selecciona el empleado/trabajador asociado si el usuario es de tipo Empleado.
                                </div>
                            </div>
                        </div>

                        <!-- Tab 3: Información de Cliente -->
                        <div class="tab-pane fade" id="clienteInfo" role="tabpanel">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="id_cliente">
                                                <i class="fas fa-user-circle"></i> Cliente
                                            </label>
                                            <select class="form-control @error('id_cliente') is-invalid @enderror" 
                                                    id="id_cliente" name="id_cliente">
                                                <option value="">Sin asignación</option>
                                                @foreach($clientes as $cliente)
                                                    <option value="{{ $cliente->id_cliente }}" {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                                                        {{ $cliente->nombre ?? $cliente->id_cliente }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('id_cliente')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Selecciona el cliente asociado si el usuario es de tipo Cliente.
                                </div>
                            </div>
                        </div>

                        <!-- Tab 4: Roles y Permisos -->
                        <div class="tab-pane fade" id="permisosInfo" role="tabpanel">
                            <div class="card-body">
                                <h5 class="mb-3">
                                    <i class="fas fa-lock"></i> Asignación de Roles y Permisos
                                </h5>

                                @if($rolPermisos->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
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
                                                                <input type="checkbox" name="rol_permiso_ids[]" value="{{ $rolPermiso->id_rol_permiso }}" class="permisoCheckbox">
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
                                    </div>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllBtn">
                                            <i class="fas fa-check-square"></i> Seleccionar Todos
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="unselectAllBtn">
                                            <i class="fas fa-square"></i> Deseleccionar Todos
                                        </button>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-info-circle"></i> No hay roles y permisos disponibles. Crea roles y permisos primero.
                                    </div>
                                @endif

                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-lightbulb"></i> Selecciona los roles y permisos que deseas asignar a este usuario.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Footer -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Crear Usuario y Asignar Acceso
                        </button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllBtn = document.getElementById('selectAllBtn');
    const unselectAllBtn = document.getElementById('unselectAllBtn');
    const selectAllCheckbox = document.getElementById('selectAllPermisos');
    const permisoCheckboxes = document.querySelectorAll('.permisoCheckbox');

    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            permisoCheckboxes.forEach(checkbox => checkbox.checked = true);
            if (selectAllCheckbox) selectAllCheckbox.checked = true;
        });
    }

    if (unselectAllBtn) {
        unselectAllBtn.addEventListener('click', function() {
            permisoCheckboxes.forEach(checkbox => checkbox.checked = false);
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
        });
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            permisoCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    }

    permisoCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!selectAllCheckbox) return;
            const allChecked = Array.from(permisoCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(permisoCheckboxes).some(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
        });
    });
});
</script>
@endsection