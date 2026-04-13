@extends('layouts.adminlte')

@section('title', 'Almacenes')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-warehouse icon-panaderia"></i> Almacenes</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('almacenes.create') }}" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Crear Almacén
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> {{ $message }}
        </div>
    @endif

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list-check"></i> Listado de Almacenes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Ubicación</th>
                        <th>Capacidad</th>
                        <th>Items Almacenados</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                    <tbody>
                        @foreach ($almacenes as $almacen)
                        <tr>
                            <td><span class="badge badge-info">{{ $almacen->id_almacen }}</span></td>
                            <td>{{ $almacen->nombre }}</td>
                            <td>{{ $almacen->ubicacion }}</td>
                            <td>{{ $almacen->capacidad }}</td>
                            <td><span class="badge badge-info">{{ $almacen->items->count() }}</span></td>
                            <td>
                                <a href="{{ route('almacenes.show', $almacen->id_almacen) }}" class="btn btn-info btn-xs" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('almacenes.edit', $almacen->id_almacen) }}" class="btn btn-warning btn-xs" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('almacenes.destroy', $almacen->id_almacen) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('¿Está seguro?')" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
            </table>
            </div>
        </div>
        <div class="card-footer">
            <div class="pagination justify-content-center">
                {{ $almacenes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
