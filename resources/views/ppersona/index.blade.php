@extends('layouts.adminlte')

@section('title', 'Personas Proveedores')
@section('page-title', 'Personas Proveedores')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Personas Proveedores</h3>
                <div class="card-tools">
        <a href="{{ route('ppersona.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Crear Persona
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
                            <th>Nombre</th>
                            <th>Proveedor (Correo)</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($personas as $persona)
                        <tr>
                            <td>{{ $persona->id_persona }}</td>
                            <td>{{ $persona->nombre }}</td>
                            <td>{{ $persona->proveedor->correo ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('ppersona.edit', $persona->id_persona) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('ppersona.destroy', $persona->id_persona) }}" method="POST" style="display:inline;">
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
                {{ $personas->links() }}
@endsection
