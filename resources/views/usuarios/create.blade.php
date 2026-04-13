@extends('layouts.adminlte')

@section('title', 'Crear Usuario')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-user-plus icon-panaderia"></i> Crear Nuevo Usuario</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('usuarios.index') }}" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h5><i class="fas fa-exclamation-circle"></i> Errores de validación</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Formulario de Usuario</h5>
        </div>
        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="correo"><i class="fas fa-envelope icon-panaderia"></i> Correo <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo') }}" placeholder="usuario@email.com" required>
                    @error('correo')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="contraseña"><i class="fas fa-lock icon-panaderia"></i> Contraseña <span class="text-danger">*</span></label>
                    <input type="password" class="form-control @error('contraseña') is-invalid @enderror" id="contraseña" name="contraseña" placeholder="Mínimo 8 caracteres" required>
                    @error('contraseña')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipo_usuario"><i class="fas fa-list icon-panaderia"></i> Tipo de Usuario <span class="text-danger">*</span></label>
                            <select class="form-control @error('tipo_usuario') is-invalid @enderror" id="tipo_usuario" name="tipo_usuario" required>
                                <option value="">Seleccione tipo</option>
                                <option value="cliente" {{ old('tipo_usuario') === 'cliente' ? 'selected' : '' }}>Cliente</option>
                                <option value="empleado" {{ old('tipo_usuario') === 'empleado' ? 'selected' : '' }}>Empleado</option>
                            </select>
                            @error('tipo_usuario')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estado"><i class="fas fa-toggle-on icon-panaderia"></i> Estado <span class="text-danger">*</span></label>
                            <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                <option value="activo" {{ old('estado') === 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('estado')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button type="submit" class="btn btn-save btn-sm">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('usuarios.index') }}" class="btn btn-back btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
