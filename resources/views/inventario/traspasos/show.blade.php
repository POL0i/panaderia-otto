@extends('layouts.adminlte')

@section('title', 'Traspaso #' . $traspaso->id_traspaso)

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-exchange-alt"></i> Traspaso #{{ $traspaso->id_traspaso }}</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('traspasos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Información general --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información del Traspaso</h5>
                </div>
                <div class="card-body">
                    <p><strong>ID:</strong> #{{ $traspaso->id_traspaso }}</p>
                   <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($traspaso->fecha_traspaso)->format('d/m/Y H:i') }}</p>
                    <p><strong>Empleado:</strong> {{ $traspaso->empleado->nombre ?? 'N/A' }}</p>
                    <p><strong>Descripción:</strong> {{ $traspaso->descripcion ?? '-' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-warehouse"></i> Almacenes</h5>
                </div>
                <div class="card-body">
                    @if($traspaso->detalles->isNotEmpty())
                        @php $primerDetalle = $traspaso->detalles->first(); @endphp
                        <p><strong>Origen:</strong> {{ $primerDetalle->almacenOrigen()->nombre ?? 'N/A' }}</p>
                        <p><strong>Destino:</strong> {{ $primerDetalle->almacenDestino()->nombre ?? 'N/A' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Detalle de items --}}
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Items Traspasados</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                    <th>Unidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($traspaso->detalles as $detalle)
                                <tr>
                                    <td>{{ $detalle->item()->nombre ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $detalle->item()->tipo_item == 'producto' ? 'success' : 'warning' }}">
                                            {{ $detalle->item()->tipo_item ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td><strong>{{ $detalle->cantidad }}</strong></td>
                                    <td>{{ $detalle->item()->unidad_medida ?? 'unidad' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Acciones --}}
    <div class="mt-3">
        <form action="{{ route('traspasos.destroy', $traspaso) }}" method="POST" style="display:inline;"
              onsubmit="return confirm('¿Eliminar este traspaso? Se revertirá el stock.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Eliminar y Revertir
            </button>
        </form>
    </div>
</div>
@endsection