@extends('layouts.adminlte')

@section('title', 'Editar Empresa Proveedora')
@section('page-title', 'Editar Empresa Proveedora')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulario de Edición</h3>
            <form action="{{ route('pempresa.update', $pempresa->id_empresa) }}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="razon_social">Razón Social</label>
                        <input type="text" class="form-control @error('razon_social') is-invalid @enderror" id="razon_social" name="razon_social" value="{{ old('razon_social', $pempresa->razon_social) }}" required>
                        @error('razon_social')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('pempresa.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
@endsection
