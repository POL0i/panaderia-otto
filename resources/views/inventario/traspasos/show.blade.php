@extends('layouts.adminlte')

@section('title', 'Ver Traspaso')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-exchange-alt icon-panaderia"></i> Traspaso #{{ $traspaso->id_traspaso }}</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('traspasos.index') }}" class="btn btn-back btn-sm">
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
            <i class="fas fa-times-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Traspaso</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">ID Traspaso:</label>
                        <p class="form-control-plaintext">
                            <span class="badge badge-info">{{ $traspaso->id_traspaso }}</span>
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Almacén Origen:</label>
                        <p class="form-control-plaintext">{{ $traspaso->almacenOrigen->nombre ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Almacén Destino:</label>
                        <p class="form-control-plaintext">{{ $traspaso->almacenDestino->nombre ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Item:</label>
                        <p class="form-control-plaintext">{{ $traspaso->item->nombre ?? 'N/A' }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Cantidad:</label>
                        <p class="form-control-plaintext">{{ $traspaso->cantidad }}</p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Precio Unitario:</label>
                        <p class="form-control-plaintext">${{ number_format($traspaso->precio_unitario, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Fecha del Traspaso:</label>
                        <p class="form-control-plaintext">{{ $traspaso->fecha_traspaso->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Estado:</label>
                        <p class="form-control-plaintext">
                            <span class="badge badge-{{ $traspaso->estado == 'completado' ? 'success' : ($traspaso->estado == 'pendiente' ? 'warning' : 'danger') }}">
                                {{ ucfirst($traspaso->estado) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            @if($traspaso->observaciones)
                <div class="form-group">
                    <label class="font-weight-bold">Observaciones:</label>
                    <p class="form-control-plaintext">{{ $traspaso->observaciones }}</p>
                </div>
            @endif
        </div>
        <div class="card-footer d-flex justify-content-between">
            <div>
                @if($traspaso->estado === 'pendiente')
                    <form action="{{ route('traspasos.completar', $traspaso) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-save btn-sm">
                            <i class="fas fa-check"></i> Completar
                        </button>
                    </form>

                    <form action="{{ route('traspasos.cancelar', $traspaso) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                    </form>
                @endif
                <a href="{{ route('traspasos.edit', $traspaso) }}" class="btn btn-cancel btn-sm">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
            <a href="{{ route('traspasos.index') }}" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection
