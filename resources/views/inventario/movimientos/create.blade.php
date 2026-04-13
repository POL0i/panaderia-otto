@extends('layouts.adminlte')

@section('title', 'Crear Movimiento')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-exchange-alt icon-panaderia"></i> Crear Nuevo Movimiento</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('movimientos.index') }}" class="btn btn-back btn-sm">
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
            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Formulario de Movimiento</h5>
        </div>
        <form action="{{ route('movimientos.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="tipo_movimiento"><i class="fas fa-arrows-alt-v icon-panaderia"></i> Tipo de Movimiento <span class="text-danger">*</span></label>
                        <select name="tipo_movimiento" id="tipo_movimiento" class="form-control @error('tipo_movimiento') is-invalid @enderror">
                            <option value="">Seleccione un tipo</option>
                            @foreach($tipos_movimiento as $tipo)
                                <option value="{{ $tipo }}" {{ old('tipo_movimiento') == $tipo ? 'selected' : '' }}>
                                    {{ ucfirst($tipo) }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_movimiento')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
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
                </div>
                <div class="row">
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
                    <div class="form-group col-md-6">
                        <label for="cantidad"><i class="fas fa-calculator icon-panaderia"></i> Cantidad <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad" id="cantidad" step="0.01" class="form-control @error('cantidad') is-invalid @enderror" value="{{ old('cantidad') }}" placeholder="0.00">
                        @error('cantidad')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="precio_unitario"><i class="fas fa-dollar-sign icon-panaderia"></i> Precio Unitario <span class="text-danger">*</span></label>
                        <input type="number" name="precio_unitario" id="precio_unitario" step="0.01" class="form-control @error('precio_unitario') is-invalid @enderror" value="{{ old('precio_unitario') }}" placeholder="0.00">
                        @error('precio_unitario')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="referencia_tipo"><i class="fas fa-link icon-panaderia"></i> Tipo de Referencia <span class="text-danger">*</span></label>
                        <select name="referencia_tipo" id="referencia_tipo" class="form-control @error('referencia_tipo') is-invalid @enderror">
                            <option value="">Seleccione una referencia</option>
                            @foreach($referencias_tipo as $ref)
                                <option value="{{ $ref }}" {{ old('referencia_tipo') == $ref ? 'selected' : '' }}>
                                    {{ ucfirst($ref) }}
                                </option>
                            @endforeach
                        </select>
                        @error('referencia_tipo')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="referencia_id"><i class="fas fa-hashtag icon-panaderia"></i> ID de Referencia</label>
                    <input type="number" name="referencia_id" id="referencia_id" class="form-control @error('referencia_id') is-invalid @enderror" value="{{ old('referencia_id') }}" placeholder="Ej: 123">
                    @error('referencia_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                <div class="form-group">
                    <label for="observaciones"><i class="fas fa-sticky-note icon-panaderia"></i> Observaciones</label>
                    <textarea name="observaciones" id="observaciones" class="form-control @error('observaciones') is-invalid @enderror" rows="3" placeholder="Notas adicionales...">{{ old('observaciones') }}</textarea>
                    @error('observaciones')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="card-footer d-flex justify-content-between">
            <button type="submit" class="btn btn-save btn-sm">
                <i class="fas fa-save"></i> Guardar
            </button>
            <a href="{{ route('movimientos.index') }}" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
        </form>
    </div>
</div>
@endsection
