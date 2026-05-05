@extends('layouts.adminlte')

@section('title', 'Detalle de Movimiento')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1 class="h3 mb-0">
                <i class="fas fa-arrows-alt-v"></i> 
                {{ ucfirst($tipo) }} #{{ $referenciaId }}
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    {{-- Resumen --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3>{{ $movimientos->count() }}</h3>
                    <p>Movimientos</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>{{ $movimientos->sum(function($m) { return $m->cantidad > 0 ? $m->cantidad : 0; }) }}</h3>
                    <p>Total Ingresos</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3>{{ $movimientos->sum(function($m) { return $m->cantidad < 0 ? abs($m->cantidad) : 0; }) }}</h3>
                    <p>Total Egresos</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Información general --}}
    @if($encabezado)
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información General</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Tipo:</strong> {{ ucfirst($tipo) }}</p>
                    <p><strong>Referencia:</strong> #{{ $referenciaId }}</p>
                    <p><strong>Fecha:</strong> {{ $encabezado->fecha_movimiento->format('d/m/Y H:i') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Estado:</strong> 
                        <span class="badge badge-{{ $encabezado->estado == 'completado' ? 'success' : 'warning' }}">
                            {{ ucfirst($encabezado->estado) }}
                        </span>
                    </p>
                    <p><strong>Observaciones:</strong> {{ $encabezado->observaciones ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Detalle de cada movimiento --}}
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Detalle de Movimientos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tipo</th>
                            <th>Almacén</th>
                            <th>Item</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <th>Costo Total</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movimientos as $index => $mov)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @switch($mov->tipo_movimiento)
                                    @case('ingreso')
                                        <span class="badge badge-success">📥 Ingreso</span>
                                        @break
                                    @case('egreso')
                                        <span class="badge badge-danger">📤 Egreso</span>
                                        @break
                                    @case('traspaso_origen')
                                        <span class="badge badge-warning">🔄 Traspaso (Salida)</span>
                                        @break
                                    @case('traspaso_destino')
                                        <span class="badge badge-info">🔄 Traspaso (Entrada)</span>
                                        @break
                                    @default
                                        <span class="badge badge-secondary">{{ $mov->tipo_movimiento }}</span>
                                @endswitch
                            </td>
                            <td>{{ \App\Models\Almacen::find($mov->id_almacen)->nombre ?? 'N/A' }}</td>
                            <td>{{ \App\Models\Item::find($mov->id_item)->nombre ?? 'N/A' }}</td>
                            <td>
                                <strong class="{{ $mov->cantidad >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $mov->cantidad >= 0 ? '+' : '' }}{{ $mov->cantidad }}
                                </strong>
                            </td>
                            <td>Bs. {{ number_format($mov->precio_unitario, 2) }}</td>
                            <td>Bs. {{ number_format(abs($mov->costo_total), 2) }}</td>
                            <td><small>{{ $mov->observaciones ?? '-' }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-light">
                            <td colspan="4" class="text-right"><strong>Totales:</strong></td>
                            <td>
                                <strong>
                                    Ingresos: {{ $movimientos->sum(function($m) { return $m->cantidad > 0 ? $m->cantidad : 0; }) }}<br>
                                    Egresos: {{ $movimientos->sum(function($m) { return $m->cantidad < 0 ? abs($m->cantidad) : 0; }) }}
                                </strong>
                            </td>
                            <td></td>
                            <td><strong>Bs. {{ number_format($movimientos->sum('costo_total'), 2) }}</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Enlace a la referencia original --}}
    {{-- Enlace a la referencia original --}}
<div class="text-center mt-3">
    @if($tipo == 'compra' && $referenciaId)
        <a href="#" class="btn btn-primary" onclick="verDetalleNota({{ $referenciaId }})">
            <i class="fas fa-external-link-alt"></i> Ver Compra #{{ $referenciaId }}
        </a>
    @elseif($tipo == 'venta' && $referenciaId)
        <a href="#" class="btn btn-primary" onclick="verDetalleNotaVenta({{ $referenciaId }})">
            <i class="fas fa-external-link-alt"></i> Ver Venta #{{ $referenciaId }}
        </a>
    @elseif($tipo == 'produccion' && $referenciaId)
        <a href="{{ route('producciones.show', $referenciaId) }}" class="btn btn-primary">
            <i class="fas fa-external-link-alt"></i> Ver Producción #{{ $referenciaId }}
        </a>
    @elseif($tipo == 'traspaso' && $referenciaId)
        <a href="{{ route('traspasos.show', $referenciaId) }}" class="btn btn-primary">
            <i class="fas fa-external-link-alt"></i> Ver Traspaso #{{ $referenciaId }}
        </a>
    @endif
</div>
</div>
@endsection