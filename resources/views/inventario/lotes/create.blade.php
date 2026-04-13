@extends('layouts.adminlte')

@section('title', 'Crear Lote')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-boxes icon-panaderia"></i> Crear Nuevo Lote</h1>
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
            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Formulario de Lote</h5>
        </div>
        <form action="{{ route('lotes.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="id_almacen"><i class="fas fa-warehouse icon-panaderia"></i> Almacén <span class="text-danger">*</span></label>
                        <select name="id_almacen" id="id_almacen" class="form-control @error('id_almacen') is-invalid @enderror">
                            <option value="">Seleccione un almacén</option>
                            @foreach($almacenes as $almacen)
                                <option value="{{ $almacen->id_almacen }}" {{ old('id_almacen') == $almacen->id_almacen ? 'selected' : '' }}>
                                    {{ $almacen->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_almacen')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="id_item"><i class="fas fa-box-open icon-panaderia"></i> Item <span class="text-danger">*</span></label>
                        <select name="id_item" id="id_item" class="form-control @error('id_item') is-invalid @enderror">
                            <option value="">Seleccione un item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id_item }}" {{ old('id_item') == $item->id_item ? 'selected' : '' }}>
                                    {{ $item->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_item')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="cantidad_inicial"><i class="fas fa-calculator icon-panaderia"></i> Cantidad Inicial <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad_inicial" id="cantidad_inicial" step="0.01" class="form-control @error('cantidad_inicial') is-invalid @enderror" value="{{ old('cantidad_inicial') }}" placeholder="0.00">
                        @error('cantidad_inicial')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="precio_unitario"><i class="fas fa-dollar-sign icon-panaderia"></i> Precio Unitario <span class="text-danger">*</span></label>
                        <input type="number" name="precio_unitario" id="precio_unitario" step="0.01" class="form-control @error('precio_unitario') is-invalid @enderror" value="{{ old('precio_unitario') }}" placeholder="0.00">
                        @error('precio_unitario')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="metodo_valuacion"><i class="fas fa-list icon-panaderia"></i> Método de Valuación <span class="text-danger">*</span></label>
                        <select name="metodo_valuacion" id="metodo_valuacion" class="form-control @error('metodo_valuacion') is-invalid @enderror">
                            <option value="PEPS" {{ old('metodo_valuacion', 'PEPS') == 'PEPS' ? 'selected' : '' }}>PEPS</option>
                            <option value="UEPS" {{ old('metodo_valuacion') == 'UEPS' ? 'selected' : '' }}>UEPS</option>
                        </select>
                        @error('metodo_valuacion')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <button type="submit" class="btn btn-save btn-sm">
                <i class="fas fa-save"></i> Guardar
            </button>
            <a href="{{ route('lotes.index') }}" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
        </form>
    </div>
</div>
@endsection
