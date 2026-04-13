@extends('layouts.adminlte')

@section('title', 'Crear Detalle de Receta')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-clipboard icon-panaderia"></i> Crear Nuevo Detalle de Receta</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('detalles-receta.index') }}" class="btn btn-back btn-sm">
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
            <h5 class="mb-0"><i class="fas fa-list"></i> Formulario de Detalle de Receta</h5>
        </div>
        <form action="{{ route('detalles-receta.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="id_receta">
                            <i class="fas fa-book"></i> Receta <span class="text-danger">*</span>
                        </label>
                        <select name="id_receta" id="id_receta" class="form-control @error('id_receta') is-invalid @enderror">
                            <option value="">Seleccione una receta</option>
                            @foreach($recetas as $receta)
                                <option value="{{ $receta->id_receta }}" {{ old('id_receta') == $receta->id_receta ? 'selected' : '' }}>
                                    {{ $receta->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_receta')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="id_insumo">
                            <i class="fas fa-warehouse"></i> Insumo <span class="text-danger">*</span>
                        </label>
                        <select name="id_insumo" id="id_insumo" class="form-control @error('id_insumo') is-invalid @enderror">
                            <option value="">Seleccione un insumo</option>
                            @foreach($insumos as $insumo)
                                <option value="{{ $insumo->id_insumo }}" {{ old('id_insumo') == $insumo->id_insumo ? 'selected' : '' }}>
                                    {{ $insumo->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_insumo')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="cantidad_requerida">
                        <i class="fas fa-balance-scale"></i> Cantidad Requerida <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="number" name="cantidad_requerida" id="cantidad_requerida" step="0.01" class="form-control @error('cantidad_requerida') is-invalid @enderror" value="{{ old('cantidad_requerida') }}" required>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-cube"></i> unidades</span>
                        </div>
                    </div>
                    @error('cantidad_requerida')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('detalles-receta.index') }}" class="btn btn-cancel">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
