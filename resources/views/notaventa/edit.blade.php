@extends('layouts.adminlte')

@section('title', 'Editar Nota de Venta')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-edit icon-panaderia"></i> Editar Nota de Venta</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('notas-venta.index') }}" class="btn btn-back btn-sm">
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
            <h5 class="mb-0"><i class="fas fa-pencil-alt"></i> Datos de la Nota de Venta</h5>
        </div>
        <form action="{{ route('notas-venta.update', $notaVenta->id_nota_venta) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_venta"><i class="fas fa-calendar icon-panaderia"></i> Fecha de Venta <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('fecha_venta') is-invalid @enderror" id="fecha_venta" name="fecha_venta" value="{{ old('fecha_venta', $notaVenta->fecha_venta->format('Y-m-d')) }}" required>
                            @error('fecha_venta')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estado"><i class="fas fa-bookmark icon-panaderia"></i> Estado <span class="text-danger">*</span></label>
                            <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                <option value="pendiente" {{ old('estado', $notaVenta->estado) === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="completada" {{ old('estado', $notaVenta->estado) === 'completada' ? 'selected' : '' }}>Completada</option>
                                <option value="cancelada" {{ old('estado', $notaVenta->estado) === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
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
                            <label for="id_cliente"><i class="fas fa-users icon-panaderia"></i> Cliente <span class="text-danger">*</span></label>
                            <select class="form-control @error('id_cliente') is-invalid @enderror" id="id_cliente" name="id_cliente" required>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id_cliente }}" {{ old('id_cliente', $notaVenta->id_cliente) == $cliente->id_cliente ? 'selected' : '' }}>
                                        {{ $cliente->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_cliente')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_empleado"><i class="fas fa-user-tie icon-panaderia"></i> Empleado <span class="text-danger">*</span></label>
                            <select class="form-control @error('id_empleado') is-invalid @enderror" id="id_empleado" name="id_empleado" required>
                                @foreach ($empleados as $empleado)
                                    <option value="{{ $empleado->id_empleado }}" {{ old('id_empleado', $notaVenta->id_empleado) == $empleado->id_empleado ? 'selected' : '' }}>
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
                    <input type="number" step="0.01" class="form-control @error('monto_total') is-invalid @enderror" id="monto_total" name="monto_total" value="{{ old('monto_total', $notaVenta->monto_total) }}" placeholder="0.00" required>
                    @error('monto_total')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button type="submit" class="btn btn-save btn-sm">
                    <i class="fas fa-check"></i> Actualizar
                </button>
                <a href="{{ route('notas-venta.index') }}" class="btn btn-back btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
