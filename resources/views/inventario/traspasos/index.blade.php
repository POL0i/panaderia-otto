@extends('layouts.adminlte')

@section('title', 'Traspasos de Inventario')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-exchange-alt"></i> Traspasos de Inventario</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('traspasos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Traspaso
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Traspasos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Empleado</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Items</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($traspasos as $traspaso)
                            @php 
                                $primer = $traspaso->detalles->first();
                            @endphp
                            <tr>
                                <td><span class="badge badge-info">#{{ $traspaso->id_traspaso }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($traspaso->fecha_traspaso)->format('d/m/Y H:i') }}</td>
                                <td>{{ $traspaso->empleado->nombre ?? 'N/A' }}</td>
                                <td>{{ $primer->almacenOrigen()->nombre ?? 'N/A' }}</td>
                                <td>{{ $primer->almacenDestino()->nombre ?? 'N/A' }}</td>
                                <td><span class="badge badge-secondary">{{ $traspaso->detalles->count() }} items</span></td>
                                <td>{{ Str::limit($traspaso->descripcion, 30) ?: '-' }}</td>
                                <td>
                                    <a href="{{ route('traspasos.show', $traspaso) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No hay traspasos registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $traspasos->links() }}
        </div>
    </div>
</div>
@endsection