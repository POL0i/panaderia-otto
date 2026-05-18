@extends('layouts.adminlte')

@section('title', 'Recetas y Detalles')
@section('page-title', 'Gestión de Recetas')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border-left: 4px solid #6c757d;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #343a40;
        line-height: 1.2;
    }
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .detalle-row {
        transition: all 0.3s ease;
    }
    .badge-insumo {
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    {{-- Estadísticas --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number">{{ $recetas->total() }}</div>
                <div class="stat-label"><i class="fas fa-book mr-2"></i>Total Recetas</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="border-left-color: #28a745;">
                <div class="stat-number">{{ $recetas->sum('detalles_count') }}</div>
                <div class="stat-label"><i class="fas fa-flask mr-2"></i>Total Insumos</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="border-left-color: #17a2b8;">
                <div class="stat-number">{{ $recetas->count() }}</div>
                <div class="stat-label"><i class="fas fa-list mr-2"></i>En esta página</div>
            </div>
        </div>
    </div>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- Tabla principal de recetas --}}
    <div class="card">
        <div class="card-header bg-gradient-dark">
            <h3 class="card-title text-white">
                <i class="fas fa-book mr-2"></i> Listado de Recetas
            </h3>
            <div class="card-tools">
                <a href="{{ route('recetas.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus mr-1"></i> Nueva Receta
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th>Nombre</th>
                            <th>Producto</th>
                            <th class="text-center">Rinde</th>
                            <th class="text-center">Insumos</th>
                            <th>Descripción</th>
                            <th class="text-center" style="width: 15%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recetas as $receta)
                            <tr>
                                <td><span class="badge badge-info">#{{ $receta->id_receta }}</span></td>
                                <td><strong>{{ $receta->nombre }}</strong></td>
                                <td>
                                    @if($receta->producto && $receta->producto->item)
                                        <span class="badge badge-success">
                                            {{ $receta->producto->item->nombre }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">Sin producto</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-primary px-3 py-2">
                                        {{ $receta->cantidad_requerida }} 
                                        {{ $receta->producto->item->unidad_medida ?? 'unidad' }}(s)
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning btn-toggle-detalles" 
                                            data-receta-id="{{ $receta->id_receta }}"
                                            title="Ver/ocultar insumos">
                                        <span class="badge badge-warning">{{ $receta->detalles_count }}</span>
                                    </button>
                                </td>
                                <td>
                                    @if($receta->descripcion)
                                        <small class="text-muted">{{ Str::limit($receta->descripcion, 80) }}</small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-info btn-toggle-detalles" 
                                                data-receta-id="{{ $receta->id_receta }}"
                                                title="Ver insumos">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('recetas.edit', $receta) }}" class="btn btn-outline-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('recetas.destroy', $receta) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('¿Eliminar esta receta y todos sus detalles?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            {{-- Fila de detalles (oculta por defecto) --}}
                            <tr class="detalles-row bg-light collapse" id="detalles-receta-{{ $receta->id_receta }}">
                                <td colspan="7" class="p-0">
                                    <div class="p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">
                                                <i class="fas fa-flask mr-2"></i>
                                                Insumos de <strong>{{ $receta->nombre }}</strong>
                                                <span class="badge badge-primary ml-2">
                                                    Rinde: {{ $receta->cantidad_requerida }} 
                                                    {{ $receta->producto->item->unidad_medida ?? 'unidad' }}(s)
                                                </span>
                                            </h6>
                                            <button class="btn btn-success btn-xs btn-agregar-insumo" 
                                                    data-receta-id="{{ $receta->id_receta }}"
                                                    data-receta-nombre="{{ $receta->nombre }}">
                                                <i class="fas fa-plus mr-1"></i> Agregar Insumo
                                            </button>
                                        </div>
                                        <div class="detalles-content" id="detalles-content-{{ $receta->id_receta }}">
                                            <div class="text-center py-3 text-muted">
                                                <i class="fas fa-spinner fa-spin"></i> Cargando insumos...
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-book fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted">No hay recetas registradas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($recetas->hasPages())
            <div class="card-footer">
                <div class="float-right">{{ $recetas->links() }}</div>
            </div>
        @endif
    </div>
</div>

{{-- Modal: Agregar Insumo a Receta --}}
<div class="modal fade" id="modalAgregarInsumo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white">
                    <i class="fas fa-plus mr-2"></i> Agregar Insumo
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formAgregarInsumo">
                @csrf
                <input type="hidden" name="id_receta" id="insumo_id_receta">
                <div class="modal-body">
                    <div class="alert alert-info" id="insumoRecetaNombre"></div>
                    <div class="form-group">
                        <label for="insumo_id_insumo">Insumo <span class="text-danger">*</span></label>
                        <select name="id_insumo" id="insumo_id_insumo" class="form-control" required>
                            <option value="">Seleccione un insumo...</option>
                            @foreach($categorias as $cat)
                                <optgroup label="── {{ $cat->nombre }} ──">
                                    @foreach($cat->insumos as $insumo)
                                        <option value="{{ $insumo->id_insumo }}">{{ $insumo->item->nombre ?? $insumo->nombre }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="insumo_cantidad">Cantidad <span class="text-danger">*</span></label>
                            <input type="number" name="cantidad" id="insumo_cantidad" class="form-control" step="0.001" min="0.001" required>
                        </div>
                        <div class="col-md-6">
                            <label for="insumo_unidad">Unidad <span class="text-danger">*</span></label>
                            <select name="unidad" id="insumo_unidad" class="form-control" required>
                                @foreach(['kg','g','lb','oz','L','mL','unidad'] as $um)
                                    <option value="{{ $um }}">{{ $um }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Agregar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Editar Insumo --}}
<div class="modal fade" id="modalEditarInsumo" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-edit mr-2"></i> Editar Cantidad</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEditarInsumo">
                @csrf
                @method('PUT')
                <input type="hidden" name="detalle_id" id="edit_detalle_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Cantidad <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad" id="edit_cantidad" class="form-control" step="0.001" min="0.001" required>
                    </div>
                    <div class="form-group">
                        <label>Unidad</label>
                        <select name="unidad" id="edit_unidad" class="form-control" required>
                            @foreach(['kg','g','lb','oz','L','mL','unidad'] as $um)
                                <option value="{{ $um }}">{{ $um }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    // ============================================
    // TOGGLE DETALLES DE RECETA
    // ============================================
    $(document).on('click', '.btn-toggle-detalles', function() {
        var recetaId = $(this).data('receta-id');
        var row = $('#detalles-receta-' + recetaId);
        
        if (row.hasClass('show')) {
            row.collapse('hide');
        } else {
            row.collapse('show');
            cargarDetalles(recetaId);
        }
    });

    function cargarDetalles(recetaId) {
        var container = $('#detalles-content-' + recetaId);
        container.html('<div class="text-center py-3"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>');
        
        $.get('/recetas/' + recetaId + '/detalles-ajax')
            .done(function(response) {
                renderizarDetalles(recetaId, response.detalles);
            })
            .fail(function() {
                container.html('<div class="alert alert-danger">Error al cargar insumos</div>');
            });
    }

    function renderizarDetalles(recetaId, detalles) {
        var container = $('#detalles-content-' + recetaId);
        
        if (detalles.length === 0) {
            container.html('<p class="text-muted text-center py-2">Sin insumos asignados</p>');
            return;
        }

        var html = '<div class="table-responsive"><table class="table table-sm table-striped mb-0">';
        html += '<thead><tr><th>Insumo</th><th>Categoría</th><th>Cantidad</th><th class="text-center">Acciones</th></tr></thead><tbody>';
        
        detalles.forEach(function(d) {
            html += '<tr>';
            html += '<td><code>' + (d.insumo?.nombre || d.insumo?.item?.nombre || 'N/A') + '</code></td>';
            html += '<td><small>' + (d.insumo?.categoria?.nombre || '-') + '</small></td>';
            html += '<td><span class="badge badge-dark">' + d.cantidad_requerida + ' ' + d.unidad_medida + '</span></td>';
            html += '<td class="text-center">';
            html += '<button class="btn btn-xs btn-outline-warning btn-editar-insumo" data-id="' + d.id_detalle_receta + '" data-cantidad="' + d.cantidad_requerida + '" data-unidad="' + d.unidad_medida + '" title="Editar"><i class="fas fa-edit"></i></button> ';
            html += '<button class="btn btn-xs btn-outline-danger btn-eliminar-insumo" data-id="' + d.id_detalle_receta + '" data-receta="' + recetaId + '" title="Eliminar"><i class="fas fa-trash"></i></button>';
            html += '</td></tr>';
        });
        
        html += '</tbody></table></div>';
        container.html(html);
    }

    // ============================================
    // AGREGAR INSUMO
    // ============================================
    $(document).on('click', '.btn-agregar-insumo', function() {
        var recetaId = $(this).data('receta-id');
        var recetaNombre = $(this).data('receta-nombre');
        
        $('#insumo_id_receta').val(recetaId);
        $('#insumoRecetaNombre').html('<i class="fas fa-book mr-1"></i> Receta: <strong>' + recetaNombre + '</strong>');
        $('#modalAgregarInsumo').modal('show');
    });

    $('#formAgregarInsumo').on('submit', function(e) {
        e.preventDefault();
        var recetaId = $('#insumo_id_receta').val();
        
        $.post('/recetas/' + recetaId + '/detalles', $(this).serialize())
            .done(function(response) {
                $('#modalAgregarInsumo').modal('hide');
                $('#formAgregarInsumo')[0].reset();
                cargarDetalles(recetaId);
                toastr.success(response.message);
            })
            .fail(function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error al agregar');
            });
    });

    // ============================================
    // EDITAR INSUMO
    // ============================================
    $(document).on('click', '.btn-editar-insumo', function() {
        var id = $(this).data('id');
        var cantidad = $(this).data('cantidad');
        var unidad = $(this).data('unidad');
        
        $('#edit_detalle_id').val(id);
        $('#edit_cantidad').val(cantidad);
        $('#edit_unidad').val(unidad);
        $('#modalEditarInsumo').modal('show');
    });

    $('#formEditarInsumo').on('submit', function(e) {
        e.preventDefault();
        var detalleId = $('#edit_detalle_id').val();
        
        $.ajax({
            url: '/detalles-receta/' + detalleId,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                $('#modalEditarInsumo').modal('hide');
                var recetaId = $('.detalles-row.show').attr('id').replace('detalles-receta-', '');
                if (recetaId) cargarDetalles(recetaId);
                toastr.success(response.message);
            },
            error: function() {
                toastr.error('Error al actualizar');
            }
        });
    });

    // ============================================
    // ELIMINAR INSUMO
    // ============================================
    $(document).on('click', '.btn-eliminar-insumo', function() {
        if (!confirm('¿Remover este insumo de la receta?')) return;
        
        var detalleId = $(this).data('id');
        var recetaId = $(this).data('receta');
        
        $.ajax({
            url: '/detalles-receta/' + detalleId,
            method: 'DELETE',
            data: {_token: '{{ csrf_token() }}'},
            success: function(response) {
                cargarDetalles(recetaId);
                toastr.success(response.message);
            },
            error: function() {
                toastr.error('Error al eliminar');
            }
        });
    });
});
</script>
@endpush