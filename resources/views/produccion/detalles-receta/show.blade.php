@extends('layouts.adminlte')

@section('title', 'Ver Detalle de Receta')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-eye icon-panaderia"></i> Detalle de Receta #{{ $detalleReceta->id_detalle_receta }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('detalles-receta.edit', $detalleReceta) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('detalles-receta.index') }}" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Detalles del Detalle de Receta</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">ID Detalle Receta:</label>
                        <p>{{ $detalleReceta->id_detalle_receta }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-book"></i> Receta:</label>
                        <p>{{ $detalleReceta->receta->nombre ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-warehouse"></i> Insumo:</label>
                        <p>{{ $detalleReceta->insumo->nombre ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-balance-scale"></i> Cantidad Requerida:</label>
                        <p>{{ $detalleReceta->cantidad_requerida }} unidades</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <a href="{{ route('detalles-receta.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <div>
                    <a href="{{ route('detalles-receta.edit', $detalleReceta) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <form action="{{ route('detalles-receta.destroy', $detalleReceta) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar?')">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
