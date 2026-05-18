@extends('layouts.adminlte')

@section('title', 'Módulo de Almacén - Panadería Otto')
@section('page-title', 'Panel de Almacén')
@section('page-description', 'Gestión de almacenes, productos, insumos y stock')

@push('styles')
<style>
    /* ==========================================
       ESTILOS ESPECÍFICOS - MÓDULO ALMACÉN
       ========================================== */
    
    /* Stats cards */
    .stats-card {
        border-radius: var(--border-radius-md);
        padding: 1.5rem;
        margin-bottom: 1rem;
        color: var(--text-on-primary);
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease;
        min-height: 110px;
    }
    .stats-card:hover { transform: translateY(-3px); }
    .stats-card .stats-number {
        font-size: 2.2rem;
        font-weight: 700;
        line-height: 1.1;
    }
    .stats-card .stats-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-top: 0.25rem;
    }
    .stats-card .stats-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 3rem;
        opacity: 0.2;
    }
    
    /* Variantes de stats cards */
    .stats-card-primary {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
    }
    .stats-card-info {
        background: linear-gradient(135deg, var(--badge-info) 0%, color-mix(in srgb, var(--badge-info) 70%, black) 100%);
    }
    .stats-card-accent {
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-dark, #c9a871) 100%);
        color: var(--color-primary-dark);
    }
    .stats-card-accent .stats-label { opacity: 0.8; }
    .stats-card-accent .stats-icon { opacity: 0.15; color: var(--color-primary-dark); }
    .stats-card-secondary {
        background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-primary-dark) 100%);
    }
    
    /* Acciones rápidas */
    .quick-actions-card .card-header {
        background: var(--color-bg-lighter);
        border-bottom: 2px solid var(--color-primary);
    }
    .quick-actions-card .card-header h5 {
        color: var(--color-primary-dark);
    }
    .quick-action-btn {
        margin: 5px;
        padding: 12px 20px;
        font-size: 1rem;
        transition: all 0.2s ease;
    }
    .quick-action-btn:hover {
        transform: translateY(-2px);
    }
    
    /* Lista de almacenes */
    .almacen-list-item {
        cursor: pointer;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
        color: var(--color-primary-dark);
    }
    .almacen-list-item:hover {
        background: var(--color-bg-lighter);
        border-left-color: var(--color-accent);
    }
    .almacen-list-item.active {
        background: var(--color-bg-lighter);
        border-left-color: var(--color-primary);
        font-weight: 600;
    }
    .almacen-list-item .badge {
        transition: all 0.2s ease;
    }
    
    /* Tabla de items */
    .table-almacen-items {
        max-height: 300px;
        overflow-y: auto;
    }
    .table-almacen-items::-webkit-scrollbar { width: 6px; }
    .table-almacen-items::-webkit-scrollbar-track {
        background: var(--color-bg-light);
        border-radius: 3px;
    }
    .table-almacen-items::-webkit-scrollbar-thumb {
        background: var(--color-border);
        border-radius: 3px;
    }
    
    /* Badge de tipo item */
    .badge-tipo-producto {
        background: var(--badge-success);
        color: white;
    }
    .badge-tipo-insumo {
        background: var(--badge-warning);
        color: var(--color-primary-dark);
    }
    
    /* Card header para panel */
    .card-header-accent {
        background: var(--color-bg-lighter);
        border-bottom: 2px solid var(--color-primary);
    }
    .card-header-accent .card-title {
        color: var(--color-primary-dark);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    {{-- Estadísticas --}}
    <div class="row">
        {{-- Almacenes --}}
        <div class="col-md-3">
            <div class="stats-card stats-card-primary">
                <div class="stats-number">{{ $totalAlmacenes ?? 0 }}</div>
                <div class="stats-label">Almacenes</div>
                <i class="fas fa-warehouse stats-icon"></i>
            </div>
        </div>
        
        {{-- Productos --}}
        <div class="col-md-3">
            <div class="stats-card stats-card-info">
                <div class="stats-number">{{ $totalProductos ?? 0 }}</div>
                <div class="stats-label">Productos</div>
                <i class="fas fa-box stats-icon"></i>
            </div>
        </div>
        
        {{-- Insumos --}}
        <div class="col-md-3">
            <div class="stats-card stats-card-accent">
                <div class="stats-number">{{ $totalInsumos ?? 0 }}</div>
                <div class="stats-label">Insumos</div>
                <i class="fas fa-flask stats-icon"></i>
            </div>
        </div>
        
        {{-- Items Totales --}}
        <div class="col-md-3">
            <div class="stats-card stats-card-secondary">
                <div class="stats-number">{{ $totalItems ?? 0 }}</div>
                <div class="stats-label">Items Totales</div>
                <i class="fas fa-cubes stats-icon"></i>
            </div>
        </div>
    </div>

    {{-- Acciones rápidas --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card quick-actions-card">
                <div class="card-header card-header-accent">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt mr-2"></i> Acciones Rápidas - Crear Nuevo
                    </h5>
                </div>
                <div class="card-body text-center">
                    <button class="btn btn-success quick-action-btn" data-toggle="modal" data-target="#createAlmacenModal">
                        <i class="fas fa-warehouse mr-1"></i> Nuevo Almacén
                    </button>
                    <button class="btn btn-primary quick-action-btn" data-toggle="modal" data-target="#createProductoModal">
                        <i class="fas fa-box mr-1"></i> Nuevo Producto
                    </button>
                    <button class="btn btn-secondary quick-action-btn" data-toggle="modal" data-target="#createInsumoModal">
                        <i class="fas fa-flask mr-1"></i> Nuevo Insumo
                    </button>
                    <button class="btn btn-danger quick-action-btn" data-toggle="modal" data-target="#manageStockModal">
                        <i class="fas fa-boxes mr-1"></i> Gestionar Stock
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Lista de Almacenes y su Stock --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-accent">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-warehouse mr-2"></i> Almacenes y su Inventario
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Lista de almacenes --}}
                        <div class="col-md-4">
                            <div class="list-group" id="listaAlmacenes">
                                @foreach($almacenes as $almacen)
                                    <a href="#" 
                                       class="list-group-item list-group-item-action almacen-list-item" 
                                       data-id="{{ $almacen->id_almacen }}">
                                        <i class="fas fa-warehouse mr-2"></i> {{ $almacen->nombre }}
                                        <span class="badge badge-primary float-right">
                                            {{ $almacen->items_count ?? 0 }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        
                        {{-- Tabla de items del almacén --}}
                        <div class="col-md-8">
                            <div class="table-responsive table-almacen-items">
                                <table class="table table-sm table-hover" id="tablaItemsAlmacen">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Tipo</th>
                                            <th>Stock</th>
                                            <th>Unidad</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsAlmacenBody">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                <i class="fas fa-hand-pointer mr-2"></i>
                                                Selecciona un almacén para ver su inventario
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Incluir modales --}}
@include('modulo-almacen.partials.modal-almacen')
@include('modulo-almacen.partials.modal-categoria-insumo')
@include('modulo-almacen.partials.modal-insumo', ['categorias' => $categoriasInsumo])
@include('modulo-almacen.partials.modal-categoria-producto')
@include('modulo-almacen.partials.modal-producto', ['categorias' => $categoriasProducto])
@include('modulo-almacen.partials.modal-stock', ['almacenes' => $almacenes, 'items' => $items])

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // ============================================
    // CARGAR ITEMS DE ALMACÉN
    // ============================================
    $('.almacen-list-item').on('click', function(e) {
        e.preventDefault();
        var almacenId = $(this).data('id');
        
        $('.almacen-list-item').removeClass('active');
        $(this).addClass('active');
        
        // Mostrar loader
        $('#itemsAlmacenBody').html(
            '<tr><td colspan="4" class="text-center text-muted">' +
            '<i class="fas fa-spinner fa-spin mr-2"></i>Cargando inventario...</td></tr>'
        );
        
        $.get('/modulo-almacen/' + almacenId + '/items', function(response) {
            var html = '';
            if (response.items && response.items.length > 0) {
                response.items.forEach(function(item) {
                    var nombreItem = item.item_nombre || item.nombre || 'N/A';
                    var tipoItem = item.tipo_item || item.tipo || 'N/A';
                    var tipoBadgeClass = tipoItem === 'producto' ? 'badge-tipo-producto' : 'badge-tipo-insumo';
                    var tipoTexto = tipoItem === 'producto' ? 'Producto' : (tipoItem === 'insumo' ? 'Insumo' : tipoItem);
                    
                    html += '<tr>';
                    html += '<td><strong>' + nombreItem + '</strong></td>';
                    html += '<td><span class="badge ' + tipoBadgeClass + '">' + tipoTexto + '</span></td>';
                    html += '<td>' + (item.stock || 0) + '</td>';
                    html += '<td>' + (item.unidad_medida || 'unidad') + '</td>';
                    html += '</tr>';
                });
            } else {
                html = '<tr><td colspan="4" class="text-center text-muted">' +
                       '<i class="fas fa-inbox mr-2"></i>Este almacén no tiene items</td></tr>';
            }
            $('#itemsAlmacenBody').html(html);
        }).fail(function(xhr) {
            console.error('Error:', xhr.responseText);
            $('#itemsAlmacenBody').html(
                '<tr><td colspan="4" class="text-center text-danger">' +
                '<i class="fas fa-exclamation-circle mr-2"></i>Error al cargar items</td></tr>'
            );
        });
    });
    
    // ============================================
    // MANEJO CENTRALIZADO DE FORMULARIOS MODALES
    // ============================================
    var isSubmitting = false;
    
    // Configuración de formularios
    var formConfigs = [
        {
            formId: '#formCreateAlmacen',
            modalId: '#createAlmacenModal',
            loadingText: 'Creando...',
            successMessage: 'Almacén creado exitosamente',
            errorMessage: 'Error al crear el almacén'
        },
        {
            formId: '#formCreateCategoriaInsumo',
            modalId: '#createCategoriaInsumoModal',
            loadingText: 'Creando...',
            successMessage: 'Categoría creada exitosamente',
            errorMessage: 'Error al crear la categoría'
        },
        {
            formId: '#formCreateInsumo',
            modalId: '#createInsumoModal',
            loadingText: 'Creando...',
            successMessage: 'Insumo creado exitosamente',
            errorMessage: 'Error al crear el insumo'
        },
        {
            formId: '#formCreateCategoriaProducto',
            modalId: '#createCategoriaProductoModal',
            loadingText: 'Creando...',
            successMessage: 'Categoría creada exitosamente',
            errorMessage: 'Error al crear la categoría'
        },
        {
            formId: '#formCreateProducto',
            modalId: '#createProductoModal',
            loadingText: 'Creando...',
            successMessage: 'Producto creado exitosamente',
            errorMessage: 'Error al crear el producto',
            isFileUpload: true
        },
        {
            formId: '#formManageStock',
            modalId: '#manageStockModal',
            loadingText: 'Procesando...',
            successMessage: 'Stock actualizado exitosamente',
            errorMessage: 'Error al gestionar stock'
        }
    ];
    
    // Inicializar cada formulario
    formConfigs.forEach(function(config) {
        var $form = $(config.formId);
        if (!$form.length) return;
        
        // Guardar texto original del botón
        var $submitBtn = $form.find('button[type="submit"]');
        var originalText = $submitBtn.html();
        $submitBtn.data('original-text', originalText);
        
        $form.off('submit').on('submit', function(e) {
            e.preventDefault();
            if (isSubmitting) return;
            isSubmitting = true;
            
            var $btn = $form.find('button[type="submit"]');
            $btn.html('<i class="fas fa-spinner fa-spin"></i> ' + config.loadingText).prop('disabled', true);
            
            var ajaxOptions = {
                url: $form.attr('action'),
                method: 'POST',
                data: config.isFileUpload ? new FormData(this) : $form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $(config.modalId).modal('hide');
                        toastr.success(response.message || config.successMessage);
                        $form[0].reset();
                        setTimeout(function() { location.reload(); }, 1500);
                    } else {
                        toastr.error(response.message || 'Error al procesar');
                        resetButton($btn, originalText);
                        isSubmitting = false;
                    }
                },
                error: function(xhr) {
                    var message = config.errorMessage;
                    if (xhr.responseJSON?.errors) {
                        message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    } else if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    }
                    toastr.error(message);
                    resetButton($btn, originalText);
                    isSubmitting = false;
                }
            };
            
            if (config.isFileUpload) {
                ajaxOptions.processData = false;
                ajaxOptions.contentType = false;
            }
            
            $.ajax(ajaxOptions);
        });
    });
    
    function resetButton($btn, originalText) {
        $btn.html(originalText).prop('disabled', false);
    }
    
    // Resetear flag al cerrar modales
    $('.modal').on('hidden.bs.modal', function() {
        isSubmitting = false;
        var $btn = $(this).find('button[type="submit"]');
        var originalText = $btn.data('original-text');
        if (originalText && $btn.prop('disabled')) {
            $btn.html(originalText).prop('disabled', false);
        }
    });
});
</script>
@endpush