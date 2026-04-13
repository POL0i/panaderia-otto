@extends('layouts.adminlte')

@section('title', 'Producción Items Almacén')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-link icon-panaderia"></i> Producción Items Almacén</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('produccion-items.create') }}" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Nuevo Registro
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
            <h5 class="mb-0"><i class="fas fa-list-check"></i> Listado de Asignaciones</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producción</th>
                            <th>Almacén</th>
                            <th>Item/Producto</th>
                            <th>Cantidad Producida</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($producciones as $produccion)
                            <tr>
                                <td>{{ $produccion->id_produccion_item_almacen }}</td>
                                <td>#{{ $produccion->id_produccion }}</td>
                                <td>{{ $produccion->almacen->nombre ?? 'N/A' }}</td>
                                <td>{{ $produccion->item->nombre ?? 'N/A' }}</td>
                                <td>{{ $produccion->cantidad_producida }}</td>
                                <td>
                                    <a href="{{ route('produccion-items.show', $produccion) }}" class="btn btn-info btn-xs" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('produccion-items.edit', $produccion) }}" class="btn btn-warning btn-xs" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('produccion-items.destroy', $produccion) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('¿Está seguro?')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay asignaciones registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="pagination justify-content-center">
                {{ $producciones->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
