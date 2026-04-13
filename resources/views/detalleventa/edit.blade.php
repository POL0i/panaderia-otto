@extends('layouts.adminlte')

@section('title', 'Editar Detalle de Venta')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-edit icon-panaderia"></i> Editar Detalle de Venta</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('detalles-venta.index') }}" class="btn btn-back btn-sm">
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
            <h5 class="mb-0"><i class="fas fa-pencil-alt"></i> Datos del Detalle</h5>
        </div>
        <form action="{{ route('detalles-venta.update', [$detalleVenta->id_nota_venta, $detalleVenta->id_producto]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="id_nota_venta"><i class="fas fa-file-invoice-dollar icon-panaderia"></i> Nota de Venta <span class="text-danger">*</span></label>
                    <select class="form-control" id="id_nota_venta" name="id_nota_venta" disabled>
                        @foreach ($notasVenta as $nota)
                            <option value="{{ $nota->id_nota_venta }}" {{ old('id_nota_venta', $detalleVenta->id_nota_venta) == $nota->id_nota_venta ? 'selected' : '' }}>
                                #{{ $nota->id_nota_venta }} - {{ $nota->cliente->nombre ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_producto"><i class="fas fa-bread-slice icon-panaderia"></i> Producto <span class="text-danger">*</span></label>
                    <select class="form-control" id="id_producto" name="id_producto" disabled>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id_producto }}" {{ old('id_producto', $detalleVenta->id_producto) == $producto->id_producto ? 'selected' : '' }}>
                                {{ $producto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cantidad"><i class="fas fa-cube icon-panaderia"></i> Cantidad <span class="text-danger">*</span></label>
                            <input type="number" step="1" class="form-control @error('cantidad') is-invalid @enderror" id="cantidad" name="cantidad" value="{{ old('cantidad', $detalleVenta->cantidad) }}" placeholder="0" required>
                            @error('cantidad')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="precio"><i class="fas fa-dollar-sign icon-panaderia"></i> Precio <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio', $detalleVenta->precio) }}" placeholder="0.00" required>
                            @error('precio')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button type="submit" class="btn btn-save btn-sm">
                    <i class="fas fa-check"></i> Actualizar
                </button>
                <a href="{{ route('detalles-venta.index') }}" class="btn btn-back btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
