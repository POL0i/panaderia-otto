@extends('layouts.adminlte')

@section('title', 'Recetas')

@section('content')
<div class="container-fluid">
    
    {{-- Cabecera --}}
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0">
                <i class="fas fa-book icon-panaderia"></i> Recetas
            </h1>
            <small class="text-muted">Administra las recetas de tus productos</small>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('recetas.create') }}" class="btn btn-save">
                <i class="fas fa-plus"></i> Nueva Receta
            </a>
        </div>
    </div>

    {{-- Errores de validación --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h5><i class="fas fa-exclamation-circle"></i> Errores de validación</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Tabla de recetas --}}
    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Recetas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 70px">ID</th>
                            <th>Nombre</th>
                            <th style="width: 150px" class="text-center">Cantidad Requerida</th>
                            <th style="width: 100px" class="text-center">Insumos</th>
                            <th>Descripción</th>
                            <th style="width: 130px" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recetas as $receta)
                            <tr>
                                <td class="align-middle">
                                    <span class="badge badge-info">#{{ $receta->id_receta }}</span>
                                </td>
                                <td class="align-middle">
                                    <strong>{{ $receta->nombre }}</strong>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-success badge-pill px-3 py-2">
                                        {{ $receta->cantidad_requerida }} unidades
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-warning badge-pill px-3 py-2">
                                        {{ $receta->detalles->count() }} insumos
                                    </span>
                                </td>
                                <td class="align-middle">
                                    @if($receta->descripcion)
                                        {{ Str::limit($receta->descripcion, 50) }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('recetas.show', $receta) }}" 
                                           class="btn btn-info" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('recetas.edit', $receta) }}" 
                                           class="btn btn-warning" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('recetas.destroy', $receta) }}" 
                                              method="POST" 
                                              style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger" 
                                                    onclick="return confirm('¿Está seguro de que desea eliminar esta receta?')" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">No hay recetas registradas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $recetas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection