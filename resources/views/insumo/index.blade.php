@extends('layouts.adminlte')

@section('title', 'Insumos')
@section('page-title', 'Insumos')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Insumos</h3>
                <div class="card-tools">
        <a href="{{ route('insumos.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Crear Insumo
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
                            <th>Precio Compra</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($insumos as $insumo)
                        <tr>
                            <td>{{ $insumo->id_insumo }}</td>
                            <td>{{ $insumo->nombre }}</td>
                            <td><span class="badge badge-primary">{{ $insumo->categoria->nombre ?? 'N/A' }}</span></td>
                            <td>{{ $insumo->item->id_item ?? 'N/A' }}</td>
                            <td>${{ number_format($insumo->precio_compra, 2) }}</td>
                            <td>
                                <a href="{{ route('insumos.show', $insumo->id_insumo) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('insumos.edit', $insumo->id_insumo) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('insumos.destroy', $insumo->id_insumo) }}" method="POST" style="display:inline;">
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
                {{ $insumos->links() }}
@endsection
