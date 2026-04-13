@extends('layouts.adminlte')

@section('title', 'Editar Insumo')
@section('page-title', 'Editar Insumo')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulario de Edición</h3>
            <form action="{{ route('insumos.update', $insumo->id_insumo) }}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="id_item">Item</label>
                        <select class="form-control @error('id_item') is-invalid @enderror" id="id_item" name="id_item" required disabled>
                            @foreach ($items as $item)
                                <option value="{{ $item->id_item }}" {{ old('id_item', $insumo->id_item) == $item->id_item ? 'selected' : '' }}>
                                    Item #{{ $item->id_item }} ({{ $item->unidad_medida }})
                                </option>
                            @endforeach
                        </select>
                    <div class="form-group">
                        <label for="id_cat_insumo">Categoría</label>
                        <select class="form-control @error('id_cat_insumo') is-invalid @enderror" id="id_cat_insumo" name="id_cat_insumo" required>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id_cat_insumo }}" {{ old('id_cat_insumo', $insumo->id_cat_insumo) == $categoria->id_cat_insumo ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_cat_insumo')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $insumo->nombre) }}" required>
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    <div class="form-group">
                        <label for="precio_compra">Precio de Compra</label>
                        <input type="number" step="0.01" class="form-control @error('precio_compra') is-invalid @enderror" id="precio_compra" name="precio_compra" value="{{ old('precio_compra', $insumo->precio_compra) }}" required>
                        @error('precio_compra')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('insumos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
@endsection
