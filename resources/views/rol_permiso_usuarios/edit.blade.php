@extends('layouts.adminlte')

@section('title', 'Editar Asignación de Rol-Permiso')
@section('page-title', 'Editar Asignación de Rol-Permiso')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card animate-fade-in-up">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-2"></i>
                        Editar Asignación de Rol-Permiso
                    </h3>
                </div>
                <form action="{{ route('rol-permiso-usuarios.update', $rolPermisoUsuario->id_rol_permiso_usuario) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="id_usuario">
                                <i class="fas fa-user mr-2 text-primary"></i>
                                Usuario
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control @error('id_usuario') is-invalid @enderror" id="id_usuario" name="id_usuario" required>
                                @foreach($usuarios ?? [] as $usuario)
                                    <option value="{{ $usuario->id_usuario }}" {{ $rolPermisoUsuario->id_usuario == $usuario->id_usuario ? 'selected' : '' }}>{{ $usuario->correo }}</option>
                                @endforeach
                            </select>
                            @error('id_usuario')
                                <small class="invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="id_rol_permiso">
                                <i class="fas fa-shield-alt mr-2 text-primary"></i>
                                Rol - Permiso
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-control @error('id_rol_permiso') is-invalid @enderror" id="id_rol_permiso" name="id_rol_permiso" required>
                                @foreach($rolPermisos ?? [] as $rolPermiso)
                                    <option value="{{ $rolPermiso->id_rol_permiso }}" {{ $rolPermisoUsuario->id_rol_permiso == $rolPermiso->id_rol_permiso ? 'selected' : '' }}>
                                        [{{ $rolPermiso->rol->nombre }}] - {{ $rolPermiso->permiso->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_rol_permiso')
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
                                <option value="activo" {{ $rolPermisoUsuario->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ $rolPermisoUsuario->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
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
                        <a href="{{ route('rol-permiso-usuarios.index') }}" class="btn btn-back">
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
