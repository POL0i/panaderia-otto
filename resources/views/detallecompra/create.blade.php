@extends('layouts.adminlte')

@section('title', 'Crear Detalle de Compra')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-barcode icon-panaderia"></i> Crear Detalle de Compra</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('detalles-compra.index') }}" class="btn btn-back btn-sm">
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
            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Formulario de Detalle de Compra</h5>
        </div>
        <form action="{{ route('detalles-compra.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="id_nota_compra"><i class="fas fa-receipt icon-panaderia"></i> Nota de Compra <span class="text-danger">*</span></label>
                    <select class="form-control @error('id_nota_compra') is-invalid @enderror" id="id_nota_compra" name="id_nota_compra" required>
                        <option value="">Seleccione una nota de compra</option>
                        @foreach ($notasCompra as $nota)
                            <option value="{{ $nota->id_nota_compra }}" {{ old('id_nota_compra') == $nota->id_nota_compra ? 'selected' : '' }}>
                                #{{ $nota->id_nota_compra }} - {{ $nota->proveedor->correo ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_nota_compra')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_insumo"><i class="fas fa-box icon-panaderia"></i> Insumo <span class="text-danger">*</span></label>
                            <select class="form-control @error('id_insumo') is-invalid @enderror" id="id_insumo" name="id_insumo" required>
                                <option value="">Seleccione un insumo</option>
                                @foreach ($insumos as $insumo)
                                    <option value="{{ $insumo->id_insumo }}" {{ old('id_insumo') == $insumo->id_insumo ? 'selected' : '' }}>
                                        {{ $insumo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_insumo')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cantidad"><i class="fas fa-cube icon-panaderia"></i> Cantidad <span class="text-danger">*</span></label>
                            <input type="number" step="1" class="form-control @error('cantidad') is-invalid @enderror" id="cantidad" name="cantidad" value="{{ old('cantidad') }}" placeholder="0" required>
                            @error('cantidad')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="precio"><i class="fas fa-dollar-sign icon-panaderia"></i> Precio <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio') }}" placeholder="0.00" required>
                    @error('precio')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button type="submit" class="btn btn-save btn-sm">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('detalles-compra.index') }}" class="btn btn-back btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
