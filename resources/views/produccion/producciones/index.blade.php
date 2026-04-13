@extends('layouts.adminlte')

@section('title', 'Producciones')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0">
                <i class="fas fa-industry icon-panaderia"></i> Producciones
            </h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('producciones.create') }}" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Nueva Producción
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
            <h5 class="mb-0"><i class="fas fa-list-check"></i> Listado de Producciones</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Receta</th>
                            <th>Cantidad Producida</th>
                            <th>Empleado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($producciones as $produccion)
                            <tr>
                                <td><span class="badge badge-info">{{ $produccion->id_produccion }}</span></td>
                                <td>{{ $produccion->fecha_produccion->format('d/m/Y') }}</td>
                                <td><strong>{{ $produccion->receta->nombre ?? 'N/A' }}</strong></td>
                                <td><span class="badge badge-success">{{ $produccion->cantidad_producida }}</span></td>
                                <td>{{ $produccion->empleado->nombre ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('producciones.show', $produccion) }}" class="btn btn-info btn-xs" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('producciones.edit', $produccion) }}" class="btn btn-warning btn-xs" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('producciones.destroy', $produccion) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('¿Está seguro de que desea eliminar esta producción?')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="fas fa-inbox"></i> No hay producciones registradas
                                </td>
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
