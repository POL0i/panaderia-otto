@extends('layouts.adminlte')

@section('title', 'Empresas Proveedoras')
@section('page-title', 'Empresas Proveedoras')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Empresas Proveedoras</h3>
                <div class="card-tools">
        <a href="{{ route('pempresa.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Crear Empresa
                    </a>
            <div class="card-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Éxito!</strong> {{ $message }}
                @endif
    <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Razón Social</th>
                            <th>Proveedor (Correo)</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($empresas as $empresa)
                        <tr>
                            <td>{{ $empresa->id_empresa }}</td>
                            <td>{{ $empresa->razon_social }}</td>
                            <td>{{ $empresa->proveedor->correo ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('pempresa.edit', $empresa->id_empresa) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('pempresa.destroy', $empresa->id_empresa) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            <div class="card-footer">
                {{ $empresas->links() }}
@endsection
