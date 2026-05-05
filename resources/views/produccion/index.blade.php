{{-- resources/views/produccion/index.blade.php --}}
@extends('layouts.adminlte')

@section('title', 'Módulo de Producción - Panadería Otto')
@section('page-title', 'Panel de Producción')
@section('page-description', 'Gestión de recetas, insumos y categorías')

@push('styles')
<style>
    .module-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 15px;
        overflow: hidden;
        height: 100%;
    }
    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .module-icon {
        font-size: 48px;
        margin-bottom: 15px;
        color: #8B4513;
    }
    .module-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #5D3A1A;
    }
    .stats-card {
        background: linear-gradient(135deg, #8B4513 0%, #5D3A1A 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .stats-number {
        font-size: 36px;
        font-weight: bold;
    }
    .quick-action-btn {
        margin: 5px;
        padding: 12px 20px;
        font-size: 16px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    {{-- Estadísticas rápidas --}}
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $totalRecetas ?? 0 }}</div>
                <div class="stats-label">Recetas Totales</div>
                <i class="fas fa-utensils float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #A0522D 0%, #8B4513 100%);">
                <div class="stats-number">{{ $totalProducciones ?? 0 }}</div>
                <div class="stats-label">Producciones</div>
                <i class="fas fa-chart-bar float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #CD853F 0%, #A0522D 100%);">
                <div class="stats-number">{{ $totalCategorias ?? 0 }}</div>
                <div class="stats-label">Categorías</div>
                <i class="fas fa-folder float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #DEB887 0%, #CD853F 100%);">
                <div class="stats-number">{{ $totalInsumos ?? 0 }}</div>
                <div class="stats-label">Insumos</div>
                <i class="fas fa-box float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    {{-- Acciones rápidas --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background: #FFF5E6; border-bottom: 2px solid #D2B48C;">
                    <h5 class="mb-0" style="color: #5D3A1A;">
                        <i class="fas fa-bolt"></i> Acciones Rápidas - Crear Nuevo
                    </h5>
                </div>
                <div class="card-body text-center">
                    {{-- Usa los modales del módulo almacén --}}
                    <button class="btn btn-info quick-action-btn" data-toggle="modal" data-target="#createInsumoModal">
                        <i class="fas fa-box-open"></i> Nuevo Insumo
                    </button>
                    <button class="btn btn-primary quick-action-btn" data-toggle="modal" data-target="#createRecetaModal">
                        <i class="fas fa-book-medical"></i> Nueva Receta
                    </button>
                    <a href="{{ route('producciones.index') }}" class="btn btn-warning quick-action-btn">
                        <i class="fas fa-list-alt"></i> Ver Órdenes de Producción
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Sección: Nueva Producción --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background: #FFF5E6; border-bottom: 2px solid #D2B48C;">
                    <h5 class="mb-0" style="color: #5D3A1A;">
                        <i class="fas fa-industry"></i> Nueva Orden de Producción
                    </h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form id="formNuevaProduccion" action="{{ route('producciones.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Receta a producir <span class="text-danger">*</span></label>
                                    <select name="id_receta" id="produccion_receta" class="form-control" required>
                                        <option value="">Seleccione receta...</option>
                                        @foreach(\App\Models\Receta::with('producto')->get() as $receta)
                                            <option value="{{ $receta->id_receta }}" 
                                                    data-producto="{{ $receta->producto->item->nombre ?? 'Sin producto' }}">
                                                {{ $receta->nombre }}
                                                @if($receta->producto)
                                                    (Producto: {{ $receta->producto->item->nombre }})
                                                @else
                                                    (Sin producto)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cantidad a producir <span class="text-danger">*</span></label>
                                    <input type="number" name="cantidad_producida" id="produccion_cantidad" 
                                        class="form-control" step="0.1" min="0.1" placeholder="Ej: 5" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Notificar a empleado (opcional)</label>
                                    <select name="notificar_empleado" class="form-control">
                                        <option value="">No notificar</option>
                                        @foreach(\App\Models\Empleado::all() as $emp)
                                            <option value="{{ $emp->id_empleado }}">{{ $emp->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observaciones</label>
                                    <textarea name="observaciones" class="form-control" rows="2" 
                                        placeholder="Notas adicionales..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6><i class="fas fa-calculator"></i> Insumos requeridos:</h6>
                                <div id="preview-insumos" class="table-responsive">
                                    <p class="text-muted">Seleccione receta y cantidad para calcular.</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-primary btn-lg" id="btnCrearProduccion">
                                <i class="fas fa-paper-plane"></i> Solicitar Producción
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Últimas recetas creadas --}}
    @if(isset($ultimasRecetas) && count($ultimasRecetas) > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Últimas Recetas Creadas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr><th>Nombre</th><th>Descripción</th><th>Insumos</th><th>Creada</th><th>Acciones</th></tr>
                            </thead>
                            <tbody>
                                @foreach($ultimasRecetas as $receta)
                                <tr>
                                    <td><strong>{{ $receta->nombre }}</strong></td>
                                    <td>{{ Str::limit($receta->descripcion, 40) ?: '-' }}</td>
                                    <td><span class="badge badge-info">{{ $receta->detalles_count ?? 0 }} insumos</span></td>
                                    <td>{{ $receta->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('recetas.show', $receta) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('produccion.recetas.detalles', $receta) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-plus-circle"></i> Insumos
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- ===================================================== --}}
{{-- MODALES REUTILIZADOS DEL MÓDULO ALMACÉN --}}
{{-- ===================================================== --}}
@include('modulo-almacen.partials.modal-categoria-insumo')
@include('modulo-almacen.partials.modal-insumo', ['categorias' => $categorias])
@include('modulo-almacen.partials.modal-categoria-producto')

{{-- Modal exclusivo de producción: Crear Receta --}}
<div class="modal fade" id="createRecetaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title"><i class="fas fa-book-medical"></i> Nueva Receta</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateRecetaCompleta">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre de la Receta <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="recetaNombre" class="form-control" 
                                       placeholder="Ej: Pan Francés, Tarta de Manzana..." required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Producto Final <span class="text-danger">*</span></label>
                                <select name="id_producto" id="recetaProducto" class="form-control" required>
                                    <option value="">Seleccione producto...</option>
                                    @foreach(\App\Models\Producto::with('item')->get() as $producto)
                                        <option value="{{ $producto->id_producto }}">
                                            {{ $producto->item->nombre }} ({{ $producto->item->unidad_medida ?? 'unidad' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea name="descripcion" id="recetaDescripcion" class="form-control" rows="2" 
                                          placeholder="Describe brevemente la receta..."></textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6><i class="fas fa-boxes"></i> Agregar Insumos a la Receta</h6>
                    <div id="insumosContainer" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px; padding: 10px; margin-bottom: 15px;">
                        @foreach($categorias ?? [] as $categoria)
                            @if($categoria->insumos->count() > 0)
                                <div class="categoria-section mb-3">
                                    <div class="categoria-header bg-light p-2 mb-2" style="border-left: 4px solid #8B4513;">
                                        <strong><i class="fas fa-folder"></i> {{ $categoria->nombre }}</strong>
                                        <span class="badge badge-secondary ml-2">{{ $categoria->insumos->count() }}</span>
                                    </div>
                                    <div class="row">
                                        @foreach($categoria->insumos as $insumo)
                                            <div class="col-md-6 mb-2">
                                                <div class="insumo-item border rounded p-2">
                                                    <div class="custom-control custom-checkbox mb-2">
                                                        <input type="checkbox" class="custom-control-input insumo-checkbox" 
                                                            id="modal_insumo_{{ $insumo->id_insumo }}" value="{{ $insumo->id_insumo }}">
                                                        <label class="custom-control-label" for="modal_insumo_{{ $insumo->id_insumo }}">
                                                            <strong>{{ $insumo->item->nombre ?? $insumo->nombre ?? 'Insumo' }}</strong>
                                                            <small class="text-muted">({{ $insumo->item->unidad_medida ?? 'unidad' }})</small>
                                                        </label>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <input type="number" name="cantidad_{{ $insumo->id_insumo }}" 
                                                                class="form-control form-control-sm cantidad-insumo"
                                                                placeholder="Cantidad" step="0.001" min="0.001" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="text-right">
                        <span id="insumosSeleccionadosCount" class="mr-2">0 insumos seleccionados</span>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Selecciona los insumos y especifica la cantidad. La unidad de medida se toma del insumo.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnCrearRecetaCompleta">
                        <i class="fas fa-save"></i> Crear Receta con Insumos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // Habilitar/deshabilitar campos de cantidad al marcar checkbox
    $(document).on('change', '.insumo-checkbox', function() {
        var container = $(this).closest('.insumo-item');
        var isChecked = $(this).prop('checked');
        container.find('.cantidad-insumo').prop('disabled', !isChecked);
        if (isChecked) {
            container.find('.cantidad-insumo').prop('required', true).val('1');
        } else {
            container.find('.cantidad-insumo').prop('required', false).val('');
        }
        actualizarContadorInsumos();
    });
    
    function actualizarContadorInsumos() {
        var count = $('.insumo-checkbox:checked').length;
        $('#insumosSeleccionadosCount').text(count + ' insumos seleccionados');
    }
    
    // Crear Receta
    $('#formCreateRecetaCompleta').on('submit', function(e) {
        e.preventDefault();
        var nombre = $('#recetaNombre').val();
        var descripcion = $('#recetaDescripcion').val();
        var id_producto = $('#recetaProducto').val();
        
        if (!nombre) { toastr.error('El nombre de la receta es requerido'); return; }
        if (!id_producto) { toastr.error('Debe seleccionar un producto final'); return; }
        
        var insumos = [];
        $('.insumo-checkbox:checked').each(function() {
            var id = $(this).val();
            var container = $(this).closest('.insumo-item');
            var cantidad = container.find('.cantidad-insumo').val();
            if (cantidad && cantidad > 0) {
                insumos.push({ id_insumo: id, cantidad: cantidad });
            }
        });
        
        $.ajax({
            url: '{{ route("produccion.recetas.store") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nombre: nombre, descripcion: descripcion, id_producto: id_producto
            },
            success: function(response) {
                if (response.success && response.receta) {
                    var recetaId = response.receta.id_receta;
                    if (insumos.length > 0) {
                        $.ajax({
                            url: '/produccion/recetas/' + recetaId + '/detalles',
                            method: 'POST',
                            data: { _token: '{{ csrf_token() }}', insumos: insumos },
                            success: function() {
                                $('#createRecetaModal').modal('hide');
                                toastr.success('Receta creada con ' + insumos.length + ' insumos');
                                $('#formCreateRecetaCompleta')[0].reset();
                                $('.insumo-checkbox').prop('checked', false).trigger('change');
                                setTimeout(() => { window.location.href = '/produccion/recetas/' + recetaId + '/detalles'; }, 1000);
                            },
                            error: function() {
                                toastr.warning('Receta creada pero hubo error al agregar insumos');
                                setTimeout(() => location.reload(), 1500);
                            }
                        });
                    } else {
                        $('#createRecetaModal').modal('hide');
                        toastr.success('Receta creada correctamente');
                        $('#formCreateRecetaCompleta')[0].reset();
                        setTimeout(() => { window.location.href = '/produccion/recetas/' + recetaId + '/detalles'; }, 1000);
                    }
                }
            },
            error: function(xhr) {
                var message = 'Error al crear la receta';
                if (xhr.responseJSON?.errors) message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                toastr.error(message);
            }
        });
    });
    
    // Previsualización de insumos
    $('#produccion_receta, #produccion_cantidad').on('change keyup', function() {
        var recetaId = $('#produccion_receta').val();
        var cantidad = parseFloat($('#produccion_cantidad').val());
        if (recetaId && cantidad && cantidad > 0) {
            $.ajax({
                url: '{{ route("producciones.calcular-insumos") }}',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', id_receta: recetaId, cantidad: cantidad },
                success: function(response) {
                    var html = '<table class="table table-sm table-bordered"><thead><tr><th>Insumo</th><th>Cant. base</th><th>Cant. requerida</th><th>Unidad</th></tr></thead><tbody>';
                    response.insumos.forEach(function(ins) {
                        html += '<tr><td>' + ins.insumo + '</td><td>' + ins.cantidad_teorica + '</td><td><strong>' + ins.cantidad_requerida.toFixed(3) + '</strong></td><td>' + ins.unidad + '</td></tr>';
                    });
                    html += '</tbody></table>';
                    $('#preview-insumos').html(html);
                },
                error: function() { $('#preview-insumos').html('<p class="text-danger">Error al calcular insumos.</p>'); }
            });
        } else {
            $('#preview-insumos').html('<p class="text-muted">Seleccione receta y cantidad para calcular.</p>');
        }
    });
    
});
</script>
@endpush