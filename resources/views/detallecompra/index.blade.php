@extends('layouts.adminlte')

@section('title', 'Historial de Compras - Panadería Otto')
@section('page-title', 'Compras')
@section('page-description', 'Historial de notas y detalles de compra')

@push('styles')
<style>
    .nav-tabs-custom {
        border-radius: var(--border-radius-md);
        overflow: hidden;
    }
    .nav-tabs-custom .nav-link {
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .nav-tabs-custom .nav-link.active {
        background: var(--color-bg-light);
        border-bottom-color: var(--color-bg-light);
        color: var(--color-primary-dark);
        font-weight: 600;
    }
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
        color: var(--text-muted);
    }
    .empty-state i {
        font-size: 3rem;
        opacity: 0.4;
        margin-bottom: 1rem;
    }
    .monto-total {
        font-weight: 700;
        color: var(--color-primary-dark);
    }
    .estado-badge {
        font-size: 0.8rem;
        padding: 0.35rem 0.7rem;
    }
    .action-btn {
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    {{-- Encabezado --}}
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0">
                <i class="fas fa-receipt icon-panaderia"></i> Historial de Compras
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('compras.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-cart-plus"></i> Nueva Compra
            </a>
        </div>
    </div>

    {{-- Mensajes --}}
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> {{ $message }}
        </div>
    @endif

    {{-- TABS --}}
    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header p-0">
            <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab-notas" role="tab">
                        <i class="fas fa-receipt mr-1"></i> Notas de Compra
                        <span class="badge badge-primary ml-1">{{ $notasCompra->total() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab-detalles" role="tab">
                        <i class="fas fa-barcode mr-1"></i> Detalles de Compra
                        <span class="badge badge-info ml-1">{{ $detallesCompra->total() }}</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                
                {{-- TAB: NOTAS DE COMPRA --}}
                <div class="tab-pane fade show active" id="tab-notas" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Empleado</th>
                                    <th class="text-right">Monto</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($notasCompra as $nota)
                                    <tr>
                                        <td><span class="badge badge-info">#{{ $nota->id_nota_compra }}</span></td>
                                        <td>{{ $nota->fecha_compra ? $nota->fecha_compra->format('d/m/Y') : 'Sin fecha' }}</td>
                                        <td>{{ $nota->proveedor->persona->nombre ?? $nota->proveedor->empresa->razon_social ?? 'N/A' }}</td>
                                        <td>{{ $nota->empleado->nombre ?? 'N/A' }}</td>
                                        <td class="text-right monto-total">Bs. {{ number_format($nota->monto_total, 2) }}</td>
                                        <td>
                                            @php
                                                $estadoClass = match($nota->estado) {
                                                    'completada' => 'success',
                                                    'pendiente' => 'warning',
                                                    'cancelada' => 'danger',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge badge-{{ $estadoClass }} estado-badge">
                                                {{ ucfirst($nota->estado) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('notas-compra.show', $nota->id_nota_compra) }}" 
                                               class="btn btn-info btn-sm action-btn" title="Ver comprobante">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <i class="fas fa-receipt"></i>
                                                <p>No hay notas de compra registradas</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($notasCompra->count() > 0)
                        <div class="d-flex justify-content-center mt-3">
                            {{ $notasCompra->appends(['tab' => 'notas'])->links() }}
                        </div>
                    @endif
                </div>

                {{-- TAB: DETALLES DE COMPRA --}}
                <div class="tab-pane fade" id="tab-detalles" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Nota #</th>
                                    <th>Insumo</th>
                                    <th>Almacén</th>
                                    <th class="text-right">Cantidad</th>
                                    <th class="text-right">Precio Unit.</th>
                                    <th class="text-right">Subtotal</th>
                                    <th class="text-center">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($detallesCompra as $detalle)
                                    <tr>
                                        <td>
                                            <a href="{{ route('notas-compra.show', $detalle->id_nota_compra) }}" 
                                               class="badge badge-info">#{{ $detalle->id_nota_compra }}</a>
                                        </td>
                                        <td>{{ $detalle->item->nombre ?? 'N/A' }}</td>
                                        <td>{{ $detalle->almacen->nombre ?? 'N/A' }}</td>
                                        <td class="text-right">{{ $detalle->cantidad }}</td>
                                        <td class="text-right">Bs. {{ number_format($detalle->precio, 2) }}</td>
                                        <td class="text-right monto-total">Bs. {{ number_format($detalle->cantidad * $detalle->precio, 2) }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('notas-compra.show', $detalle->id_nota_compra) }}" 
                                               class="btn btn-info btn-sm action-btn" title="Ver nota completa">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <i class="fas fa-barcode"></i>
                                                <p>No hay detalles de compra registrados</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($detallesCompra->count() > 0)
                        <div class="d-flex justify-content-center mt-3">
                            {{ $detallesCompra->appends(['tab' => 'detalles'])->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if (window.location.hash) {
            $('.nav-tabs a[href="' + window.location.hash + '"]').tab('show');
        }
        $('.nav-tabs a').on('shown.bs.tab', function(e) {
            window.location.hash = e.target.hash;
        });
    });
</script>
@endpush