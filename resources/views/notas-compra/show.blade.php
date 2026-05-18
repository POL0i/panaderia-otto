@extends('layouts.adminlte')

@section('title', 'Nota de Compra #' . $notaCompra->id_nota_compra)
@section('page-title', 'Nota de Compra')
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
            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
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
                        <h5><strong>NOTA DE COMPRA</strong></h5>
                        <h6><span class="badge badge-info">#{{ $notaCompra->id_nota_compra }}</span></h6>
                        <small>Fecha: {{ $notaCompra->fecha_compra ? $notaCompra->fecha_compra->format('d/m/Y') : 'Sin fecha' }}</small>
                    </div>
                </div>
            </div>

            {{-- Proveedor y Empleado --}}
            <div class="p-3 border-bottom">
                <div class="row">
                    <div class="col-6">
                        <strong>Proveedor:</strong><br>
                        {{ $notaCompra->proveedor->persona->nombre ?? $notaCompra->proveedor->empresa->razon_social ?? 'No asignado' }}<br>
                        <small>Tel: {{ $notaCompra->proveedor->telefono ?? 'N/A' }}</small>
                    </div>
                    <div class="col-6 text-right">
                        <strong>Registrado por:</strong><br>
                        {{ $notaCompra->empleado->nombre ?? 'No asignado' }}
                    </div>
                </div>
            </div>

            {{-- Detalles --}}
            <div class="p-3">
                <h6><i class="fas fa-boxes"></i> Insumos</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Cant.</th>
                                <th>Insumo</th>
                                <th>Almacén</th>
                                <th class="text-right">P. Unit.</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notaCompra->detalles as $detalle)
                            <tr>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>{{ $detalle->insumo->nombre ?? $detalle->item->nombre ?? 'N/A' }}</td>
                                <td>{{ $detalle->almacen->nombre ?? 'N/A' }}</td>
                                <td class="text-right">Bs. {{ number_format($detalle->precio, 2) }}</td>
                                <td class="text-right">Bs. {{ number_format($detalle->cantidad * $detalle->precio, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">Sin insumos registrados</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                <td class="text-right"><strong>Bs. {{ number_format($notaCompra->monto_total ?? 0, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Estado --}}
            <div class="p-3 recibo-footer d-flex justify-content-between align-items-center">
                @php
                    $estado = $notaCompra->estado ?? '';
                    $estadoClass = match($estado) {
                        'completada' => 'success',
                        'pendiente' => 'warning',
                        'cancelada' => 'danger',
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