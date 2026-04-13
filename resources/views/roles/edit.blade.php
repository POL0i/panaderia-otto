@extends('layouts.adminlte')

@section('title', 'Editar Rol')
@section('page-title', 'Editar Rol')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card animate-fade-in-up">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title">
                        <i class="fas fa-user-shield mr-2"></i>
                        Editar Rol
                    </h3>
                </div>
                <form action="{{ route('roles.update', $rol->id_rol) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nombre">
                                <i class="fas fa-tag mr-2 text-primary"></i>
                                Nombre del Rol
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ $rol->nombre }}" placeholder="Ej: Administrador, Vendedor, Panadero" required>
                            @error('nombre')
                                <small class="invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-check mr-2"></i>
                            Actualizar Rol
                        </button>
                        <a href="{{ route('roles.index') }}" class="btn btn-back">
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
