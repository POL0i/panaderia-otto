@extends('layouts.adminlte')

@section('title', 'Inventario (Stock)')
@section('page-title', 'Inventario - Stock por Almacén/Item')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Stock en Almacenes</h3>
                <div class="card-tools">
        <a href="{{ route('almacen-items.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Agregar Stock
                    </a>
            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Éxito!</strong> {{ $message }}
                @endif
    <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Almacén</th>
                            <th>Item</th>
                            <th>Tipo</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($almacenItems as $almacenItem)
                        <tr>
                            <td>{{ $almacenItem->almacen->nombre }}</td>
                            <td>#{{ $almacenItem->item->id_item }}</td>
                            <td>
                                <span class="badge badge-{{ $almacenItem->item->tipo_item === 'producto' ? 'success' : 'info' }}">
                                    {{ ucfirst($almacenItem->item->tipo_item) }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $almacenItem->stock }}</strong> {{ $almacenItem->item->unidad_medida }}
                            </td>
                            <td>
                                <a href="{{ route('almacen-items.edit', [$almacenItem->id_almacen, $almacenItem->id_item]) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('almacen-items.destroy', [$almacenItem->id_almacen, $almacenItem->id_item]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            <div class="card-footer">
                {{ $almacenItems->links() }}
@endsection
