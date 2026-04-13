@extends('layouts.adminlte')

@section('title', 'Crear Insumo')
@section('page-title', 'Crear Insumo')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulario de Insumo</h3>
            <form action="{{ route('insumos.store') }}" method="POST" class="form-horizontal">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="id_item">Item</label>
                        <select class="form-control @error('id_item') is-invalid @enderror" id="id_item" name="id_item" required>
                            <option value="">Seleccione un item</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id_item }}" {{ old('id_item') == $item->id_item ? 'selected' : '' }}>
                                    Item #{{ $item->id_item }} ({{ $item->unidad_medida }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_item')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    <div class="form-group">
                        <label for="id_cat_insumo">Categoría</label>
                        <select class="form-control @error('id_cat_insumo') is-invalid @enderror" id="id_cat_insumo" name="id_cat_insumo" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id_cat_insumo }}" {{ old('id_cat_insumo') == $categoria->id_cat_insumo ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_cat_insumo')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    <div class="form-group">
                        <label for="precio_compra">Precio de Compra</label>
                        <input type="number" step="0.01" class="form-control @error('precio_compra') is-invalid @enderror" id="precio_compra" name="precio_compra" value="{{ old('precio_compra') }}" required>
                        @error('precio_compra')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('insumos.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
@endsection
