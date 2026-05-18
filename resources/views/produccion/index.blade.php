{{-- resources/views/produccion/index.blade.php --}}
@extends('layouts.adminlte')

@section('title', 'Módulo de Producción - Panadería Otto')
@section('page-title', 'Panel de Producción')
@section('page-description', 'Gestión de recetas, insumos y categorías')

@push('styles')
<style>
    /* ==========================================
       ESTILOS ESPECÍFICOS DEL PANEL DE PRODUCCIÓN
       ========================================== */
    .module-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: var(--border-radius-md);
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
        color: var(--color-primary);
    }
    .module-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--color-primary-dark);
    }

    /* Tarjetas de estadísticas */
    .stats-card {
        border-radius: var(--border-radius-sm);
        padding: 20px;
        margin-bottom: 20px;
        color: var(--text-on-primary);
        position: relative;
        overflow: hidden;
    }
    .stats-card-primary {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
    }
    .stats-card-secondary {
        background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-primary-dark) 100%);
    }
    .stats-card-accent {
        background: linear-gradient(135deg, var(--color-accent-dark, #c9a871) 0%, var(--color-secondary) 100%);
        color: var(--color-primary-dark);
    }
    .stats-card-light {
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-dark, #c9a871) 100%);
        color: var(--color-primary-dark);
    }
    .stats-number {
        font-size: 36px;
        font-weight: bold;
    }
    .stats-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    .stats-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 40px;
        opacity: 0.2;
    }

    /* Botones de acción rápida */
    .quick-action-btn {
        margin: 5px;
        padding: 12px 20px;
        font-size: 16px;
        border-radius: 25px !important;
    }

    /* Panel de acciones rápidas */
    .quick-actions-panel {
        background: var(--color-bg-lighter);
        border-bottom: 2px solid var(--color-accent);
    }
    .quick-actions-panel h5 {
        color: var(--color-primary-dark);
    }

    /* Previsualización de total */
    .total-preview {
        background: var(--color-bg-lighter) !important;
        color: var(--color-primary) !important;
        font-weight: bold;
    }

    /* Insumos container */
    .insumos-container {
        max-height: 350px;
        overflow-y: auto;
        background: var(--bg-card, white);
        border-radius: var(--border-radius-sm);
        padding: 0.5rem;
        border: 2px solid var(--color-accent);
    }
    .insumo-category-header {
        background: rgba(139, 69, 19, 0.08);
        border-left: 3px solid var(--color-primary);
        font-size: 0.8rem;
        border-radius: 4px;
        padding: 0.25rem 0.5rem;
        margin-bottom: 0.25rem;
    }
    .insumo-item {
        background: var(--color-bg-light);
        border: 1px solid var(--color-border);
        border-radius: var(--border-radius-sm);
        font-size: 0.85rem;
    }
    .insumo-item:hover {
        background: var(--color-bg-lighter);
    }
    .insumo-cantidad {
        width: 70px;
        height: 28px;
        font-size: 0.8rem;
        border-radius: var(--border-radius-sm);
    }

    /* Modal footer */
    .modal-footer-theme {
        background: var(--color-bg-lighter);
        border-top: 1px solid var(--color-accent);
    }

    /* Resumen de insumos */
    .insumos-summary {
        color: var(--color-secondary);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    {{-- ========================================== --}}
    {{-- ESTADÍSTICAS RÁPIDAS --}}
    {{-- ========================================== --}}
    <div class="row">
        {{-- Recetas Totales --}}
        <div class="col-md-3">
            <div class="stats-card stats-card-primary">
                <div class="stats-number">{{ $totalRecetas ?? 0 }}</div>
                <div class="stats-label">Recetas Totales</div>
                <i class="fas fa-utensils stats-icon"></i>
            </div>
        </div>

        {{-- Producciones --}}
        <div class="col-md-3">
            <div class="stats-card stats-card-secondary">
                <div class="stats-number">{{ $totalProducciones ?? 0 }}</div>
                <div class="stats-label">Producciones</div>
                <i class="fas fa-chart-bar stats-icon"></i>
            </div>
        </div>

        {{-- Categorías --}}
        <div class="col-md-3">
            <div class="stats-card stats-card-accent">
                <div class="stats-number">{{ $totalCategorias ?? 0 }}</div>
                <div class="stats-label">Categorías</div>
                <i class="fas fa-folder stats-icon"></i>
            </div>
        </div>

        {{-- Insumos --}}
        <div class="col-md-3">
            <div class="stats-card stats-card-light">
                <div class="stats-number">{{ $totalInsumos ?? 0 }}</div>
                <div class="stats-label">Insumos</div>
                <i class="fas fa-box stats-icon"></i>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- ACCIONES RÁPIDAS --}}
    {{-- ========================================== --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header quick-actions-panel">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt"></i> Acciones Rápidas - Crear Nuevo
                    </h5>
                </div>
                <div class="card-body text-center">
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

    {{-- ========================================== --}}
    {{-- NUEVA ORDEN DE PRODUCCIÓN --}}
    {{-- ========================================== --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header quick-actions-panel">
                    <h5 class="mb-0">
                        <i class="fas fa-industry"></i> Nueva Orden de Producción
                    </h5>
                </div>
                <div class="card-body">
                    {{-- Errores y mensajes --}}
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
                            {{-- Selección de receta --}}
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Receta a producir <span class="text-danger">*</span></label>
                                    <select name="id_receta" id="produccion_receta" class="form-control" required>
                                        <option value="">Seleccione receta...</option>
                                        @foreach(\App\Models\Receta::with('producto.item')->get() as $receta)
                                            <option value="{{ $receta->id_receta }}" 
                                                    data-producto="{{ $receta->producto->item->nombre ?? 'Sin producto' }}"
                                                    data-rinde="{{ $receta->cantidad_requerida }}"
                                                    data-unidad="{{ $receta->producto->item->unidad_medida ?? 'unidad' }}">
                                                {{ $receta->nombre }}
                                                @if($receta->producto && $receta->producto->item)
                                                    ({{ $receta->producto->item->nombre }} - 
                                                    {{ $receta->cantidad_requerida }} {{ $receta->producto->item->unidad_medida }}/lote)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Número de lotes --}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Número de lotes <span class="text-danger">*</span></label>
                                    <input type="number" name="numero_lotes" id="produccion_lotes" 
                                        class="form-control" step="0.1" min="0.1" 
                                        placeholder="Ej: 3" value="1" required>
                                    <small class="text-muted">¿Cuántas veces se ejecutará la receta?</small>
                                </div>
                            </div>

                            {{-- Total a producir (calculado automáticamente) --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Total de producto a obtener</label>
                                    <div id="total-producto-preview" class="form-control total-preview">
                                        Seleccione una receta
                                    </div>
                                    <input type="hidden" name="cantidad_producida" id="produccion_cantidad_total">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Observaciones</label>
                                    <textarea name="observaciones" class="form-control" rows="2" 
                                            placeholder="Notas adicionales..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha de Vencimiento del Producto</label>
                                    <input type="date" name="fecha_vencimiento_producto" id="produccion_fecha_vencimiento" 
                                        class="form-control">
                                    <small class="text-muted">Opcional. ¿Cuándo caduca esta tanda?</small>
                                </div>
                            </div>
                        </div>

                        {{-- Previsualización de insumos --}}
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6><i class="fas fa-calculator"></i> Insumos requeridos:</h6>
                                <div id="preview-insumos" class="table-responsive">
                                    <p class="text-muted">Seleccione receta y número de lotes para calcular.</p>
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

    {{-- ========================================== --}}
    {{-- ÚLTIMAS RECETAS CREADAS --}}
    {{-- ========================================== --}}
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
                                <tr>
                                    <th>Nombre</th>
                                    <th>Producto</th>
                                    <th class="text-center">Rinde</th>
                                    <th class="text-center">Insumos</th>
                                    <th>Descripción</th>
                                    <th>Creada</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimasRecetas as $receta)
                                <tr>
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
                                        <span class="badge badge-info">{{ $receta->detalles_count ?? 0 }} insumos</span>
                                    </td>
                                    <td>{{ Str::limit($receta->descripcion, 40) ?: '-' }}</td>
                                    <td>{{ $receta->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('produccion.recetas.detalles', $receta) }}" class="btn btn-sm btn-success" title="Agregar insumos">
                                            <i class="fas fa-plus-circle"></i>
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
@include('modulo-almacen.partials.modal-categoria-producto')

{{-- ===================================================== --}}
{{-- MODAL: CREAR RECETA --}}
{{-- ===================================================== --}}
<div class="modal fade" id="createRecetaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-panaderia shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-book-open mr-2"></i> Nueva Receta
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <form id="formCreateRecetaCompleta">
                @csrf
                <div class="modal-body bg-panaderia-light">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="text-panaderia">
                                    <i class="fas fa-signature mr-1"></i> Nombre <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre" id="recetaNombre" 
                                    class="form-control" placeholder="Ej: Pan Francés" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="text-panaderia">
                                    <i class="fas fa-box mr-1"></i> Producto Final <span class="text-danger">*</span>
                                </label>
                                <select name="id_producto" id="recetaProducto" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    @foreach(\App\Models\Producto::with('item')->get() as $producto)
                                        <option value="{{ $producto->id_producto }}">
                                            {{ $producto->item->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="text-panaderia">
                                    <i class="fas fa-cubes mr-1"></i> Rinde <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="cantidad_producida" id="recetaCantidadProducto" 
                                    class="form-control" step="0.1" min="0.1" 
                                    placeholder="Unidades" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-panaderia">
                            <i class="fas fa-align-left mr-1"></i> Descripción
                        </label>
                        <textarea name="descripcion" id="recetaDescripcion" class="form-control" 
                            rows="2" placeholder="Breve descripción..."></textarea>
                    </div>

                    {{-- Sección de insumos --}}
                    <div class="d-flex align-items-center mb-2">
                        <button type="button" class="btn btn-accent" id="btnAbrirInsumosModal">
                            <i class="fas fa-boxes mr-1"></i> Seleccionar insumos
                        </button>
                        <span id="resumenInsumos" class="ml-3 insumos-summary">
                            <i class="fas fa-info-circle"></i> No hay insumos seleccionados
                        </span>
                    </div>
                    <input type="hidden" name="insumos_seleccionados" id="insumosSeleccionadosData" value="">
                </div>

                <div class="modal-footer modal-footer-theme">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-save" id="btnCrearRecetaCompleta">
                        <i class="fas fa-save mr-1"></i> Crear Receta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===================================================== --}}
{{-- MODAL: SELECCIONAR INSUMOS --}}
{{-- ===================================================== --}}
<div class="modal fade" id="selectInsumosModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content border-panaderia shadow-lg">
            <div class="modal-header py-2">
                <h6 class="modal-title mb-0">
                    <i class="fas fa-boxes mr-1"></i> Insumos
                </h6>
                <span id="insumosCountBadge" class="badge ml-2" style="background: var(--color-accent); color: var(--color-primary-dark);">0</span>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body bg-panaderia-light p-2">
                <div id="insumosContainer" class="insumos-container">
                    @foreach($categorias ?? [] as $categoria)
                        @if($categoria->insumos->count() > 0)
                            <div class="mb-2">
                                <div class="insumo-category-header">
                                    <i class="fas fa-folder text-panaderia mr-1"></i>
                                    <strong class="text-panaderia">{{ $categoria->nombre }}</strong>
                                </div>
                                <div class="row mx-0">
                                    @foreach($categoria->insumos as $insumo)
                                        <div class="col-12 mb-1 px-1">
                                            <div class="insumo-item d-flex align-items-center py-1 px-2">
                                                <div class="custom-control custom-checkbox mr-2" style="min-height: auto; line-height: 1;">
                                                    <input type="checkbox" class="custom-control-input insumo-checkbox" 
                                                        id="modal_insumo_{{ $insumo->id_insumo }}" 
                                                        value="{{ $insumo->id_insumo }}">
                                                    <label class="custom-control-label" for="modal_insumo_{{ $insumo->id_insumo }}"></label>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="text-panaderia font-weight-bold">
                                                        {{ $insumo->item->nombre ?? $insumo->nombre }}
                                                    </span>
                                                    <small class="text-muted ml-1">
                                                        ({{ $insumo->item->unidad_medida ?? 'unid' }})
                                                    </small>
                                                </div>
                                                <input type="number" name="cantidad_{{ $insumo->id_insumo }}" 
                                                    class="form-control form-control-sm cantidad-insumo text-right insumo-cantidad"
                                                    placeholder="Cant." step="0.001" min="0.001" disabled>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="modal-footer modal-footer-theme py-2">
                <small class="insumos-summary mr-auto">
                    <i class="fas fa-info-circle"></i> Marca y asigna cantidad
                </small>
                <button type="button" class="btn btn-sm btn-cancel" data-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-sm btn-save" id="btnAplicarSeleccion">
                    <i class="fas fa-check mr-1"></i> Aplicar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // ==========================================
    // SCRIPT PARA NUEVA PRODUCCIÓN
    // ==========================================
    $('#produccion_receta, #produccion_lotes').on('change keyup', function() {
        var recetaSelect = $('#produccion_receta option:selected');
        var recetaId = $('#produccion_receta').val();
        var lotes = parseFloat($('#produccion_lotes').val());
        var rinde = parseFloat(recetaSelect.data('rinde')) || 1;
        var unidad = recetaSelect.data('unidad') || 'unidad';

        if (recetaId && lotes && lotes > 0) {
            var totalProducto = lotes * rinde;
            $('#total-producto-preview').text(totalProducto + ' ' + unidad + '(s)');
            $('#produccion_cantidad_total').val(totalProducto);

            // Calcular insumos
            $.ajax({
                url: '{{ route("producciones.calcular-insumos") }}',
                method: 'POST',
                data: { 
                    _token: '{{ csrf_token() }}', 
                    id_receta: recetaId, 
                    cantidad: totalProducto
                },
                success: function(response) {
                    var html = '<table class="table table-sm table-bordered"><thead><tr><th>Insumo</th><th>Cant. por lote</th><th>Cant. total necesaria</th><th>Unidad</th></tr></thead><tbody>';
                    response.insumos.forEach(function(ins) {
                        html += '<tr><td>' + ins.insumo + '</td><td>' + ins.cantidad_teorica + '</td><td><strong>' + ins.cantidad_requerida.toFixed(3) + '</strong></td><td>' + ins.unidad + '</td></tr>';
                    });
                    html += '</tbody></table>';
                    $('#preview-insumos').html(html);
                },
                error: function(xhr) { 
                    $('#preview-insumos').html('<p class="text-danger">Error al calcular insumos.</p>');
                }
            });
        } else {
            $('#total-producto-preview').text('Seleccione una receta y un número de lotes');
            $('#produccion_cantidad_total').val('');
            $('#preview-insumos').html('<p class="text-muted">Seleccione receta y número de lotes para calcular.</p>');
        }
    });

    // ==========================================
    // SCRIPT PARA CREAR RECETA (MODAL)
    // ==========================================
    let insumosSeleccionados = {};

    // Abrir modal de selección de insumos
    $('#btnAbrirInsumosModal').on('click', function() {
        $('.insumo-checkbox').prop('checked', false).trigger('change');
        $.each(insumosSeleccionados, function(id, cantidad) {
            $('#modal_insumo_' + id).prop('checked', true).trigger('change');
            $('input[name="cantidad_' + id + '"]').val(cantidad);
        });
        actualizarContadorInsumos();
        $('#selectInsumosModal').modal('show');
    });

    // Habilitar/deshabilitar campo cantidad al marcar checkbox
    $(document).on('change', '.insumo-checkbox', function() {
        var container = $(this).closest('.insumo-item');
        var isChecked = $(this).prop('checked');
        container.find('.cantidad-insumo').prop('disabled', !isChecked);
        if (isChecked) {
            container.find('.cantidad-insumo').val('1').focus();
        } else {
            container.find('.cantidad-insumo').val('');
        }
        actualizarContadorInsumos();
    });

    function actualizarContadorInsumos() {
        var count = $('.insumo-checkbox:checked').length;
        $('#insumosCountBadge').text(count);
    }

    // Botón "Aplicar selección"
    $('#btnAplicarSeleccion').on('click', function() {
        insumosSeleccionados = {};
        $('.insumo-checkbox:checked').each(function() {
            var id = $(this).val();
            var cantidad = $(this).closest('.insumo-item').find('.cantidad-insumo').val();
            if (cantidad && parseFloat(cantidad) > 0) {
                insumosSeleccionados[id] = cantidad;
            }
        });

        var count = Object.keys(insumosSeleccionados).length;
        if (count > 0) {
            $('#resumenInsumos').html('<i class="fas fa-check-circle text-success mr-1"></i> <strong>' + count + ' insumo(s) seleccionado(s)</strong>');
            $('#insumosSeleccionadosData').val(JSON.stringify(insumosSeleccionados));
        } else {
            $('#resumenInsumos').html('<i class="fas fa-info-circle"></i> No hay insumos seleccionados');
            $('#insumosSeleccionadosData').val('');
        }

        $('#selectInsumosModal').modal('hide');
    });

    // Envío del formulario de receta
    $('#formCreateRecetaCompleta').on('submit', function(e) {
        e.preventDefault();
        var nombre = $('#recetaNombre').val();
        var id_producto = $('#recetaProducto').val();
        var cantidad_producto = parseFloat($('#recetaCantidadProducto').val()) || 1;
        var descripcion = $('#recetaDescripcion').val();

        if (!nombre) { toastr.error('El nombre de la receta es requerido'); return; }
        if (!id_producto) { toastr.error('Debe seleccionar un producto final'); return; }
        if (!cantidad_producto || cantidad_producto <= 0) {
            toastr.error('La cantidad de producto final debe ser mayor a 0');
            return;
        }

        var insumosArray = [];
        $.each(insumosSeleccionados, function(id, cant) {
            insumosArray.push({ id_insumo: id, cantidad: cant });
        });

        $.ajax({
            url: '{{ route("produccion.recetas.store") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nombre: nombre,
                descripcion: descripcion,
                id_producto: id_producto,
                cantidad_producida: cantidad_producto,
                insumos: insumosArray
            },
            success: function(response) {
                if (response.success && response.receta) {
                    var recetaId = response.receta.id_receta;
                    $('#createRecetaModal').modal('hide');
                    toastr.success('Receta creada correctamente');
                    $('#formCreateRecetaCompleta')[0].reset();
                    insumosSeleccionados = {};
                    $('#resumenInsumos').html('<i class="fas fa-info-circle"></i> No hay insumos seleccionados');
                    $('#insumosSeleccionadosData').val('');
                    setTimeout(() => { window.location.href = '/produccion/recetas/' + recetaId + '/detalles'; }, 1000);
                }
            },
            error: function(xhr) {
                var message = 'Error al crear la receta';
                if (xhr.responseJSON?.errors) message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                toastr.error(message);
            }
        });
    });
    
});
</script>
@endpush