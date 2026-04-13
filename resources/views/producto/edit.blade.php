@extends('layouts.adminlte')

@section('title', 'Editar Producto')
@section('page-title', 'Editar Producto')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulario de Edición</h3>
            <form action="{{ route('productos.update', $producto->id_producto) }}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="id_item">Item</label>
                        <select class="form-control @error('id_item') is-invalid @enderror" id="id_item" name="id_item" required disabled>
                            @foreach ($items as $item)
                                <option value="{{ $item->id_item }}" {{ old('id_item', $producto->id_item) == $item->id_item ? 'selected' : '' }}>
                                    Item #{{ $item->id_item }} ({{ $item->tipo_item }})
                                </option>
                            @endforeach
                        </select>
                    <div class="form-group">
                        <label for="id_cat_producto">Categoría</label>
                        <select class="form-control @error('id_cat_producto') is-invalid @enderror" id="id_cat_producto" name="id_cat_producto" required>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id_cat_producto }}" {{ old('id_cat_producto', $producto->id_cat_producto) == $categoria->id_cat_producto ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_cat_producto')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio', $producto->precio) }}" required>
                        @error('precio')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
@endsection
