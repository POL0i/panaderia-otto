@extends('layouts.adminlte')

@section('title', 'Editar Lote')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-pencil-alt icon-panaderia"></i> Editar Lote #{{ $lote->id_lote }}</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('lotes.index') }}" class="btn btn-back btn-sm">
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
            <h5 class="mb-0"><i class="fas fa-edit icon-panaderia"></i> Formulario de Edición</h5>
        </div>
        <form action="{{ route('lotes.update', $lote) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="cantidad_disponible"><i class="fas fa-calculator icon-panaderia"></i> Cantidad Disponible <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad_disponible" id="cantidad_disponible" step="0.01" class="form-control @error('cantidad_disponible') is-invalid @enderror" value="{{ old('cantidad_disponible', $lote->cantidad_disponible) }}" max="{{ $lote->cantidad_inicial }}" placeholder="0.00">
                        <small class="form-text text-muted">Máximo: {{ $lote->cantidad_inicial }}</small>
                        @error('cantidad_disponible')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="metodo_valuacion"><i class="fas fa-list icon-panaderia"></i> Método de Valuación <span class="text-danger">*</span></label>
                        <select name="metodo_valuacion" id="metodo_valuacion" class="form-control @error('metodo_valuacion') is-invalid @enderror">
                            <option value="PEPS" {{ old('metodo_valuacion', $lote->metodo_valuacion) == 'PEPS' ? 'selected' : '' }}>PEPS</option>
                            <option value="UEPS" {{ old('metodo_valuacion', $lote->metodo_valuacion) == 'UEPS' ? 'selected' : '' }}>UEPS</option>
                        </select>
                        @error('metodo_valuacion')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="alert alert-info animate-fade-in">
                    <h6 class="font-weight-bold"><i class="fas fa-info-circle"></i> Información del Lote:</h6>
                    <ul class="mb-0">
                        <li><strong>Almacén:</strong> {{ $lote->almacen->nombre ?? 'N/A' }}</li>
                        <li><strong>Item:</strong> {{ $lote->item->nombre ?? 'N/A' }}</li>
                        <li><strong>Cantidad Inicial:</strong> {{ $lote->cantidad_inicial }}</li>
                        <li><strong>Precio Unitario:</strong> ${{ number_format($lote->precio_unitario, 2) }}</li>
                        <li><strong>Fecha de Entrada:</strong> {{ $lote->fecha_entrada->format('d/m/Y H:i') }}</li>
                        <li><strong>Estado Actual:</strong> {{ ucfirst($lote->estado) }}</li>
                    </ul>
                </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <button type="submit" class="btn btn-save btn-sm">
                <i class="fas fa-save"></i> Actualizar
            </button>
            <a href="{{ route('lotes.index') }}" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
        </form>
    </div>
</div>
@endsection
