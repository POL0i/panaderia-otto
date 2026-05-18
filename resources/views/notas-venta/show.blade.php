@extends('layouts.adminlte')

@section('title', 'Nota de Venta #' . $notaVenta->id_nota_venta)
@section('page-title', 'Nota de Venta')
@section('page-description', 'Detalle del comprobante')

@push('styles')
<style>
    .recibo-header { background-color: var(--color-bg-lighter); }
    .recibo-footer { background-color: var(--color-bg-lighter); border-top: 1px solid var(--color-border); }
    .recibo-empresa { color: var(--color-primary-dark); }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('detalles-venta.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Volver al historial
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            {{-- Encabezado --}}
            <div class="p-3 border-bottom recibo-header">
                <div class="row">
                    <div class="col-6">
                        <h4 class="recibo-empresa"><strong>PANADERÍA OTTO</strong></h4>
                        <small class="text-muted">NIT: 123456789</small><br>
                        <small class="text-muted">Av. Principal #123, Santa Cruz</small>
                    </div>
                    <div class="col-6 text-right">
                        <h5><strong>NOTA DE VENTA</strong></h5>
                        <h6><span class="badge badge-success">#{{ $notaVenta->id_nota_venta }}</span></h6>
                        <small>Fecha: {{ $notaVenta->fecha_venta ? $notaVenta->fecha_venta->format('d/m/Y') : 'Sin fecha' }}</small>
                    </div>
                </div>
            </div>

            {{-- Cliente y Empleado --}}
            <div class="p-3 border-bottom">
                <div class="row">
                    <div class="col-6">
                        <strong>Cliente:</strong><br>
                        {{ $notaVenta->cliente->nombre ?? 'No asignado' }} {{ $notaVenta->cliente->apellido ?? '' }}<br>
                        <small>Tel: {{ $notaVenta->cliente->telefono ?? 'N/A' }}</small>
                    </div>
                    <div class="col-6 text-right">
                        <strong>Atendido por:</strong><br>
                        {{ $notaVenta->empleado->nombre ?? 'No asignado' }}<br>
                        <small>Método: {{ $notaVenta->metodo_pago ?? 'N/A' }}</small>
                    </div>
                </div>
            </div>

            {{-- Detalles --}}
            <div class="p-3">
                <h6><i class="fas fa-boxes"></i> Productos</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Cant.</th>
                                <th>Producto</th>
                                <th>Almacén</th>
                                <th class="text-right">P. Unit.</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notaVenta->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>{{ $detalle->item->nombre ?? 'N/A' }}</td>
                                <td>{{ $detalle->almacen->nombre ?? 'N/A' }}</td>
                                <td class="text-right">Bs. {{ number_format($detalle->precio, 2) }}</td>
                                <td class="text-right">Bs. {{ number_format($detalle->cantidad * $detalle->precio, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">Sin productos</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                <td class="text-right"><strong>Bs. {{ number_format($notaVenta->monto_total ?? 0, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Estado --}}
            <div class="p-3 recibo-footer d-flex justify-content-between align-items-center">
                @php
                    $estado = $notaVenta->estado ?? '';
                    $estadoClass = match($estado) {
                        'completado' => 'success',
                        'pendiente' => 'warning',
                        'cancelado' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <span class="badge badge-{{ $estadoClass }}" style="font-size:0.9rem;padding:0.5rem 1rem;">
                    {{ $estado ? ucfirst($estado) : 'Sin estado' }}
                </span>
                <small class="text-muted">Documento generado electrónicamente</small>
            </div>
        </div>
    </div>

</div>
@endsection