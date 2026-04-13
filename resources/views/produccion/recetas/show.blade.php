@extends('layouts.adminlte')

@section('title', 'Ver Receta')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-8">
            <h1 class="h3 mb-0">Receta: {{ $receta->nombre }}</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('recetas.edit', $receta) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('recetas.index') }}" class="btn btn-back btn-sm">
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Detalles de la Receta</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong><i class="fas fa-hashtag"></i> ID Receta:</strong> {{ $receta->id_receta }}</p>
                    <p><strong><i class="fas fa-tag"></i> Nombre:</strong> {{ $receta->nombre }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong><i class="fas fa-balance-scale"></i> Cantidad Requerida:</strong> {{ $receta->cantidad_requerida }}</p>
                    <p><strong><i class="fas fa-list"></i> Total Insumos:</strong> <span class="badge badge-info">{{ $receta->detalles->count() }}</span></p>
                </div>
            </div>

            @if($receta->descripcion)
                <hr>
                <div>
                    <p><strong><i class="fas fa-align-left"></i> Descripción:</strong></p>
                    <p class="text-muted">{{ $receta->descripcion }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="card shadow-sm mt-3 animate-fade-in-up">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-boxes"></i> Insumos de la Receta</h5>
        </div>
        <div class="card-body">
            @if($receta->detalles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>ID Insumo</th>
                                <th>Nombre Insumo</th>
                                <th>Cantidad Requerida</th>
                                <th style="width: 100px;" class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receta->detalles as $detalle)
                                <tr>
                                    <td>{{ $detalle->id_insumo }}</td>
                                    <td>{{ $detalle->insumo->nombre ?? 'N/A' }}</td>
                                    <td>{{ $detalle->cantidad_requerida }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('detalles-receta.edit', $detalle) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('detalles-receta.destroy', $detalle) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted text-center"><i class="fas fa-inbox"></i> No hay insumos asignados a esta receta</p>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('detalles-receta.create') }}" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Agregar Insumo
            </a>
        </div>
    </div>

    <div class="card-footer mt-3">
        <div class="d-flex justify-content-between">
            <div>
                <a href="{{ route('recetas.edit', $receta) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('recetas.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            <form action="{{ route('recetas.destroy', $receta) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Está seguro de eliminar esta receta?')">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
            <i class="fas fa-times"></i> Cancelar
        </a>
@endsection
