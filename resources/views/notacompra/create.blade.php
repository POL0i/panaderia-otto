@extends('layouts.adminlte')

@section('title', 'Crear Nota de Compra')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-receipt icon-panaderia"></i> Crear Nota de Compra</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('notas-compra.index') }}" class="btn btn-back btn-sm">
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
            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Formulario de Nota de Compra</h5>
        </div>
        <form action="{{ route('notas-compra.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_compra"><i class="fas fa-calendar icon-panaderia"></i> Fecha de Compra <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('fecha_compra') is-invalid @enderror" id="fecha_compra" name="fecha_compra" value="{{ old('fecha_compra') }}" required>
                            @error('fecha_compra')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estado"><i class="fas fa-bookmark icon-panaderia"></i> Estado <span class="text-danger">*</span></label>
                            <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                <option value="">Seleccione estado</option>
                                <option value="pendiente" {{ old('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="completada" {{ old('estado') === 'completada' ? 'selected' : '' }}>Completada</option>
                                <option value="cancelada" {{ old('estado') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                            @error('estado')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_proveedor"><i class="fas fa-truck icon-panaderia"></i> Proveedor <span class="text-danger">*</span></label>
                            <select class="form-control @error('id_proveedor') is-invalid @enderror" id="id_proveedor" name="id_proveedor" required>
                                <option value="">Seleccione un proveedor</option>
                                @foreach ($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id_proveedor }}" {{ old('id_proveedor') == $proveedor->id_proveedor ? 'selected' : '' }}>
                                        {{ $proveedor->correo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_proveedor')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_empleado"><i class="fas fa-user-tie icon-panaderia"></i> Empleado <span class="text-danger">*</span></label>
                            <select class="form-control @error('id_empleado') is-invalid @enderror" id="id_empleado" name="id_empleado" required>
                                <option value="">Seleccione un empleado</option>
                                @foreach ($empleados as $empleado)
                                    <option value="{{ $empleado->id_empleado }}" {{ old('id_empleado') == $empleado->id_empleado ? 'selected' : '' }}>
                                        {{ $empleado->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_empleado')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="monto_total"><i class="fas fa-dollar-sign icon-panaderia"></i> Monto Total <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control @error('monto_total') is-invalid @enderror" id="monto_total" name="monto_total" value="{{ old('monto_total') }}" placeholder="0.00" required>
                    @error('monto_total')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button type="submit" class="btn btn-save btn-sm">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('notas-compra.index') }}" class="btn btn-back btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
