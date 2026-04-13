@extends('layouts.adminlte')

@section('title', 'Crear Item')
@section('page-title', 'Crear Item')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulario de Item</h3>
            <form action="{{ route('items.store') }}" method="POST" class="form-horizontal">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="tipo_item">Tipo de Item</label>
                        <select class="form-control @error('tipo_item') is-invalid @enderror" id="tipo_item" name="tipo_item" required>
                            <option value="">Seleccione tipo</option>
                            <option value="producto" {{ old('tipo_item') === 'producto' ? 'selected' : '' }}>Producto</option>
                            <option value="insumo" {{ old('tipo_item') === 'insumo' ? 'selected' : '' }}>Insumo</option>
                        </select>
                        @error('tipo_item')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    <div class="form-group">
                        <label for="unidad_medida">Unidad de Medida</label>
                        <input type="text" class="form-control @error('unidad_medida') is-invalid @enderror" id="unidad_medida" name="unidad_medida" value="{{ old('unidad_medida') }}" required>
                        @error('unidad_medida')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('items.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
@endsection
