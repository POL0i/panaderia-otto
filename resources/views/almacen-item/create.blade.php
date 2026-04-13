@extends('layouts.adminlte')

@section('title', 'Agregar Stock')
@section('page-title', 'Agregar Stock a Almacén')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulario de Stock</h3>
            <form action="{{ route('almacen-items.store') }}" method="POST" class="form-horizontal">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="id_almacen">Almacén</label>
                        <select class="form-control @error('id_almacen') is-invalid @enderror" id="id_almacen" name="id_almacen" required>
                            <option value="">Seleccione un almacén</option>
                            @foreach ($almacenes as $almacen)
                                <option value="{{ $almacen->id_almacen }}" {{ old('id_almacen') == $almacen->id_almacen ? 'selected' : '' }}>
                                    {{ $almacen->nombre,}}
                                </option>
                            @endforeach
                        </select>
                        @error('id_almacen')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    <div class="form-group">
                        <label for="id_item">Item</label>
                        <select class="form-control @error('id_item') is-invalid @enderror" id="id_item" name="id_item" required>
                            <option value="">Seleccione un item</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id_item }}" {{ old('id_item') == $item->id_item ? 'selected' : '' }}>
                                    Item #{{ $item->id_item }} - {{ $item->tipo_item }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_item')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" step="0.01" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}" required>
                        @error('stock')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('almacen-items.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
@endsection
