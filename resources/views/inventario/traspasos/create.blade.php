@extends('layouts.adminlte')

@section('title', 'Crear Traspaso')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-exchange-alt icon-panaderia"></i> Crear Nuevo Traspaso</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('traspasos.index') }}" class="btn btn-back btn-sm">
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
            <h5 class="mb-0"><i class="fas fa-edit"></i> Formulario de Traspaso</h5>
        </div>
        <form action="{{ route('traspasos.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="id_almacen_origen">
                            <i class="fas fa-warehouse"></i> Almacén Origen <span class="text-danger">*</span>
                        </label>
                        <select name="id_almacen_origen" id="id_almacen_origen" class="form-control @error('id_almacen_origen') is-invalid @enderror">
                            <option value="">Seleccione un almacén</option>
                            @foreach($almacenes as $almacen)
                                <option value="{{ $almacen->id_almacen }}" {{ old('id_almacen_origen') == $almacen->id_almacen ? 'selected' : '' }}>
                                    {{ $almacen->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_almacen_origen')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="id_almacen_destino">
                            <i class="fas fa-warehouse"></i> Almacén Destino <span class="text-danger">*</span>
                        </label>
                        <select name="id_almacen_destino" id="id_almacen_destino" class="form-control @error('id_almacen_destino') is-invalid @enderror">
                            <option value="">Seleccione un almacén</option>
                            @foreach($almacenes as $almacen)
                                <option value="{{ $almacen->id_almacen }}" {{ old('id_almacen_destino') == $almacen->id_almacen ? 'selected' : '' }}>
                                    {{ $almacen->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_almacen_destino')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="id_item">
                            <i class="fas fa-box"></i> Item <span class="text-danger">*</span>
                        </label>
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
                    <div class="form-group col-md-4">
                        <label for="cantidad">
                            <i class="fas fa-cubes"></i> Cantidad <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="cantidad" id="cantidad" step="0.01" class="form-control @error('cantidad') is-invalid @enderror" value="{{ old('cantidad') }}">
                        @error('cantidad')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="precio_unitario">
                            <i class="fas fa-dollar-sign"></i> Precio Unitario <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="precio_unitario" id="precio_unitario" step="0.01" class="form-control @error('precio_unitario') is-invalid @enderror" value="{{ old('precio_unitario') }}">
                        @error('precio_unitario')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="observaciones">
                        <i class="fas fa-sticky-note"></i> Observaciones
                    </label>
                    <textarea name="observaciones" id="observaciones" class="form-control @error('observaciones') is-invalid @enderror" rows="3">{{ old('observaciones') }}</textarea>
                    @error('observaciones')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('traspasos.index') }}" class="btn btn-cancel">
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
