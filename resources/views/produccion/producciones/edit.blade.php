@extends('layouts.adminlte')

@section('title', 'Editar Producción')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-edit icon-panaderia"></i> Editar Producción</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('producciones.index') }}" class="btn btn-back btn-sm">
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
        <form action="{{ route('producciones.update', $produccion) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="fecha_produccion">
                            <i class="fas fa-calendar"></i> Fecha de Producción <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="fecha_produccion" id="fecha_produccion" class="form-control @error('fecha_produccion') is-invalid @enderror" value="{{ old('fecha_produccion', $produccion->fecha_produccion->format('Y-m-d')) }}" required>
                        @error('fecha_produccion')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="id_receta">
                            <i class="fas fa-book"></i> Receta <span class="text-danger">*</span>
                        </label>
                        <select name="id_receta" id="id_receta" class="form-control @error('id_receta') is-invalid @enderror">
                            <option value="">Seleccione una receta</option>
                            @foreach($recetas as $receta)
                                <option value="{{ $receta->id_receta }}" {{ old('id_receta', $produccion->id_receta) == $receta->id_receta ? 'selected' : '' }}>
                                    {{ $receta->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_receta')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="cantidad_producida">
                            <i class="fas fa-cubes"></i> Cantidad Producida <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="cantidad_producida" id="cantidad_producida" step="0.01" class="form-control @error('cantidad_producida') is-invalid @enderror" value="{{ old('cantidad_producida', $produccion->cantidad_producida) }}" required>
                        @error('cantidad_producida')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="id_empleado">
                            <i class="fas fa-user"></i> Empleado <span class="text-danger">*</span>
                        </label>
                        <select name="id_empleado" id="id_empleado" class="form-control @error('id_empleado') is-invalid @enderror">
                            <option value="">Seleccione un empleado</option>
                            @foreach($empleados as $empleado)
                                <option value="{{ $empleado->id_empleado }}" {{ old('id_empleado', $produccion->id_empleado) == $empleado->id_empleado ? 'selected' : '' }}>
                                    {{ $empleado->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_empleado')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('producciones.index') }}" class="btn btn-cancel">
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
