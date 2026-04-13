@extends('layouts.adminlte')

@section('title', 'Empleados')
@section('page-title', 'Gestión de Empleados')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card animate-fade-in-up">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title">
                        <i class="fas fa-user-tie mr-2"></i>
                        Lista de Empleados
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('empleados.create') }}" class="btn btn-save btn-sm">
                            <i class="fas fa-plus mr-1"></i>
                            Crear Empleado
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 5%">
                                        <i class="fas fa-hashtag"></i>
                                    </th>
                                    <th>Nombre Completo</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    <th>Sueldo</th>
                                    <th style="width: 15%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($empleados ?? [] as $empleado)
                                    <tr class="animate-slide-in-right">
                                        <td>{{ $empleado->id_empleado }}</td>
                                        <td>
                                            <i class="fas fa-user-circle mr-2 text-primary"></i>
                                            {{ $empleado->nombre ?? 'N/A' }} {{ $empleado->apellido ?? '' }}
                                        </td>
                                        <td>{{ $empleado->telefono ?? '—' }}</td>
                                        <td>{{ $empleado->direccion ?? '—' }}</td>
                                        <td><span class="badge badge-info">{{ $empleado->sueldo ? '$' . number_format($empleado->sueldo, 2) : 'N/A' }}</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('empleados.edit', $empleado->id_empleado) }}" class="btn btn-outline-primary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('empleados.destroy', $empleado->id_empleado) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de que desea eliminar este empleado?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-inbox text-muted mr-2"></i>
                                            No hay empleados registrados
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Total: <strong>{{ count($empleados ?? []) }}</strong> empleados
                    </small>
                    <a href="{{ route('home') }}" class="btn btn-back btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
