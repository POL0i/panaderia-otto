@extends('layouts.adminlte')

@section('title', 'Ver Movimiento')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-arrows-alt-v icon-panaderia"></i> Movimiento #{{ $movimiento->id_movimiento }}</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('movimientos.index') }}" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Movimiento</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">ID Movimiento:</label>
                        <p class="form-control-plaintext">
                            <span class="badge badge-info">{{ $movimiento->id_movimiento }}</span>
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Tipo de Movimiento:</label>
                        <p class="form-control-plaintext">
                            <span class="badge badge-{{ $movimiento->tipo_movimiento == 'ingreso' ? 'success' : ($movimiento->tipo_movimiento == 'egreso' ? 'danger' : 'info') }}">
                                {{ ucfirst($movimiento->tipo_movimiento) }}
                            </span>
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Estado:</label>
                        <p class="form-control-plaintext">
                            <span class="badge badge-{{ $movimiento->estado == 'completado' ? 'success' : 'warning' }}">
                                {{ ucfirst($movimiento->estado) }}
                            </span>
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Almacén:</label>
                        <p class="form-control-plaintext">{{ $movimiento->almacen->nombre ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Item:</label>
                        <p class="form-control-plaintext">{{ $movimiento->item->nombre ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Cantidad:</label>
                        <p class="form-control-plaintext">{{ $movimiento->cantidad }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Precio Unitario:</label>
                        <p class="form-control-plaintext">${{ number_format($movimiento->precio_unitario, 2) }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Costo Total:</label>
                        <p class="form-control-plaintext"><strong>${{ number_format($movimiento->costo_total, 2) }}</strong></p>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Tipo de Referencia:</label>
                        <p class="form-control-plaintext">{{ ucfirst($movimiento->referencia_tipo) }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">ID de Referencia:</label>
                        <p class="form-control-plaintext">{{ $movimiento->referencia_id ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Fecha de Movimiento:</label>
                        <p class="form-control-plaintext">{{ $movimiento->fecha_movimiento->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Registro Creado:</label>
                        <p class="form-control-plaintext">{{ $movimiento->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            @if($movimiento->observaciones)
                <div class="form-group">
                    <label class="font-weight-bold">Observaciones:</label>
                    <p class="form-control-plaintext">{{ $movimiento->observaciones }}</p>
                </div>
            @endif
        </div>
        <div class="card-footer d-flex justify-content-between">
            <div>
                <a href="{{ route('movimientos.edit', $movimiento) }}" class="btn btn-cancel btn-sm">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <form action="{{ route('movimientos.destroy', $movimiento) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este movimiento?')">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </form>
            </div>
            <a href="{{ route('movimientos.index') }}" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection
