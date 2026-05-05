@extends('layouts.adminlte')

@section('title', 'Movimientos de Inventario')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-arrows-alt-v"></i> Movimientos de Inventario</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('produccion.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Movimientos Agrupados</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Referencia</th>
                            <th>Tipo(s)</th>
                            <th>Ingresos</th>
                            <th>Egresos</th>
                            <th>Items</th>
                            <th>Costo Total</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimientos as $mov)
                            @php
                                $tipos = explode(',', $mov->tipos);
                                $esIngreso = in_array('ingreso', $tipos);
                                $esEgreso = in_array('egreso', $tipos);
                                $esTraspaso = in_array('traspaso_origen', $tipos) || in_array('traspaso_destino', $tipos);
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($mov->fecha_movimiento)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge badge-secondary">{{ ucfirst($mov->referencia_tipo) }} #{{ $mov->referencia_id }}</span>
                                </td>
                                <td>
                                    @if($esTraspaso)
                                        <span class="badge badge-info">🔄 Traspaso</span>
                                    @elseif($esIngreso && !$esEgreso)
                                        <span class="badge badge-success">📥 Ingreso</span>
                                    @elseif($esEgreso && !$esIngreso)
                                        <span class="badge badge-danger">📤 Egreso</span>
                                    @else
                                        <span class="badge badge-warning">📦 Mixto</span>
                                    @endif
                                </td>
                                <td>
                                    @if($mov->total_ingresos > 0)
                                        <span class="text-success">+{{ $mov->total_ingresos }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($mov->total_egresos > 0)
                                        <span class="text-danger">-{{ $mov->total_egresos }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td><span class="badge badge-pill badge-light">{{ $mov->items_count }}</span></td>
                                <td>Bs. {{ number_format($mov->costo_total, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ $mov->estado == 'completado' ? 'success' : 'warning' }}">
                                        {{ ucfirst($mov->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('movimientos.show', $mov->referencia_id) }}?tipo={{ $mov->referencia_tipo }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No hay movimientos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $movimientos->links() }}
        </div>
    </div>
</div>
@endsection