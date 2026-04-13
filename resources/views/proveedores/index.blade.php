@extends('layouts.adminlte')

@section('title', 'Proveedores')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-truck icon-panaderia"></i> Proveedores</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('proveedores.create') }}" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Crear Proveedor
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
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Proveedores</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre/Empresa</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Tipo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($proveedores as $proveedor)
                        <tr>
                            <td><span class="badge badge-info">{{ $proveedor->id_proveedor }}</span></td>
                            <td>
                                @if ($proveedor->tipo_proveedor === 'persona' && $proveedor->persona)
                                    <i class="fas fa-user text-primary"></i> {{ $proveedor->persona->nombre }}
                                @elseif ($proveedor->tipo_proveedor === 'empresa' && $proveedor->empresa)
                                    <i class="fas fa-building text-success"></i> {{ $proveedor->empresa->razon_social }}
                                @else
                                    <em class="text-muted">N/A</em>
                                @endif
                            </td>
                            <td>{{ $proveedor->correo }}</td>
                            <td>{{ $proveedor->telefono }}</td>
                            <td>{{ $proveedor->direccion }}</td>
                            <td>
                                <span class="badge badge-{{ $proveedor->tipo_proveedor === 'persona' ? 'info' : 'success' }}">
                                    {{ ucfirst($proveedor->tipo_proveedor) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('proveedores.show', $proveedor->id_proveedor) }}" class="btn btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('proveedores.edit', $proveedor->id_proveedor) }}" class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('proveedores.destroy', $proveedor->id_proveedor) }}" method="POST" style="display:inline;">
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
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox"></i> No hay proveedores registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($proveedores->count() > 0)
            <div class="card-footer d-flex justify-content-center">
                {{ $proveedores->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
