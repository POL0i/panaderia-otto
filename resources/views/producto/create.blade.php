@extends('layouts.adminlte')

@section('title', 'Crear Producto')
@section('page-title', 'Crear Producto')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulario de Producto</h3>
            <form action="{{ route('productos.store') }}" method="POST" class="form-horizontal">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="id_item">Item</label>
                        <select class="form-control @error('id_item') is-invalid @enderror" id="id_item" name="id_item" required>
                            <option value="">Seleccione un item</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id_item }}" {{ old('id_item') == $item->id_item ? 'selected' : '' }}>
                                    Item #{{ $item->id_item }} ({{ $item->tipo_item }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_item')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    <div class="form-group">
                        <label for="id_cat_producto">Categoría</label>
                        <select class="form-control @error('id_cat_producto') is-invalid @enderror" id="id_cat_producto" name="id_cat_producto" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id_cat_producto }}" {{ old('id_cat_producto') == $categoria->id_cat_producto ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_cat_producto')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio') }}" required>
                        @error('precio')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
@endsection
