@extends('layouts.adminlte')

@section('title', 'Clientes')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-users icon-panaderia"></i> Clientes</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('clientes.create') }}" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Crear Cliente
            </a>
        </div>
    </div>

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list-check"></i> Listado de Clientes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes ?? [] as $cliente)
                        <tr>
                            <td><span class="badge badge-info">{{ $cliente->id_cliente }}</span></td>
                            <td>{{ $cliente->nombre ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="btn btn-warning btn-xs" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('clientes.destroy', $cliente->id_cliente) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('¿Está seguro?')" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No hay clientes registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
@endsection
