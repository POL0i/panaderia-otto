@extends('layouts.adminlte')

@section('title', 'Ver Producción Item Almacén')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-eye icon-panaderia"></i> Asignación #{{ $produccionItem->id_produccion_item_almacen }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('produccion-items.edit', $produccionItem) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('produccion-items.index') }}" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Detalles de la Asignación</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">ID Asignación:</label>
                        <p>{{ $produccionItem->id_produccion_item_almacen }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-hammer"></i> Producción:</label>
                        <p>#{{ $produccionItem->produccion->id_produccion }} - {{ $produccionItem->produccion->receta->nombre ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-warehouse"></i> Almacén:</label>
                        <p>{{ $produccionItem->almacen->nombre ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-box"></i> Item/Producto:</label>
                        <p>{{ $produccionItem->item->nombre ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-cubes"></i> Cantidad Producida:</label>
                        <p>{{ $produccionItem->cantidad_producida }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-clock"></i> Creado:</label>
                        <p>{{ $produccionItem->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <a href="{{ route('produccion-items.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <div>
                    <a href="{{ route('produccion-items.edit', $produccionItem) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <form action="{{ route('produccion-items.destroy', $produccionItem) }}" method="POST" style="display:inline;">
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
