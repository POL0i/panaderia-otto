@extends('layouts.adminlte')

@section('title', 'Ver Lote')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-boxes icon-panaderia"></i> Lote #{{ $lote->id_lote }}</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('lotes.index') }}" class="btn btn-back btn-sm">
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
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Lote</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">ID Lote:</label>
                        <p class="form-control-plaintext">
                            <span class="badge badge-info">{{ $lote->id_lote }}</span>
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Almacén:</label>
                        <p class="form-control-plaintext">{{ $lote->almacen->nombre ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Item:</label>
                        <p class="form-control-plaintext">{{ $lote->item->nombre ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Cantidad Inicial:</label>
                        <p class="form-control-plaintext">{{ $lote->cantidad_inicial }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Cantidad Disponible:</label>
                        <p class="form-control-plaintext"><strong>{{ $lote->cantidad_disponible }}</strong></p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Cantidad Consumida:</label>
                        <p class="form-control-plaintext">{{ $lote->cantidad_inicial - $lote->cantidad_disponible }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Precio Unitario:</label>
                        <p class="form-control-plaintext">${{ number_format($lote->precio_unitario, 2) }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Valor Disponible:</label>
                        <p class="form-control-plaintext"><strong>${{ number_format($lote->valor_disponible, 2) }}</strong></p>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Método de Valuación:</label>
                        <p class="form-control-plaintext">
                            <span class="badge badge-info">{{ $lote->metodo_valuacion }}</span>
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Estado:</label>
                        <p class="form-control-plaintext">
                            <span class="badge badge-{{ $lote->estado == 'disponible' ? 'success' : ($lote->estado == 'consumido' ? 'danger' : 'info') }}">
                                {{ ucfirst($lote->estado) }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Fecha de Entrada:</label>
                        <p class="form-control-plaintext">{{ $lote->fecha_entrada->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($lote->fecha_salida)
                        <div class="form-group">
                            <label class="font-weight-bold">Fecha de Salida:</label>
                            <p class="form-control-plaintext">{{ $lote->fecha_salida->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($lote->estado == 'disponible' && $lote->cantidad_disponible > 0)
                <div class="alert alert-info animate-fade-in">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="font-weight-bold"><i class="fas fa-minus-circle"></i> Consumir Cantidad</h6>
                            <form action="{{ route('lotes.consumir', $lote) }}" method="POST" class="form-inline">
                                @csrf
                                <div class="form-group mr-3">
                                    <label for="cantidad" class="mr-2">Cantidad a consumir:</label>
                                    <input type="number" name="cantidad" id="cantidad" step="0.01" class="form-control" max="{{ $lote->cantidad_disponible }}" placeholder="0.00" required>
                                </div>
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="fas fa-minus"></i> Consumir
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer d-flex justify-content-between">
            <div>
                <a href="{{ route('lotes.edit', $lote) }}" class="btn btn-cancel btn-sm">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
            <a href="{{ route('lotes.index') }}" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection
