@extends('layouts.adminlte')

@section('title', 'Editar Empleado')
@section('page-title', 'Editar Empleado')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card animate-fade-in-up">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title">
                        <i class="fas fa-user-edit mr-2"></i>
                        Editar Empleado
                    </h3>
                </div>
                <form action="{{ route('empleados.update', $empleado->id_empleado) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nombre">
                                <i class="fas fa-user mr-2 text-primary"></i>
                                Nombre
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ $empleado->nombre }}" placeholder="Ingrese nombre" required>
                            @error('nombre')
                                <small class="invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="apellido">
                                <i class="fas fa-user mr-2 text-primary"></i>
                                Apellido
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('apellido') is-invalid @enderror" id="apellido" name="apellido" value="{{ $empleado->apellido }}" placeholder="Ingrese apellido" required>
                            @error('apellido')
                                <small class="invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="telefono">
                                    <i class="fas fa-phone mr-2 text-primary"></i>
                                    Teléfono
                                </label>
                                <input type="tel" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ $empleado->telefono }}" placeholder="Ingrese teléfono">
                                @error('telefono')
                                    <small class="invalid-feedback d-block">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fecha_nac">
                                    <i class="fas fa-calendar mr-2 text-primary"></i>
                                    Fecha de Nacimiento
                                </label>
                                <input type="date" class="form-control @error('fecha_nac') is-invalid @enderror" id="fecha_nac" name="fecha_nac" value="{{ $empleado->fecha_nac ? $empleado->fecha_nac->format('Y-m-d') : '' }}">
                                @error('fecha_nac')
                                    <small class="invalid-feedback d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="direccion">
                                <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                Dirección
                            </label>
                            <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ $empleado->direccion }}" placeholder="Ingrese dirección">
                            @error('direccion')
                                <small class="invalid-feedback d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="sueldo">
                                    <i class="fas fa-dollar-sign mr-2 text-primary"></i>
                                    Sueldo
                                </label>
                                <input type="number" class="form-control @error('sueldo') is-invalid @enderror" id="sueldo" name="sueldo" value="{{ $empleado->sueldo }}" placeholder="0.00" step="0.01" min="0">
                                @error('sueldo')
                                    <small class="invalid-feedback d-block">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="edad">
                                    <i class="fas fa-birthday-cake mr-2 text-primary"></i>
                                    Edad
                                </label>
                                <input type="number" class="form-control @error('edad') is-invalid @enderror" id="edad" name="edad" value="{{ $empleado->edad }}" placeholder="0" min="0" max="120">
                                @error('edad')
                                    <small class="invalid-feedback d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-check mr-2"></i>
                            Actualizar Empleado
                        </button>
                        <a href="{{ route('empleados.index') }}" class="btn btn-back">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
