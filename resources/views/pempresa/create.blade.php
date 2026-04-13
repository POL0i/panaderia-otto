@extends('layouts.adminlte')

@section('title', 'Crear Empresa Proveedora')
@section('page-title', 'Crear Empresa Proveedora')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulario de Empresa Proveedora</h3>
            <form action="{{ route('pempresa.store') }}" method="POST" class="form-horizontal">
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
                        <label for="razon_social">Razón Social</label>
                        <input type="text" class="form-control @error('razon_social') is-invalid @enderror" id="razon_social" name="razon_social" value="{{ old('razon_social') }}" required>
                        @error('razon_social')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('pempresa.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
@endsection
