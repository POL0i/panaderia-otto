@extends('layouts.adminlte')

@section('title', 'Editar Receta')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Editar Receta</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('recetas.index') }}" class="btn btn-back btn-sm">
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
            <h5 class="mb-0"><i class="fas fa-edit"></i> Formulario de Edición</h5>
        </div>
        <form action="{{ route('recetas.update', $receta) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="nombre">
                        <i class="fas fa-tag"></i> Nombre <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $receta->nombre) }}" required>
                    @error('nombre')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="cantidad_requerida">
                        <i class="fas fa-balance-scale"></i> Cantidad Requerida <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="number" name="cantidad_requerida" id="cantidad_requerida" step="0.01" class="form-control @error('cantidad_requerida') is-invalid @enderror" value="{{ old('cantidad_requerida', $receta->cantidad_requerida) }}" required>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-cookie"></i> unidades</span>
                        </div>
                    </div>
                    @error('cantidad_requerida')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="descripcion">
                        <i class="fas fa-align-left"></i> Descripción
                    </label>
                    <textarea name="descripcion" id="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="4">{{ old('descripcion', $receta->descripcion) }}</textarea>
                    @error('descripcion')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('recetas.index') }}" class="btn btn-cancel">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
