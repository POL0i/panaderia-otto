@extends('layouts.adminlte')

@section('title', 'Inventario General')
@section('page-title', 'Inventario - Stock por Almacén')
@section('page-description', 'Gestión de stock por almacén')

@push('styles')
<style>
    /* ==========================================
       ESTILOS ESPECÍFICOS - STOCK POR ALMACÉN
       ========================================== */
    
    /* Badges de stock rápido */
    .stock-summary-badges .badge {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
        margin-right: 0.5rem;
    }
    
    /* Badge de stock en tabla */
    .badge-stock {
        font-size: 0.85rem;
        padding: 0.4rem 0.6rem;
        font-weight: 600;
    }
    
    /* Badges de tipo */
    .badge-tipo-producto { background: var(--badge-success); color: white; }
    .badge-tipo-insumo   { background: var(--badge-info); color: white; }
    
    /* Filtros */
    .filter-btn-group .btn {
        border-radius: 20px;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }
    .filter-btn-group .btn:hover { transform: translateY(-1px); }
    .filter-btn-group .btn.active { font-weight: 600; }
    
    /* Card header oscuro */
    .card-header-dark {
        background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
        color: var(--text-on-primary);
    }
    .card-header-dark .card-title { color: var(--text-on-primary); }
    
    /* Footer de filtros */
    .filter-footer {
        background: var(--color-bg-lighter);
        border-top: 1px solid var(--color-border);
        font-size: 0.85rem;
    }
    
    /* Almacén info */
    .almacen-nombre { color: var(--color-primary-dark); }
    .almacen-ubicacion { color: var(--text-muted); font-size: 0.85rem; }
    
    /* Empty state */
    .empty-state-icon {
        font-size: 3rem;
        color: var(--text-muted);
        display: block;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    {{-- Estadísticas --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card stat-card-accent">
                <div class="stat-number">{{ $totalItems }}</div>
                <div class="stat-label">
                    <i class="fas fa-boxes mr-2"></i>Total Items
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-success">
                <div class="stat-number">{{ $totalProductos }}</div>
                <div class="stat-label">
                    <i class="fas fa-box mr-2"></i>Productos
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-info">
                <div class="stat-number">{{ $totalInsumos }}</div>
                <div class="stat-label">
                    <i class="fas fa-flask mr-2"></i>Insumos
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-number">{{ $almacenItems->total() }}</div>
                <div class="stat-label">
                    <i class="fas fa-warehouse mr-2"></i>Registros Stock
                </div>
            </div>
        </div>
    </div>

    {{-- Stocks rápidos por tipo --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="stock-summary-badges">
                <span class="badge badge-info">
                    <i class="fas fa-flask mr-1"></i> Stock Insumos: {{ $totalInsumoStock }}
                </span>
                <span class="badge badge-success">
                    <i class="fas fa-box mr-1"></i> Stock Productos: {{ $totalProductoStock }}
                </span>
                <span class="badge badge-warning">
                    <i class="fas fa-arrows-alt-h mr-1"></i> Stock Mixto: {{ $totalMixtoStock }}
                </span>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card filter-card mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('almacen-items.index') }}">
                {{-- Primera fila: Tipo de almacén --}}
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="filter-btn-group btn-group-sm d-flex">
                            <a href="{{ route('almacen-items.index', ['tipo' => 'todos', 'buscar' => $buscar, 'orden' => $orden]) }}" 
                               class="btn btn-outline-secondary flex-fill {{ $filtroTipo == 'todos' ? 'active' : '' }}">
                                <i class="fas fa-list mr-1"></i> Todos
                            </a>
                            <a href="{{ route('almacen-items.index', ['tipo' => 'insumo', 'buscar' => $buscar, 'orden' => $orden]) }}" 
                               class="btn btn-outline-info flex-fill {{ $filtroTipo == 'insumo' ? 'active' : '' }}">
                                <i class="fas fa-flask mr-1"></i> Insumo
                            </a>
                            <a href="{{ route('almacen-items.index', ['tipo' => 'producto', 'buscar' => $buscar, 'orden' => $orden]) }}" 
                               class="btn btn-outline-success flex-fill {{ $filtroTipo == 'producto' ? 'active' : '' }}">
                                <i class="fas fa-box mr-1"></i> Producto
                            </a>
                            <a href="{{ route('almacen-items.index', ['tipo' => 'mixto', 'buscar' => $buscar, 'orden' => $orden]) }}" 
                               class="btn btn-outline-warning flex-fill {{ $filtroTipo == 'mixto' ? 'active' : '' }}">
                                <i class="fas fa-arrows-alt-h mr-1"></i> Mixto
                            </a>
                        </div>
                    </div>
                </div>
                
                {{-- Segunda fila: Orden y búsqueda --}}
                <div class="row">
                    <div class="col-md-3 mb-2 mb-md-0">
                        <select name="orden" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="almacen" {{ $orden == 'almacen' ? 'selected' : '' }}>📦 Por Almacén</option>
                            <option value="item" {{ $orden == 'item' ? 'selected' : '' }}>📋 Por Item</option>
                            <option value="stock" {{ $orden == 'stock' ? 'selected' : '' }}>📊 Por Stock</option>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group input-group-sm">
                            <input type="hidden" name="tipo" value="{{ $filtroTipo }}">
                            <input type="text" name="buscar" class="form-control" 
                                   placeholder="Buscar almacén o item..." value="{{ $buscar }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if($buscar || $filtroTipo != 'todos' || $orden != 'almacen')
                                    <a href="{{ route('almacen-items.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Limpiar
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer filter-footer py-1">
            <small>
                <i class="fas fa-filter mr-1"></i> 
                Filtro: <strong>{{ $filtroTipo == 'todos' ? 'Todos' : ucfirst($filtroTipo) }}</strong>
                @if($buscar)
                    | Búsqueda: <strong>"{{ $buscar }}"</strong>
                @endif
                | Orden: <strong>{{ $orden == 'almacen' ? 'Almacén' : ($orden == 'item' ? 'Item' : 'Stock') }}</strong>
                | Resultados: <strong>{{ $almacenItems->total() }}</strong>
            </small>
        </div>
    </div>

    {{-- Tabla de stock --}}
    <div class="card">
        <div class="card-header card-header-dark">
            <h3 class="card-title">
                <i class="fas fa-warehouse mr-2"></i> Stock por Almacén
            </h3>
            <div class="card-tools">
                <a href="{{ route('almacen-items.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus mr-1"></i> Agregar Stock
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Almacén</th>
                            <th>Tipo</th>
                            <th>Item</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($almacenItems as $ai)
                        <tr>
                            <td>
                                <strong class="almacen-nombre">{{ $ai->almacen->nombre }}</strong>
                                @if($ai->almacen->ubicacion)
                                    <br><small class="almacen-ubicacion">{{ $ai->almacen->ubicacion }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $ai->item->tipo_item === 'producto' ? 'badge-tipo-producto' : 'badge-tipo-insumo' }}">
                                    {{ ucfirst($ai->item->tipo_item) }}
                                </span>
                            </td>
                            <td>{{ $ai->item->nombre }}</td>
                            <td>
                                <span class="badge badge-stock" style="background: var(--color-primary-dark); color: var(--text-on-primary);">
                                    {{ $ai->stock }} {{ $ai->item->unidad_medida }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('almacen-items.edit', [$ai->id_almacen, $ai->id_item]) }}" 
                                       class="btn btn-outline-primary" title="Editar stock">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('almacen-items.destroy', [$ai->id_almacen, $ai->id_item]) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Eliminar este stock?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Eliminar stock">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-warehouse empty-state-icon d-block"></i>
                                <p class="text-muted">No hay stock registrado con los filtros actuales</p>
                                <a href="{{ route('almacen-items.create') }}" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-plus mr-1"></i> Agregar primer stock
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($almacenItems->hasPages())
            <div class="card-footer">
                <div class="float-right">{{ $almacenItems->links() }}</div>
            </div>
        @endif
    </div>
</div>
@endsection