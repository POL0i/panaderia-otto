@extends('layouts.adminlte')

@section('title', 'Lotes de Inventario')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-boxes icon-panaderia"></i> Lotes de Inventario</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('lotes.create') }}" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Nuevo Lote
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

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list-check"></i> Listado de Lotes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Almacén</th>
                        <th>Item</th>
                        <th>Cantidad Inicial</th>
                        <th>Cantidad Disponible</th>
                        <th>Precio Unitario</th>
                        <th>Valor Disponible</th>
                        <th>Método</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lotes as $lote)
                        <tr>
                            <td><span class="badge badge-info">{{ $lote->id_lote }}</span></td>
                            <td>{{ $lote->almacen->nombre ?? 'N/A' }}</td>
                            <td>{{ $lote->item->nombre ?? 'N/A' }}</td>
                            <td>{{ $lote->cantidad_inicial }}</td>
                            <td>{{ $lote->cantidad_disponible }}</td>
                            <td>${{ number_format($lote->precio_unitario, 2) }}</td>
                            <td>${{ number_format($lote->valor_disponible, 2) }}</td>
                            <td><span class="badge badge-info">{{ $lote->metodo_valuacion }}</span></td>
                            <td>
                                <span class="badge badge-{{ $lote->estado == 'disponible' ? 'success' : ($lote->estado == 'consumido' ? 'danger' : 'info') }}">
                                    {{ ucfirst($lote->estado) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('lotes.show', $lote) }}" class="btn btn-info btn-xs">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('lotes.edit', $lote) }}" class="btn btn-warning btn-xs">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('lotes.destroy', $lote) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('\u00bfEst\u00e1 seguro?')" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">No hay lotes registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="pagination justify-content-center">
                {{ $lotes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
