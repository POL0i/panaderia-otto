@extends('layouts.adminlte')

@section('title', 'Usuarios')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-users icon-panaderia"></i> Usuarios</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('usuarios.create') }}" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Crear Usuario
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> {{ $message }}
        </div>
    @endif

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Usuarios</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Correo</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td><span class="badge badge-info">{{ $usuario->id_usuario }}</span></td>
                            <td><i class="fas fa-envelope text-primary"></i> {{ $usuario->correo }}</td>
                            <td>
                                <span class="badge badge-{{ $usuario->tipo_usuario === 'empleado' ? 'success' : 'warning' }}">
                                    {{ ucfirst($usuario->tipo_usuario) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $usuario->estado === 'activo' ? 'success' : 'danger' }}">
                                    <i class="fas fa-{{ $usuario->estado === 'activo' ? 'check-circle' : 'times-circle' }}"></i>
                                    {{ ucfirst($usuario->estado) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('usuarios.edit', $usuario->id_usuario) }}" class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('usuarios.destroy', $usuario->id_usuario) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fas fa-inbox"></i> No hay usuarios registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection