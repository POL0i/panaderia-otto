@extends('layouts.adminlte')

@section('title', 'Asignar Permiso a Rol')
@section('page-title', 'Asignar Nuevo Permiso a Rol')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card animate-fade-in-up">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Asignar Permiso a Rol
                    </h3>
                </div>
                <form action="{{ route('rol-permisos.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="id_rol">
                                <i class="fas fa-user-shield mr-2 text-primary"></i>
                                Rol
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control @error('id_rol') is-invalid @enderror" id="id_rol" name="id_rol" required>
                                <option value="">Seleccionar rol...</option>
                                @foreach($roles ?? [] as $rol)
                                    <option value="{{ $rol->id_rol }}">{{ $rol->nombre }}</option>
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
                                <option value="">Seleccionar permiso...</option>
                                @foreach($permisos ?? [] as $permiso)
                                    <option value="{{ $permiso->id_permiso }}">{{ $permiso->nombre }}</option>
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
                                <option value="activo" selected>Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                            @error('estado')
                                <small class="invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-check mr-2"></i>
                            Guardar Asignación
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
