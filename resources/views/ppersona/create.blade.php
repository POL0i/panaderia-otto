@extends('layouts.adminlte')

@section('title', 'Crear Persona Proveedor')
@section('page-title', 'Crear Persona Proveedor')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulario de Persona Proveedor</h3>
            <form action="{{ route('ppersona.store') }}" method="POST" class="form-horizontal">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="id_proveedor">Proveedor</label>
                        <select class="form-control @error('id_proveedor') is-invalid @enderror" id="id_proveedor" name="id_proveedor" required>
                            <option value="">Seleccione un proveedor</option>
                            @foreach ($proveedores as $proveedor)
                                <option value="{{ $proveedor->id_proveedor }}" {{ old('id_proveedor') == $proveedor->id_proveedor ? 'selected' : '' }}>
                                    {{ $proveedor->correo }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_proveedor')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('ppersona.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
@endsection
