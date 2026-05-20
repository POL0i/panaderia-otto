@extends('layouts.adminlte')

@section('title', 'Lotes de Inventario - Panadería Otto')
@section('page-title', 'Lotes de Inventario')
@section('page-description', 'Control de lotes, caducidad y existencias')

@php
    $config = \App\Models\ConfiguracionInventario::obtener();
    $metodoGlobal = $config->metodo_valuacion_predeterminado ?? 'PEPS';
@endphp

@push('styles')
<style>
    /* ========== ALERTAS VISUALES ========== */
    
    /* Fila vencida - rojo intenso */
    .lote-vencido {
        background-color: rgba(220, 53, 69, 0.12) !important;
        border-left: 3px solid #dc3545 !important;
    }
    .lote-vencido:hover {
        background-color: rgba(220, 53, 69, 0.18) !important;
    }
    
    /* Fila próximo a vencer - naranja/amarillo */
    .lote-proximo-vencer {
        background-color: rgba(255, 193, 7, 0.12) !important;
        border-left: 3px solid #ffc107 !important;
    }
    .lote-proximo-vencer:hover {
        background-color: rgba(255, 193, 7, 0.18) !important;
    }
    
    /* Fila con stock bajo - rojo claro */
    .lote-stock-bajo {
        background-color: rgba(255, 0, 0, 0.06) !important;
        border-left: 3px solid #ff6b6b !important;
    }
    .lote-stock-bajo:hover {
        background-color: rgba(255, 0, 0, 0.10) !important;
    }

    /* Badges de estado */
    .badge-estado {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    .estado-disponible { background-color: #28a745; }
    .estado-consumido  { background-color: #6c757d; }
    .estado-anulado   { background-color: #dc3545; }
    
    /* Badge de método */
    .metodo-badge {
        font-size: 0.65rem;
        padding: 2px 6px;
        border-radius: 10px;
        font-weight: 600;
    }
    .metodo-peps { background: #d4edda; color: #155724; }
    .metodo-ueps { background: #fff3cd; color: #856404; }
    
    /* Cantidad usada */
    .cantidad-usada { color: #dc3545; font-size: 0.75rem; }
    
    /* Stock bajo - texto rojo + icono */
    .stock-bajo-texto {
        color: #dc3545 !important;
        font-weight: 700;
    }
    .stock-bajo-icono {
        color: #dc3545;
        animation: pulse-warning 1.5s ease-in-out infinite;
    }
    
    @keyframes pulse-warning {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    /* Alerta de vencimiento en fecha */
    .vencimiento-alerta {
        animation: blink-warning 1s ease-in-out infinite;
    }
    
    @keyframes blink-warning {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
    
    /* Progress bar de stock */
    .progress-stock {
        height: 6px;
        width: 80px;
        margin: 0 auto;
        border-radius: 3px;
        background: #e9ecef;
    }
    .progress-stock .progress-bar-stock {
        border-radius: 3px;
        transition: width 0.3s ease;
    }
    
    /* Filtros */
    .filter-card {
        background: var(--color-bg-lighter, #FFF5E6);
        border: 1px solid var(--color-accent, #D2B48C);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
    }
    .filter-badge {
        display: inline-block;
        padding: 4px 10px;
        margin: 2px;
        border-radius: 20px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    .filter-badge:hover { transform: translateY(-1px); }
    .filter-badge.active { font-weight: bold; border-color: #5D3A1A; }
    .clear-filters {
        cursor: pointer;
        color: #dc3545;
        font-size: 0.8rem;
        text-decoration: underline;
    }
    
    /* Tooltip de alerta */
    .alerta-tooltip {
        display: inline-block;
        cursor: help;
    }
    
    .pagination svg { width: 1rem !important; height: 1rem !important; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    {{-- Encabezado con alertas --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-panaderia">
                <i class="fas fa-boxes mr-2"></i> Lotes de Inventario
            </h1>
        </div>
        <div class="col-md-6 text-right">
            @php
                $lotesVencidosCount = \App\Models\LoteInventario::where('estado', 'disponible')
                    ->whereNotNull('fecha_vencimiento')
                    ->where('fecha_vencimiento', '<', now())
                    ->count();
                $lotesStockBajoCount = \App\Models\LoteInventario::where('estado', 'disponible')
                    ->where('cantidad_disponible', '<=', 30)
                    ->where('cantidad_disponible', '>', 0)
                    ->count();
            @endphp
            
            @if($lotesVencidosCount > 0)
                <span class="badge badge-danger mr-2" style="font-size: 0.85rem; padding: 6px 12px;">
                    <i class="fas fa-skull"></i> {{ $lotesVencidosCount }} vencido(s)
                </span>
            @endif
            @if($lotesStockBajoCount > 0)
                <span class="badge badge-warning" style="font-size: 0.85rem; padding: 6px 12px; color: #856404;">
                    <i class="fas fa-exclamation-triangle"></i> {{ $lotesStockBajoCount }} stock bajo
                </span>
            @endif
        </div>
    </div>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success py-2"><i class="fas fa-check-circle mr-1"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2"><i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}</div>
    @endif

    {{-- FILTROS --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('lotes.index') }}" id="filtrosForm">
            <div class="row">
                <div class="col-md-4">
                    <label class="text-panaderia font-weight-bold small">
                        <i class="fas fa-search mr-1"></i> Buscar Item
                    </label>
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Nombre del producto o insumo..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="text-panaderia font-weight-bold small">
                        <i class="fas fa-warehouse mr-1"></i> Almacén
                    </label>
                    <select name="almacen" class="form-control form-control-sm">
                        <option value="">Todos los almacenes</option>
                        @foreach(\App\Models\Almacen::orderBy('nombre')->get() as $alm)
                            <option value="{{ $alm->id_almacen }}" {{ request('almacen') == $alm->id_almacen ? 'selected' : '' }}>
                                {{ $alm->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="text-panaderia font-weight-bold small">
                        <i class="fas fa-circle mr-1"></i> Estado
                    </label>
                    <select name="estado" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        <option value="disponible" {{ request('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="consumido" {{ request('estado') == 'consumido' ? 'selected' : '' }}>Consumido</option>
                        <option value="anulado" {{ request('estado') == 'anulado' ? 'selected' : '' }}>Anulado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="text-panaderia font-weight-bold small">
                        <i class="fas fa-clock mr-1"></i> Vencimiento
                    </label>
                    <select name="vencimiento" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        <option value="vencido" {{ request('vencimiento') == 'vencido' ? 'selected' : '' }}>Vencidos</option>
                        <option value="proximo" {{ request('vencimiento') == 'proximo' ? 'selected' : '' }}>Próximos (30 días)</option>
                        <option value="vigente" {{ request('vencimiento') == 'vigente' ? 'selected' : '' }}>Vigentes</option>
                        <option value="sin_vencimiento" {{ request('vencimiento') == 'sin_vencimiento' ? 'selected' : '' }}>Sin vencimiento</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-sm btn-primary btn-block" style="border-radius: 20px;">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </div>
            
            {{-- Filtros rápidos --}}
            <div class="mt-2">
                <small class="text-muted">Filtros rápidos:</small>
                <span class="filter-badge {{ request('estado') == 'disponible' && !request('vencimiento') ? 'active' : '' }}" 
                      style="background: #d4edda; color: #155724; border-color: #28a745;"
                      onclick="setFilter('estado', 'disponible')">
                    <i class="fas fa-check-circle"></i> Disponibles
                </span>
                <span class="filter-badge {{ request('vencimiento') == 'proximo' ? 'active' : '' }}" 
                      style="background: #fff3cd; color: #856404; border-color: #ffc107;"
                      onclick="setFilter('vencimiento', 'proximo')">
                    <i class="fas fa-exclamation-triangle"></i> Por vencer (30d)
                </span>
                <span class="filter-badge {{ request('vencimiento') == 'vencido' ? 'active' : '' }}" 
                      style="background: #f8d7da; color: #721c24; border-color: #dc3545;"
                      onclick="setFilter('vencimiento', 'vencido')">
                    <i class="fas fa-skull"></i> Vencidos
                </span>
                <span class="filter-badge {{ request('stock_bajo') ? 'active' : '' }}" 
                      style="background: #ffe0e0; color: #a71d2a; border-color: #ff6b6b;"
                      onclick="setFilter('stock_bajo', '1')">
                    <i class="fas fa-layer-group"></i> Stock bajo (≤30)
                </span>
                @if(request()->anyFilled(['search', 'almacen', 'estado', 'vencimiento', 'stock_bajo']))
                    <span class="clear-filters ml-2" onclick="clearFilters()">
                        <i class="fas fa-times-circle"></i> Limpiar filtros
                    </span>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabla principal --}}
    <div class="card shadow-sm">
        <div class="card-header py-2">
            <h6 class="mb-0">
                <i class="fas fa-list mr-1"></i> Listado de Lotes
                <small class="text-muted ml-2">
                    ({{ $lotes->total() }} resultados)
                    <span class="metodo-badge {{ $metodoGlobal == 'PEPS' ? 'metodo-peps' : 'metodo-ueps' }} ml-2">
                        {{ $metodoGlobal }}
                    </span>
                </small>
            </h6>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Almacén</th>
                            <th>Item</th>
                            <th class="text-center">Stock</th>
                            <th class="text-right">Precio</th>
                            <th class="text-center">Ingreso</th>
                            <th class="text-center">Vencimiento</th>
                            <th class="text-center">Mét.</th>
                            <th class="text-center">E</th>
                            <th class="text-center"><i class="fas fa-eye"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lotes as $lote)
                            @php
                                $vencido = false;
                                $proximoAVencer = false;
                                $diasRestantes = null;
                                $stockBajo = $lote->cantidad_disponible <= 30 && $lote->cantidad_disponible > 0;
                                
                                if ($lote->fecha_vencimiento) {
                                    $fechaVenc = $lote->fecha_vencimiento instanceof \Carbon\Carbon 
                                        ? $lote->fecha_vencimiento 
                                        : \Carbon\Carbon::parse($lote->fecha_vencimiento);
                                    $diasRestantes = now()->startOfDay()->diffInDays($fechaVenc, false);
                                    $vencido = $diasRestantes < 0;
                                    $proximoAVencer = !$vencido && $diasRestantes <= 30;
                                }
                                
                                $consumido = $lote->cantidad_inicial - $lote->cantidad_disponible;
                                $porcentajeConsumido = $lote->cantidad_inicial > 0 
                                    ? round(($consumido / $lote->cantidad_inicial) * 100) 
                                    : 0;
                                $porcentajeDisponible = 100 - $porcentajeConsumido;
                                
                                // Determinar clase de fila
                                $rowClass = '';
                                if ($vencido && $lote->estado == 'disponible') {
                                    $rowClass = 'lote-vencido';
                                } elseif ($proximoAVencer && $lote->estado == 'disponible') {
                                    $rowClass = 'lote-proximo-vencer';
                                } elseif ($stockBajo && $lote->estado == 'disponible') {
                                    $rowClass = 'lote-stock-bajo';
                                }
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td class="align-middle"><small>#{{ $lote->id_lote }}</small></td>
                                <td class="align-middle">{{ $lote->almacen_nombre }}</td>
                                <td class="align-middle">
                                    <strong>{{ $lote->item_nombre }}</strong>
                                    @if($stockBajo && $lote->estado == 'disponible')
                                        <i class="fas fa-exclamation-triangle stock-bajo-icono ml-1" 
                                           title="¡Stock bajo! Solo quedan {{ $lote->cantidad_disponible }} unidades"></i>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <span class="font-weight-bold {{ $stockBajo && $lote->estado == 'disponible' ? 'stock-bajo-texto' : ($lote->cantidad_disponible > 0 ? 'text-success' : 'text-muted') }}">
                                            {{ $lote->cantidad_disponible }}
                                        </span>
                                        @if($consumido > 0)
                                            <span class="cantidad-usada ml-1">/{{ $lote->cantidad_inicial }}</span>
                                        @endif
                                    </div>
                                    @if($consumido > 0)
                                        <div class="progress-stock mt-1">
                                            <div class="progress-bar-stock bg-{{ $stockBajo ? 'danger' : 'success' }}" 
                                                 style="width: {{ $porcentajeDisponible }}%"></div>
                                            <div class="progress-bar-stock bg-secondary" 
                                                 style="width: {{ $porcentajeConsumido }}%"></div>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-right align-middle"><small>Bs. {{ number_format($lote->precio_unitario, 2) }}</small></td>
                                <td class="text-center align-middle"><small>{{ $lote->fecha_entrada ? $lote->fecha_entrada->format('d/m/y') : '-' }}</small></td>
                                <td class="text-center align-middle">
                                    @if($lote->fecha_vencimiento)
                                        @if($vencido)
                                            <span class="badge badge-danger vencimiento-alerta" 
                                                  title="¡VENCIDO! Hace {{ abs($diasRestantes) }} días">
                                                <i class="fas fa-skull"></i> {{ $fechaVenc->format('d/m/y') }}
                                            </span>
                                        @elseif($proximoAVencer)
                                            <span class="badge badge-warning" 
                                                  title="Quedan {{ $diasRestantes }} días - ¡ATENCIÓN!"
                                                  style="color: #856404; font-weight: 600;">
                                                <i class="fas fa-hourglass-half"></i> {{ $fechaVenc->format('d/m/y') }}
                                            </span>
                                        @else
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> {{ $fechaVenc->format('d/m/y') }}
                                            </small>
                                        @endif
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <span class="metodo-badge {{ $lote->metodo_valuacion == 'PEPS' ? 'metodo-peps' : 'metodo-ueps' }}">
                                        {{ $lote->metodo_valuacion ?? 'PEPS' }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge-estado estado-{{ $lote->estado }}" 
                                          title="{{ ucfirst($lote->estado) }}"></span>
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('lotes.show', $lote) }}" class="btn btn-sm btn-outline-info" title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-3">
                                    <i class="fas fa-search fa-2x d-block mb-2"></i>
                                    No se encontraron lotes con los filtros seleccionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($lotes->hasPages())
            <div class="card-footer bg-panaderia-lighter py-2">
                {{ $lotes->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function setFilter(key, value) {
    const form = document.getElementById('filtrosForm');
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = key;
    input.value = value;
    form.appendChild(input);
    form.submit();
}

function clearFilters() {
    window.location.href = '{{ route("lotes.index") }}';
}
</script>
@endpush