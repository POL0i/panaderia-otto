@extends('layouts.adminlte')

@section('title', 'Notas de Compra')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-receipt icon-panaderia"></i> Notas de Compra</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('notas-compra.create') }}" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Crear Nota
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
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Notas de Compra</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Empleado</th>
                        <th class="text-right">Monto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notasCompra as $nota)
                        <tr>
                            <td><span class="badge badge-info">{{ $nota->id_nota_compra }}</span></td>
                            <td>{{ $nota->fecha_compra->format('d/m/Y') }}</td>
                            <td><i class="fas fa-truck text-primary"></i> {{ $nota->proveedor->correo ?? 'N/A' }}</td>
                            <td><i class="fas fa-user text-success"></i> {{ $nota->empleado->nombre ?? 'N/A' }}</td>
                            <td class="text-right font-weight-bold">${{ number_format($nota->monto_total, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $nota->estado === 'completada' ? 'success' : ($nota->estado === 'pendiente' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($nota->estado) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('notas-compra.show', $nota->id_nota_compra) }}" class="btn btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('notas-compra.edit', $nota->id_nota_compra) }}" class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('notas-compra.destroy', $nota->id_nota_compra) }}" method="POST" style="display:inline;">
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
                                <i class="fas fa-inbox"></i> No hay notas de compra registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($notasCompra->count() > 0)
            <div class="card-footer d-flex justify-content-center">
                {{ $notasCompra->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
