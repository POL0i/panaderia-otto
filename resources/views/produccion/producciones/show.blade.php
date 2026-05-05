{{-- resources/views/produccion/producciones/show.blade.php --}}
@extends('layouts.adminlte')

@section('title', 'Producción #' . $produccion->id_produccion . ' - Panadería Otto')
@section('page-title', 'Detalle de Producción #' . $produccion->id_produccion)
@section('page-description', 'Revisión y autorización de orden de producción')

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
                <div class="card-body">
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
                                    <td>
                                        {{ $detalle->item->nombre ?? 'Item #' . $detalle->id_item }}
                                    </td>
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

    {{-- Acciones según estado --}}
    @if($produccion->estado == 'pendiente')
    <div class="row mt-3">
        {{-- Formulario de aprobación --}}
        <div class="col-md-7">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-check-circle"></i> Aprobar Producción y Ejecutar Movimientos</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('producciones.aprobar', $produccion) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>
                                <i class="fas fa-arrow-down text-danger"></i> 
                                Almacén de INSUMOS (origen) - Se descontarán de aquí
                                <span class="text-danger">*</span>
                            </label>
                            <select name="almacen_origen" class="form-control" required>
                                <option value="">Seleccione de dónde sacar insumos...</option>
                                @foreach(\App\Models\Almacen::whereIn('tipo_almacen', ['insumo', 'mixto'])->get() as $alm)
                                    <option value="{{ $alm->id_almacen }}">
                                        {{ $alm->nombre }} ({{ $alm->tipo_almacen }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>
                                <i class="fas fa-arrow-up text-success"></i> 
                                Almacén de PRODUCTO (destino) - Se ingresará aquí
                                <span class="text-danger">*</span>
                            </label>
                            <select name="almacen_destino" class="form-control" required>
                                <option value="">Seleccione dónde guardar producto...</option>
                                @foreach(\App\Models\Almacen::whereIn('tipo_almacen', ['producto', 'mixto'])->get() as $alm)
                                    <option value="{{ $alm->id_almacen }}">
                                        {{ $alm->nombre }} ({{ $alm->tipo_almacen }})
                                        @if($alm->capacidad > 0) - Cap: {{ $alm->capacidad }} @endif
                                    </option>
                                @endforeach>
                            </select>
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
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-times-circle"></i> Rechazar o Cancelar</h5>
                </div>
                <div class="card-body text-center">
                    <button class="btn btn-danger btn-lg btn-block mb-3" data-toggle="modal" data-target="#modalMotivoRechazo">
                        <i class="fas fa-times"></i> Rechazar Producción
                    </button>
                    <form action="{{ route('producciones.cancelar', $produccion) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-dark btn-lg btn-block" 
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
                <div class="modal-header bg-danger text-white">
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
    @endif

</div>
@endsection