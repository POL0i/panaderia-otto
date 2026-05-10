@extends('layouts.adminlte')

@section('title', 'Inventario Unificado')
@section('page-title', 'Gestión de Inventario')

@section('content')
<div class="container-fluid">
    
    {{-- Estadísticas estilo stat-card (igual que personas) --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number">{{ $totalItems }}</div>
                <div class="stat-label">
                    <i class="fas fa-boxes mr-2"></i>Total Items
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="border-left-color: #28a745;">
                <div class="stat-number">{{ $totalProductos }}</div>
                <div class="stat-label">
                    <i class="fas fa-box mr-2"></i>Productos
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="border-left-color: #17a2b8;">
                <div class="stat-number">{{ $totalInsumos }}</div>
                <div class="stat-label">
                    <i class="fas fa-flask mr-2"></i>Insumos
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros y búsqueda --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('items.index') }}" id="filtrosForm">
                <div class="row align-items-center">
                    {{-- Filtros de tipo --}}
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="btn-group btn-group-sm w-100">
                            <a href="{{ route('items.index', ['filtro' => 'todos', 'buscar' => $buscar, 'categoria' => $categoria]) }}" 
                               class="btn btn-outline-secondary flex-fill {{ $filtro == 'todos' ? 'active' : '' }}">
                                <i class="fas fa-list mr-1"></i> Todos
                            </a>
                            <a href="{{ route('items.index', ['filtro' => 'productos', 'buscar' => $buscar, 'categoria' => $categoria]) }}" 
                               class="btn btn-outline-success flex-fill {{ $filtro == 'productos' ? 'active' : '' }}">
                                <i class="fas fa-box mr-1"></i> Productos
                            </a>
                            <a href="{{ route('items.index', ['filtro' => 'insumos', 'buscar' => $buscar, 'categoria' => $categoria]) }}" 
                               class="btn btn-outline-info flex-fill {{ $filtro == 'insumos' ? 'active' : '' }}">
                                <i class="fas fa-flask mr-1"></i> Insumos
                            </a>
                        </div>
                    </div>
                    
                    {{-- Categoría --}}
                    <div class="col-md-3 mb-2 mb-md-0">
                        <select name="categoria" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">Todas las categorías</option>
                            @if($filtro == 'productos' || $filtro == 'todos')
                                <optgroup label="── Productos ──">
                                    @foreach($categoriasProductos as $cat)
                                        <option value="{{ $cat->id_cat_producto }}" {{ $categoria == $cat->id_cat_producto ? 'selected' : '' }}>
                                            {{ $cat->nombre }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                            @if($filtro == 'insumos' || $filtro == 'todos')
                                <optgroup label="── Insumos ──">
                                    @foreach($categoriasInsumos as $cat)
                                        <option value="{{ $cat->id_cat_insumo }}" {{ $categoria == $cat->id_cat_insumo ? 'selected' : '' }}>
                                            {{ $cat->nombre }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div>
                    
                    {{-- Búsqueda --}}
                    <div class="col-md-5">
                        <div class="input-group input-group-sm">
                            <input type="hidden" name="filtro" value="{{ $filtro }}">
                            <input type="text" name="buscar" class="form-control" 
                                   placeholder="Buscar por nombre..." value="{{ $buscar }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if($buscar || $categoria)
                                    <a href="{{ route('items.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card">
        <div class="card-header bg-gradient-dark">
            <h3 class="card-title text-white">
                <i class="fas fa-boxes mr-2"></i>
                Listado de Items ({{ $items->total() }})
            </h3>
            <div class="card-tools">
                <a href="{{ route('items.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus mr-1"></i> Nuevo Item
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th style="width: 8%">Imagen</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Categoría</th>
                            <th>Unidad</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->id_item }}</td>
                                <td class="text-center">
                                    @if($item->tipo_item === 'producto' && $item->producto && $item->producto->imagen)
                                        @php
                                            $imagen = $item->producto->imagen;
                                            $esUrl = Str::startsWith($imagen, ['http://', 'https://']);
                                            $src = $esUrl ? $imagen : asset('storage/' . $imagen);
                                        @endphp
                                        <img src="{{ $src }}" class="img-thumbnail" 
                                             style="width: 45px; height: 45px; object-fit: cover;"
                                             onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-box text-muted\'></i>'">
                                    @elseif($item->tipo_item === 'producto')
                                        <i class="fas fa-box text-muted fa-lg"></i>
                                    @else
                                        <i class="fas fa-flask text-info fa-lg"></i>
                                    @endif
                                </td>
                                <td><strong>{{ $item->nombre }}</strong></td>
                                <td>
                                    @if($item->tipo_item === 'producto')
                                        <span class="badge badge-success">Producto</span>
                                    @else
                                        <span class="badge badge-info">Insumo</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->tipo_item === 'producto' && $item->producto && $item->producto->categoria)
                                        {{ $item->producto->categoria->nombre }}
                                    @elseif($item->tipo_item === 'insumo' && $item->insumo && $item->insumo->categoria)
                                        {{ $item->insumo->categoria->nombre }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-secondary">{{ $item->unidad_medida }}</span></td>
                                <td>
                                    @if($item->tipo_item === 'producto' && $item->producto)
                                        <strong class="text-success">${{ number_format($item->producto->precio, 2) }}</strong>
                                    @elseif($item->insumo && $item->insumo->precio_compra)
                                        <small>${{ number_format($item->insumo->precio_compra, 2) }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('items.show', $item->id_item) }}" class="btn btn-outline-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('items.edit', $item->id_item) }}" class="btn btn-outline-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('items.destroy', $item->id_item) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Eliminar este item?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted">No se encontraron items</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($items->hasPages())
            <div class="card-footer clearfix">
                <div class="float-right">{{ $items->links() }}</div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Estadísticas estilo stat-card (igual que personas) */
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgb(85, 70, 4);
        border-left: 4px solid #602c07; /* gris por defecto para Total */
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #673a07;
        line-height: 1.2;
    }
    
    .stat-label {
        color: #522b0b;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 0.25rem;
    }
    
    .img-thumbnail {
        padding: 2px;
        border-radius: 4px;
    }
    
    .btn-group-sm .btn {
        margin-right: 2px;
    }
</style>
@endpush