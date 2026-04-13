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
            </div> <!-- Cierre del card-header -->

            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Éxito!</strong> {{ $message }}
                    </div>
                @endif

                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo de Item</th>
                            <th>Unidad de Medida</th>
                            <th>Producto/Insumo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->id_item }}</td>
                            <td>
                                <span class="badge badge-secondary">{{ ucfirst($item->tipo_item) }}</span>
                            </td>
                            <td>{{ $item->unidad_medida }}</td>
                            <td>
                                @if ($item->tipo_item === 'producto' && $item->producto)
                                    <span class="badge badge-success">{{ $item->producto->nombre }}</span>
                                @elseif ($item->tipo_item === 'insumo' && $item->insumo)
                                    <span class="badge badge-info">{{ $item->insumo->nombre }}</span>
                                @else
                                    <span class="badge badge-warning">Sin relación</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('items.show', $item->id_item) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('items.edit', $item->id_item) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('items.destroy', $item->id_item) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    No hay items registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> <!-- Cierre del card-body -->
        </div> <!-- Cierre del card -->
    </div>
</div>
@endsection