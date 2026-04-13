@extends('layouts.adminlte')

@section('title', 'Traspasos de Inventario')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-exchange-alt icon-panaderia"></i> Traspasos de Inventario</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('traspasos.create') }}" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Nuevo Traspaso
            </a>
        </div>
    </div>
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h5><i class="fas fa-exclamation-circle"></i> Errores de validación</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-times-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list-check"></i> Listado de Traspasos</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                        <th>Almacén Origen</th>
                        <th>Almacén Destino</th>
                        <th>Item</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($traspasos as $traspaso)
                        <tr>
                            <td><span class="badge badge-info">{{ $traspaso->id_traspaso }}</span></td>
                            <td>{{ $traspaso->almacenOrigen->nombre ?? 'N/A' }}</td>
                            <td>{{ $traspaso->almacenDestino->nombre ?? 'N/A' }}</td>
                            <td>{{ $traspaso->item->nombre ?? 'N/A' }}</td>
                            <td>{{ $traspaso->cantidad }}</td>
                            <td>${{ number_format($traspaso->precio_unitario, 2) }}</td>
                            <td>{{ $traspaso->fecha_traspaso->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $traspaso->estado == 'completado' ? 'success' : ($traspaso->estado == 'pendiente' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($traspaso->estado) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('traspasos.show', $traspaso) }}" class="btn btn-info btn-xs">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($traspaso->estado === 'pendiente')
                                    <form action="{{ route('traspasos.completar', $traspaso) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-xs" title="Completar">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('traspasos.cancelar', $traspaso) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-warning btn-xs" title="Cancelar">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('traspasos.edit', $traspaso) }}" class="btn btn-warning btn-xs">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('traspasos.destroy', $traspaso) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('¿Está seguro?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No hay traspasos registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="pagination justify-content-center">
                {{ $traspasos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
