@extends('layouts.adminlte')

@section('title', 'Ver Producción')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-eye icon-panaderia"></i> Producción #{{ $produccion->id_produccion }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('producciones.edit', $produccion) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('producciones.index') }}" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Detalles de la Producción</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">ID Producción:</label>
                        <p>{{ $produccion->id_produccion }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-calendar"></i> Fecha de Producción:</label>
                        <p>{{ $produccion->fecha_produccion->format('d/m/Y') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-book"></i> Receta:</label>
                        <p>{{ $produccion->receta->nombre ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-cubes"></i> Cantidad Producida:</label>
                        <p>{{ $produccion->cantidad_producida }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-user"></i> Empleado:</label>
                        <p>{{ $produccion->empleado->nombre ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-clock"></i> Creado:</label>
                        <p>{{ $produccion->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-3 animate-fade-in-up">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-cubes"></i> Insumos Requeridos de la Receta</h5>
        </div>
        <div class="card-body">
            @if($produccion->receta && $produccion->receta->detalles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Insumo</th>
                                <th>Cantidad Requerida</th>
                                <th>Total para esta Producción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produccion->receta->detalles as $detalle)
                                <tr>
                                    <td>{{ $detalle->insumo->nombre ?? 'N/A' }}</td>
                                    <td>{{ $detalle->cantidad_requerida }}</td>
                                    <td>{{ $detalle->cantidad_requerida * $produccion->cantidad_producida }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center"><i class="fas fa-inbox"></i> No hay insumos asignados a esta receta</p>
            @endif
        </div>
    </div>

    <div class="card-footer mt-3">
        <div class="d-flex justify-content-between">
            <a href="{{ route('producciones.index') }}" class="btn btn-cancel">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <div>
                <a href="{{ route('producciones.edit', $produccion) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <form action="{{ route('producciones.destroy', $produccion) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar esta producción?')">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
