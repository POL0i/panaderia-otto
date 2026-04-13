@extends('layouts.adminlte')

@section('title', 'Crear Producción Item Almacén')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-link icon-panaderia"></i> Crear Nueva Asignación</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('produccion-items.index') }}" class="btn btn-back btn-sm">
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
            <h5 class="mb-0"><i class="fas fa-edit"></i> Formulario de Asignación</h5>
        </div>
        <form action="{{ route('produccion-items.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="id_produccion">
                            <i class="fas fa-hammer"></i> Producción <span class="text-danger">*</span>
                        </label>
                        <select name="id_produccion" id="id_produccion" class="form-control @error('id_produccion') is-invalid @enderror">
                            <option value="">Seleccione una producción</option>
                            @foreach($producciones as $produccion)
                                <option value="{{ $produccion->id_produccion }}" {{ old('id_produccion') == $produccion->id_produccion ? 'selected' : '' }}>
                                    #{{ $produccion->id_produccion }} - {{ $produccion->receta->nombre ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_produccion')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="id_almacen">
                            <i class="fas fa-warehouse"></i> Almacén <span class="text-danger">*</span>
                        </label>
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
                    <div class="form-group col-md-4">
                        <label for="id_item">
                            <i class="fas fa-box"></i> Item/Producto <span class="text-danger">*</span>
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
                </div>

                <div class="form-group">
                    <label for="cantidad_producida">
                        <i class="fas fa-cubes"></i> Cantidad Producida <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="cantidad_producida" id="cantidad_producida" step="0.01" class="form-control @error('cantidad_producida') is-invalid @enderror" value="{{ old('cantidad_producida') }}" required>
                    @error('cantidad_producida')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('produccion-items.index') }}" class="btn btn-cancel">
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
