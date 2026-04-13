@extends('layouts.adminlte')

@section('title', 'Editar Stock')
@section('page-title', 'Editar Stock en Almacén')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulario de Edición</h3>
            <form action="{{ route('almacen-items.update', [$almacenItem->id_almacen, $almacenItem->id_item]) }}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="id_almacen">Almacén</label>
                        <select class="form-control" id="id_almacen" name="id_almacen" required disabled>
                            @foreach ($almacenes as $almacen)
                                <option value="{{ $almacen->id_almacen }}" {{ old('id_almacen', $almacenItem->id_almacen) == $almacen->id_almacen ? 'selected' : '' }}>
                                    {{ $almacen->nombre }}
                                </option>
                            @endforeach
                        </select>
                    <div class="form-group">
                        <label for="id_item">Item</label>
                        <select class="form-control" id="id_item" name="id_item" required disabled>
                            @foreach ($items as $item)
                                <option value="{{ $item->id_item }}" {{ old('id_item', $almacenItem->id_item) == $item->id_item ? 'selected' : '' }}>
                                    Item #{{ $item->id_item }} - {{ $item->tipo_item }}
                                </option>
                            @endforeach
                        </select>
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" step="0.01" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $almacenItem->stock) }}" required>
                        @error('stock')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('almacen-items.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
@endsection
