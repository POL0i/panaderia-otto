@extends('layouts.adminlte')

@section('title', 'Crear Cliente')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-users icon-panaderia"></i> Crear Nuevo Cliente</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('clientes.index') }}" class="btn btn-back btn-sm">
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
            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Formulario de Cliente</h5>
        </div>
        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nombre"><i class="fas fa-user icon-panaderia"></i> Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre del cliente" required>
                            @error('nombre')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="apellido"><i class="fas fa-user icon-panaderia"></i> Apellido <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('apellido') is-invalid @enderror" id="apellido" name="apellido" value="{{ old('apellido') }}" placeholder="Apellido del cliente" required>
                            @error('apellido')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="telefono"><i class="fas fa-phone icon-panaderia"></i> Teléfono <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="Número de teléfono" required>
                    <small class="text-muted">Ej: 71234567 o 59171234567</small>
                    @error('telefono')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button type="submit" class="btn btn-save btn-sm">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('clientes.index') }}" class="btn btn-back btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
