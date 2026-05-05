@extends('layouts.adminlte')

@section('title', 'Lotes de Inventario')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-boxes"></i> Lotes de Inventario</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('lotes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Lote
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Lotes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Almacén</th>
                            <th>Item</th>
                            <th>Cant. Inicial</th>
                            <th>Cant. Disponible</th>
                            <th>Precio Unit.</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lotes as $lote)
                            <tr>
                                <td>#{{ $lote->id_lote }}</td>
<td>{{ $lote->almacen_nombre }}</td>
<td>{{ $lote->item_nombre }}</td>
                                <td>{{ $lote->cantidad_inicial }}</td>
                                <td>{{ $lote->cantidad_disponible }}</td>
                                <td>Bs. {{ number_format($lote->precio_unitario, 2) }}</td>
                                <td>{{ $lote->metodo_valuacion ?? 'PEPS' }}</td>
                                <td>
                                    @if($lote->estado == 'disponible')
                                        <span class="badge badge-success">Disponible</span>
                                    @else
                                        <span class="badge badge-danger">{{ ucfirst($lote->estado) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('lotes.show', $lote) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No hay lotes registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $lotes->links() }}
        </div>
    </div>
</div>
@endsection