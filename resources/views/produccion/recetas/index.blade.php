{{-- resources/views/produccion/recetas/index.blade.php --}}
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

    {{-- Mensajes --}}
    @if(session('error'))
        <div class="alert alert-warning alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-exclamation-triangle"></i> {!! nl2br(e(session('error'))) !!}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Tabla de recetas --}}
    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Recetas</h5>
            <span class="badge badge-primary">{{ $recetas->total() }} recetas</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 70px">ID</th>
                            <th>Nombre</th>
                            <th style="width: 150px" class="text-center">Rinde</th>
                            <th style="width: 100px" class="text-center">Insumos</th>
                            <th>Producto</th>
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
                                    @if($receta->descripcion)
                                        <br><small class="text-muted">{{ Str::limit($receta->descripcion, 40) }}</small>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-success badge-pill px-3 py-2">
                                        {{ $receta->cantidad_requerida }} unid.
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-warning badge-pill px-3 py-2">
                                        {{ $receta->detalles->count() }} insumos
                                    </span>
                                </td>
                                <td class="align-middle">
                                    {{ $receta->producto->item->nombre ?? 'Sin producto' }}
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('produccion.recetas.detalles', $receta) }}" 
                                           class="btn btn-info" 
                                           title="Gestionar insumos">
                                            <i class="fas fa-boxes"></i>
                                        </a>
                                        <a href="{{ route('recetas.edit', $receta) }}" 
                                           class="btn btn-warning" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-danger btn-delete-receta" 
                                                data-id="{{ $receta->id_receta }}"
                                                data-nombre="{{ $receta->nombre }}"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

{{-- Modal de confirmación para eliminar --}}
<div class="modal fade" id="deleteRecetaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar la receta <strong id="recetaNombre"></strong>?</p>
                <p class="text-danger mb-0">
                    <i class="fas fa-info-circle"></i> 
                    Esta acción no se puede deshacer si la receta no tiene producciones asociadas.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <form id="deleteRecetaForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Eliminar Receta
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Manejar eliminación con modal
    $('.btn-delete-receta').on('click', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        
        $('#recetaNombre').text(nombre);
        $('#deleteRecetaForm').attr('action', '/recetas/' + id);
        $('#deleteRecetaModal').modal('show');
    });
});
</script>
@endpush