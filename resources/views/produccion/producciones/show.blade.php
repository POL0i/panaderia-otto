{{-- resources/views/produccion/producciones/show.blade.php --}}
@extends('layouts.adminlte')

@section('title', 'Producción #' . $produccion->id_produccion . ' - Panadería Otto')
@section('page-title', 'Detalle de Producción #' . $produccion->id_produccion)
@section('page-description', 'Revisión y autorización de orden de producción')

@push('styles')
<style>
    .info-row p { margin-bottom: 0.5rem; }
    .info-row strong { color: var(--color-primary-dark); }
    .lote-table th { white-space: nowrap; font-size: 0.85rem; }
    .lote-table td { font-size: 0.85rem; vertical-align: middle; }
    .aprobacion-card { border: 2px solid var(--badge-success); }
    .rechazo-card { border: 2px solid var(--badge-danger); }
    .almacen-badge {
        font-size: 0.9rem;
        background: var(--color-bg-lighter);
        padding: 0.4rem 0.8rem;
        border-radius: var(--border-radius-sm);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- Mensajes --}}
    @if(session('error'))
        <div class="alert alert-danger">{!! nl2br(e(session('error'))) !!}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Información principal --}}
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información de la Producción</h5>
                </div>
                <div class="card-body info-row">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID:</strong> #{{ $produccion->id_produccion }}</p>
                            <p><strong>Fecha producción:</strong> {{ \Carbon\Carbon::parse($produccion->fecha_produccion)->format('d/m/Y') }}</p>
                            <p><strong>Cantidad a producir:</strong> {{ $produccion->cantidad_producida }}</p>
                            <p><strong>Solicitante:</strong> {{ $produccion->empleadoSolicita->nombre ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Estado:</strong> 
                                @switch($produccion->estado)
                                    @case('pendiente') <span class="badge badge-warning">Pendiente</span> @break
                                    @case('aprobado') <span class="badge badge-success">Aprobado</span> @break
                                    @case('rechazado') <span class="badge badge-danger">Rechazado</span> @break
                                    @case('cancelado') <span class="badge badge-secondary">Cancelado</span> @break
                                @endswitch
                            </p>
                            <p><strong>Fecha solicitud:</strong> {{ $produccion->fecha_solicitud ? $produccion->fecha_solicitud->format('d/m/Y H:i') : 'No registrada' }}</p>
                            @if($produccion->fecha_autorizacion)
                                <p><strong>Autorizado por:</strong> {{ $produccion->empleadoAutoriza->nombre ?? 'N/A' }}</p>
                                <p><strong>Fecha autorización:</strong> {{ $produccion->fecha_autorizacion->format('d/m/Y H:i') }}</p>
                            @endif
                            @if($produccion->observaciones)
                                <p><strong>Observaciones:</strong> {{ $produccion->observaciones }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('producciones.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a lista
            </a>
        </div>
    </div>

    {{-- Receta utilizada --}}
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    @php
                        $detalleConReceta = $produccion->detalles
                            ->whereNotNull('id_detalle_receta')
                            ->first();
                        $receta = $detalleConReceta?->detalleReceta?->receta;
                    @endphp
                    <h5 class="mb-0"><i class="fas fa-book"></i> Receta: 
                        <strong>{{ $receta->nombre ?? 'N/A' }}</strong>
                        @if($receta && $receta->producto)
                            → Producto final: <strong>{{ $receta->producto->item->nombre ?? 'N/A' }}</strong>
                        @endif
                    </h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Almacenes utilizados (visible solo si está aprobada) --}}
    @if($produccion->estado == 'aprobado')
        @php
            $detalleEgreso = $produccion->detalles->where('tipo_movimiento', 'egreso')->first();
            $detalleIngreso = $produccion->detalles->where('tipo_movimiento', 'ingreso')->first();
            $almacenOrigen = $detalleEgreso?->almacen;
            $almacenDestino = $detalleIngreso?->almacen;
        @endphp
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-warehouse"></i> Almacenes utilizados</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <span class="almacen-badge">
                                    <i class="fas fa-arrow-down text-danger"></i> 
                                    <strong>Origen (insumos):</strong> 
                                    {{ $almacenOrigen->nombre ?? 'No asignado' }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <span class="almacen-badge">
                                    <i class="fas fa-arrow-up text-success"></i> 
                                    <strong>Destino (producto):</strong> 
                                    {{ $almacenDestino->nombre ?? 'No asignado' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Detalles de la producción --}}
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Movimientos Planificados</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Ítem</th>
                                    <th>Almacén actual</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produccion->detalles as $detalle)
                                <tr>
                                    <td>
                                        @if($detalle->tipo_movimiento == 'egreso')
                                            <span class="badge badge-danger">Consume (Insumo)</span>
                                        @else
                                            <span class="badge badge-success">Produce (Producto)</span>
                                        @endif
                                    </td>
                                    <td>{{ $detalle->item->nombre ?? 'Item #' . $detalle->id_item }}</td>
                                    <td>
                                        @if($detalle->id_almacen && $detalle->id_almacen != 1)
                                            {{ $detalle->almacen->nombre ?? 'Almacén #' . $detalle->id_almacen }}
                                        @else
                                            <span class="text-muted">Pendiente de asignación</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $detalle->cantidad }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Lotes afectados --}}
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Lotes Afectados</h5>
                </div>
                <div class="card-body">
                    @if($produccion->estado == 'aprobado')
                        @php
                            $lotesProducidos = \App\Models\LoteInventario::where('referencia_id', $produccion->id_produccion)
                                ->where('referencia_tipo', 'produccion')
                                ->orderBy('id_lote', 'desc')
                                ->get();
                            
                            $consumosProduccion = $produccion->detalles()
                                ->where('tipo_movimiento', 'egreso')
                                ->get();
                        @endphp
                        
                        @if($lotesProducidos->count() > 0 || $consumosProduccion->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered lote-table">
                                    <thead>
                                        <tr>
                                            <th>Lote</th>
                                            <th>Tipo</th>
                                            <th>Item</th>
                                            <th>Almacén</th>
                                            <th>Cant. Inicial</th>
                                            <th>Cant. Disponible</th>
                                            <th>Esta producción</th>
                                            <th>Total consumido</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Lotes de PRODUCTO GENERADO --}}
                                        @foreach($lotesProducidos as $lote)
                                        <tr class="table-success">
                                            <td>#{{ $lote->id_lote }}</td>
                                            <td><span class="badge badge-success">Producido</span></td>
                                            <td>{{ $lote->item_nombre }}</td>
                                            <td>{{ $lote->almacen_nombre }}</td>
                                            <td class="text-center">{{ number_format($lote->cantidad_inicial, 2) }}</td>
                                            <td class="text-center">{{ number_format($lote->cantidad_disponible, 2) }}</td>
                                            <td class="text-center"><strong>{{ number_format($lote->cantidad_inicial, 2) }}</strong></td>
                                            <td class="text-center">-</td>
                                            <td><span class="badge badge-success">Ingresado</span></td>
                                        </tr>
                                        @endforeach
                                        
                                        {{-- CONSUMOS DE INSUMOS --}}
                                        @foreach($consumosProduccion as $consumo)
                                            @php
                                                // Solo se buscan lotes si hay fecha de autorización (está aprobado)
                                                $lotesAfectados = \App\Models\LoteInventario::where('id_almacen', $consumo->id_almacen)
                                                    ->where('id_item', $consumo->id_item)
                                                    ->whereNotNull('fecha_salida')
                                                    ->whereDate('fecha_salida', $produccion->fecha_autorizacion->toDateString())
                                                    ->orderBy('fecha_salida', 'desc')
                                                    ->get();
                                            @endphp
                                            
                                            @if($lotesAfectados->count() > 0)
                                                @foreach($lotesAfectados as $lote)
                                                <tr class="table-danger">
                                                    <td>#{{ $lote->id_lote }}</td>
                                                    <td><span class="badge badge-danger">Consumido</span></td>
                                                    <td>{{ $consumo->item->nombre ?? 'Item #' . $consumo->id_item }}</td>
                                                    <td>{{ $consumo->almacen->nombre ?? 'Almacén #' . $consumo->id_almacen }}</td>
                                                    <td class="text-center">{{ number_format($lote->cantidad_inicial, 2) }}</td>
                                                    <td class="text-center">{{ number_format($lote->cantidad_disponible, 2) }}</td>
                                                    <td class="text-center"><strong class="text-danger">{{ number_format($consumo->cantidad, 2) }}</strong></td>
                                                    <td class="text-center">{{ number_format($lote->cantidad_inicial - $lote->cantidad_disponible, 2) }}</td>
                                                    <td>
                                                        @if($lote->cantidad_disponible == 0)
                                                            <span class="badge badge-secondary">Agotado</span>
                                                        @else
                                                            <span class="badge badge-warning">Parcial</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr class="table-warning">
                                                    <td>-</td>
                                                    <td><span class="badge badge-danger">Consumido</span></td>
                                                    <td>{{ $consumo->item->nombre ?? 'Item #' . $consumo->id_item }}</td>
                                                    <td>{{ $consumo->almacen->nombre ?? 'Almacén #' . $consumo->id_almacen }}</td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center"><strong class="text-danger">{{ number_format($consumo->cantidad, 2) }}</strong></td>
                                                    <td class="text-center">-</td>
                                                    <td><span class="badge badge-info">Sin lote</span></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center">
                                <i class="fas fa-info-circle"></i> No se encontraron lotes afectados.
                            </p>
                        @endif
                    @else
                        <p class="text-muted text-center">
                            <i class="fas fa-info-circle"></i> Los lotes se mostrarán cuando se apruebe la producción.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- ACCIONES (solo si está pendiente y tiene permiso) --}}
    {{-- ========================================== --}}
    @if($produccion->estado == 'pendiente')
        @if(auth()->user()->esAdmin() || in_array('almacen_ver', auth()->user()->obtenerPermisos()))
        <div class="row mt-3">
            {{-- Aprobación --}}
            <div class="col-md-7">
                <div class="card aprobacion-card">
                    <div class="card-header modal-header-success">
                        <h5 class="mb-0"><i class="fas fa-check-circle"></i> Aprobar Producción y Ejecutar Movimientos</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('producciones.aprobar', $produccion) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>
                                    <i class="fas fa-arrow-down text-danger"></i> 
                                    Almacén de INSUMOS (origen)
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="almacen_origen" id="almacen_origen" class="form-control" required>
                                    <option value="">Seleccione de dónde sacar insumos...</option>
                                    @foreach(\App\Models\Almacen::whereIn('tipo_almacen', ['insumo', 'mixto'])->get() as $alm)
                                        <option value="{{ $alm->id_almacen }}">
                                            {{ $alm->nombre }} ({{ $alm->tipo_almacen }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="info-stock-insumos" class="mt-2 small"></div>
                            </div>

                            <div class="form-group">
                                <label>
                                    <i class="fas fa-arrow-up text-success"></i> 
                                    Almacén de PRODUCTO (destino)
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="almacen_destino" id="almacen_destino" class="form-control" required>
                                    <option value="">Seleccione dónde guardar producto...</option>
                                    @foreach(\App\Models\Almacen::whereIn('tipo_almacen', ['producto', 'mixto'])->get() as $alm)
                                        <option value="{{ $alm->id_almacen }}">
                                            {{ $alm->nombre }} ({{ $alm->tipo_almacen }})
                                            @if($alm->capacidad > 0) - Cap: {{ $alm->capacidad }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div id="info-capacidad-producto" class="mt-2 small"></div>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg btn-block">
                                <i class="fas fa-check"></i> Ejecutar Producción
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Rechazar / Cancelar --}}
            <div class="col-md-5">
                <div class="card rechazo-card">
                    <div class="card-header modal-header-danger">
                        <h5 class="mb-0"><i class="fas fa-times-circle"></i> Rechazar o Cancelar</h5>
                    </div>
                    <div class="card-body text-center">
                        <button class="btn btn-danger btn-lg btn-block mb-3" data-toggle="modal" data-target="#modalMotivoRechazo">
                            <i class="fas fa-times"></i> Rechazar Producción
                        </button>
                        <form action="{{ route('producciones.cancelar', $produccion) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-lg btn-block" 
                                    onclick="return confirm('¿Está seguro de CANCELAR esta producción?')">
                                <i class="fas fa-ban"></i> Cancelar Producción
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal para rechazar --}}
        <div class="modal fade" id="modalMotivoRechazo" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <h5 class="modal-title"><i class="fas fa-times-circle"></i> Rechazar Producción</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="{{ route('producciones.rechazar', $produccion) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Motivo del rechazo <span class="text-danger">*</span></label>
                                <textarea name="motivo" class="form-control" rows="3" required 
                                          placeholder="Explique por qué se rechaza..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Confirmar Rechazo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @else
        <div class="row mt-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-lock"></i> No tienes permisos para aprobar, rechazar o cancelar esta producción. Contacta al administrador o encargado de almacén.
                </div>
            </div>
        </div>
        @endif
    @endif

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const produccionId = {{ $produccion->id_produccion }};
    const selectOrigen = document.getElementById('almacen_origen');
    const divInsumos = document.getElementById('info-stock-insumos');
    const selectDestino = document.getElementById('almacen_destino');
    const divCapacidad = document.getElementById('info-capacidad-producto');

    if (selectOrigen) {
        selectOrigen.addEventListener('change', function() {
            const almacenId = this.value;
            if (!almacenId) { divInsumos.innerHTML = ''; return; }
            divInsumos.innerHTML = '<span class="text-muted"><i class="fas fa-spinner fa-pulse"></i> Verificando...</span>';

            fetch(`/almacen/${almacenId}/insumos-stock/${produccionId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.insumos?.length) {
                        let ok = true, html = '';
                        data.insumos.forEach(item => {
                            let icon = item.suficiente ? 'fa-check-circle text-success' : 'fa-exclamation-circle text-danger';
                            html += `<div class="d-flex align-items-center mb-1">
                                <i class="fas ${icon} mr-2"></i>
                                <span class="flex-fill">${item.nombre}</span>
                                <span class="badge ${item.suficiente ? 'badge-success' : 'badge-danger'} ml-2">${item.stock} / ${item.requerido}</span>
                            </div>`;
                            if (!item.suficiente) ok = false;
                        });
                        divInsumos.innerHTML = `<div class="alert ${ok ? 'alert-success' : 'alert-danger'} py-2 px-3 mb-0 mt-2">
                            <i class="fas ${ok ? 'fa-check-circle' : 'fa-exclamation-triangle'} mr-1"></i>
                            <strong>${ok ? 'Stock suficiente' : 'Stock insuficiente'}</strong>
                            <div class="mt-1">${html}</div></div>`;
                    } else {
                        divInsumos.innerHTML = '<div class="alert alert-warning py-2 px-3 mb-0 mt-2">No se encontraron insumos.</div>';
                    }
                })
                .catch(() => divInsumos.innerHTML = '<div class="alert alert-danger py-2 px-3 mb-0 mt-2">Error al consultar stock.</div>');
        });
    }

    if (selectDestino) {
        selectDestino.addEventListener('change', function() {
            const almacenId = this.value;
            if (!almacenId) { divCapacidad.innerHTML = ''; return; }
            divCapacidad.innerHTML = '<span class="text-muted"><i class="fas fa-spinner fa-pulse"></i> Verificando...</span>';

            fetch(`/almacen/${almacenId}/capacidad-disponible/${produccionId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.error) {
                        divCapacidad.innerHTML = `<div class="alert alert-danger py-2 px-3 mb-0 mt-2">${data.error}</div>`;
                        return;
                    }
                    const disponible = data.disponible !== null ? data.disponible : '∞';
                    const suficiente = data.suficiente;
                    divCapacidad.innerHTML = `<div class="alert ${suficiente ? 'alert-success' : 'alert-danger'} py-2 px-3 mb-0 mt-2">
                        <i class="fas ${suficiente ? 'fa-check-circle' : 'fa-exclamation-triangle'} mr-1"></i>
                        <strong>${suficiente ? 'Espacio suficiente' : 'Espacio insuficiente'} para ${data.cantidad_prod} unidades</strong><br>
                        <small><i class="fas fa-warehouse"></i> ${data.almacen} | Cap: ${data.capacidad > 0 ? data.capacidad : 'Sin límite'} | Ocupado: ${data.stock_actual}</small>
                    </div>`;
                })
                .catch(() => divCapacidad.innerHTML = '<div class="alert alert-danger py-2 px-3 mb-0 mt-2">Error al verificar capacidad.</div>');
        });
    }
});
</script>
@endpush