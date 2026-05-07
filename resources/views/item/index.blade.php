@extends('layouts.adminlte')

@section('title', 'Items')
@section('page-title', 'Items')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Items</h3>
                <div class="card-tools">
                    <a href="{{ route('items.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Crear Item
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>¡Éxito!</strong> {{ $message }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="35%">Nombre</th>
                                <th width="15%">Tipo</th>
                                <th width="15%">Unidad de Medida</th>
                                <th width="30%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                            <tr>
                                <td>{{ $item->id_item }}</td>
                                <td>
                                    <strong>{{ $item->nombre }}</strong>
                                    
                                </td>
                                <td>
                                    @if($item->tipo_item === 'producto')
                                        <span class="badge badge-success">
                                            <i class="fas fa-box"></i> Producto
                                        </span>
                                    @else
                                        <span class="badge badge-info">
                                            <i class="fas fa-flask"></i> Insumo
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ $item->unidad_medida }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('items.edit', $item->id_item) }}" class="btn btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('items.destroy', $item->id_item) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este item?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                                        No hay items registrados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    <table>
                </div>

                @if($items->hasPages())
                    <div class="card-footer clearfix">
                        <div class="float-right">
                            {{ $items->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
