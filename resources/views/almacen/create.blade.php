@extends('layouts.adminlte')

@section('title', 'Crear Almacén')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-warehouse icon-panaderia"></i> Crear Nuevo Almacén</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('almacenes.index') }}" class="btn btn-back btn-sm">
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
            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Formulario de Almacén</h5>
        </div>
        <form action="{{ route('almacenes.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="nombre"><i class="fas fa-tag icon-panaderia"></i> Nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Almacén Principal" required>
                    @error('nombre')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="ubicacion"><i class="fas fa-map-marker-alt icon-panaderia"></i> Ubicación <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('ubicacion') is-invalid @enderror" id="ubicacion" name="ubicacion" value="{{ old('ubicacion') }}" placeholder="Ej: Planta Baja, Sector A" required>
                    @error('ubicacion')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="capacidad"><i class="fas fa-expand icon-panaderia"></i> Capacidad <span class="text-danger">*</span></label>
                    <input type="number" step="1" class="form-control @error('capacidad') is-invalid @enderror" id="capacidad" name="capacidad" value="{{ old('capacidad') }}" placeholder="Ej: 1000" required>
                    @error('capacidad')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button type="submit" class="btn btn-save btn-sm">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('almacenes.index') }}" class="btn btn-back btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
