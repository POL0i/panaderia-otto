@extends('layouts.adminlte')

@section('title', 'Editar Asignación de Permiso')
@section('page-title', 'Editar Asignación de Permiso')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card animate-fade-in-up">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Editar Asignación de Permiso
                    </h3>
                </div>
                <form action="{{ route('rol-permisos.update', $rolPermiso->id_rol_permiso) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="id_rol">
                                <i class="fas fa-user-shield mr-2 text-primary"></i>
                                Rol
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control @error('id_rol') is-invalid @enderror" id="id_rol" name="id_rol" required>
                                @foreach($roles ?? [] as $rol)
                                    <option value="{{ $rol->id_rol }}" {{ $rolPermiso->id_rol == $rol->id_rol ? 'selected' : '' }}>{{ $rol->nombre }}</option>
                                @endforeach
                            </select>
                            @error('id_rol')
                                <small class="invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="id_permiso">
                                <i class="fas fa-lock mr-2 text-primary"></i>
                                Permiso
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control @error('id_permiso') is-invalid @enderror" id="id_permiso" name="id_permiso" required>
                                @foreach($permisos ?? [] as $permiso)
                                    <option value="{{ $permiso->id_permiso }}" {{ $rolPermiso->id_permiso == $permiso->id_permiso ? 'selected' : '' }}>{{ $permiso->nombre }}</option>
                                @endforeach
                            </select>
                            @error('id_permiso')
                                <small class="invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="estado">
                                <i class="fas fa-toggle-on mr-2 text-primary"></i>
                                Estado
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                <option value="activo" {{ $rolPermiso->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ $rolPermiso->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('estado')
                                <small class="invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-check mr-2"></i>
                            Actualizar Asignación
                        </button>
                        <a href="{{ route('rol-permisos.index') }}" class="btn btn-back">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
