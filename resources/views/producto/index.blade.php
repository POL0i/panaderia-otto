@extends('layouts.adminlte')

@section('title', 'Productos')
@section('page-title', 'Productos')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Productos</h3>
                <div class="card-tools">
        <a href="{{ route('productos.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Crear Producto
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
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Item</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                        <tr>
                            <td>{{ $producto->id_producto }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td><span class="badge badge-primary">{{ $producto->categoria->nombre ?? 'N/A' }}</span></td>
                            <td>{{ $producto->item->id_item ?? 'N/A' }}</td>
                            <td>${{ number_format($producto->precio, 2) }}</td>
                            <td>
                                <a href="{{ route('productos.show', $producto->id_producto) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('productos.edit', $producto->id_producto) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('productos.destroy', $producto->id_producto) }}" method="POST" style="display:inline;">
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
                {{ $productos->links() }}
@endsection
