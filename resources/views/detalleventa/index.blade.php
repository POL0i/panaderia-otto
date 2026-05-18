@extends('layouts.adminlte')

@section('title', 'Notas y Detalles de Venta - Panadería Otto')
@section('page-title', 'Ventas')
@section('page-description', 'Historial de notas y detalles de venta')

@push('styles')
<style>
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
    .ver-btn {
        transition: all 0.2s ease;
    }
    .ver-btn:hover {
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
                <i class="fas fa-history icon-panaderia"></i> Historial de Ventas
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('ventas.index') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-cart-plus"></i> Nueva Venta
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
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab-notas" role="tab">
                        <i class="fas fa-file-invoice-dollar mr-1"></i> Notas de Venta
                        <span class="badge badge-primary ml-1">{{ $notasVenta->total() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab-detalles" role="tab">
                        <i class="fas fa-list-alt mr-1"></i> Detalles de Venta
                        <span class="badge badge-info ml-1">{{ $detallesVenta->total() }}</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                
                {{-- TAB: NOTAS DE VENTA --}}
                <div class="tab-pane fade show active" id="tab-notas" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Empleado</th>
                                    <th>Método</th>
                                    <th class="text-right">Monto</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($notasVenta as $nota)
                                    <tr>
                                        <td><span class="badge badge-info">#{{ $nota->id_nota_venta }}</span></td>
                                        <td>{{ $nota->fecha_venta->format('d/m/Y H:i') }}</td>
                                        <td>{{ $nota->cliente->nombre ?? 'N/A' }}</td>
                                        <td>{{ $nota->empleado->nombre ?? 'N/A' }}</td>
                                        <td>
                                            @if($nota->metodo_pago === 'libelula')
                                                <span class="badge badge-info">Libélula</span>
                                            @elseif($nota->metodo_pago === 'efectivo')
                                                <span class="badge badge-success">Efectivo</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $nota->metodo_pago ?? 'N/A' }}</span>
                                            @endif
                                        </td>
                                        <td class="text-right monto-total">Bs. {{ number_format($nota->monto_total, 2) }}</td>
                                        <td>
                                            @php
                                                $estadoClass = match($nota->estado) {
                                                    'completado' => 'success',
                                                    'pendiente' => 'warning',
                                                    'cancelado' => 'danger',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge badge-{{ $estadoClass }}">
                                                {{ ucfirst($nota->estado) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('notas-venta.show', $nota->id_nota_venta) }}" 
                                               class="btn btn-info btn-sm ver-btn" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="empty-state">
                                                <i class="fas fa-file-invoice-dollar"></i>
                                                <p>No hay notas de venta registradas</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($notasVenta->count() > 0)
                        <div class="d-flex justify-content-center mt-3">
                            {{ $notasVenta->appends(['tab' => 'notas'])->links() }}
                        </div>
                    @endif
                </div>

                {{-- TAB: DETALLES DE VENTA --}}
                <div class="tab-pane fade" id="tab-detalles" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Nota #</th>
                                    <th>Producto</th>
                                    <th>Almacén</th>
                                    <th class="text-right">Cant.</th>
                                    <th class="text-right">Precio U.</th>
                                    <th class="text-right">Subtotal</th>
                                    <th class="text-center">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($detallesVenta as $detalle)
                                    <tr>
                                        <td>
                                            <span class="badge badge-info">#{{ $detalle->id_nota_venta }}</span>
                                        </td>
                                        <td>
                                            <i class="fas fa-box icon-panaderia mr-1"></i>
                                            {{ $detalle->item->nombre ?? 'N/A' }}
                                        </td>
                                        <td>
                                            <i class="fas fa-warehouse text-muted mr-1"></i>
                                            {{ $detalle->almacen->nombre ?? 'N/A' }}
                                        </td>
                                        <td class="text-right">{{ $detalle->cantidad }}</td>
                                        <td class="text-right">Bs. {{ number_format($detalle->precio, 2) }}</td>
                                        <td class="text-right monto-total">
                                            Bs. {{ number_format($detalle->cantidad * $detalle->precio, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('notas-venta.show', $detalle->id_nota_venta) }}" 
                                               class="btn btn-info btn-sm ver-btn" title="Ver nota">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">
                                            <div class="empty-state">
                                                <i class="fas fa-list-alt"></i>
                                                <p>No hay detalles de venta registrados</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($detallesVenta->count() > 0)
                        <div class="d-flex justify-content-center mt-3">
                            {{ $detallesVenta->appends(['tab' => 'detalles'])->links() }}
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