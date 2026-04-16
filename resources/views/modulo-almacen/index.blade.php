@extends('layouts.adminlte')

@section('title', 'Módulo de Almacén - Panadería Otto')
@section('page-title', 'Panel de Almacén')
@section('page-description', 'Gestión de almacenes, productos, insumos y stock')

@push('styles')
<style>
    .stats-card {
        background: linear-gradient(135deg, #2E5D3A 0%, #1A3D2A 100%);
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
    .module-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 15px;
        height: 100%;
    }
    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .table-almacen-items {
        max-height: 300px;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    {{-- Estadísticas --}}
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $totalAlmacenes ?? 0 }}</div>
                <div class="stats-label">Almacenes</div>
                <i class="fas fa-warehouse float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #4A6FA5 0%, #2E4A7A 100%);">
                <div class="stats-number">{{ $totalProductos ?? 0 }}</div>
                <div class="stats-label">Productos</div>
                <i class="fas fa-box float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #A58B4A 0%, #7A6B2E 100%);">
                <div class="stats-number">{{ $totalInsumos ?? 0 }}</div>
                <div class="stats-label">Insumos</div>
                <i class="fas fa-flask float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #6A4A8A 0%, #4A2E6A 100%);">
                <div class="stats-number">{{ $totalItems ?? 0 }}</div>
                <div class="stats-label">Items Totales</div>
                <i class="fas fa-cubes float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    {{-- Acciones rápidas --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background: #E8F0E8; border-bottom: 2px solid #2E5D3A;">
                    <h5 class="mb-0" style="color: #1A3D2A;">
                        <i class="fas fa-bolt"></i> Acciones Rápidas - Crear Nuevo
                    </h5>
                </div>
                <div class="card-body text-center">
                    <button class="btn btn-success quick-action-btn" data-toggle="modal" data-target="#createAlmacenModal">
                        <i class="fas fa-warehouse"></i> Nuevo Almacén
                    </button>
                    <button class="btn btn-primary quick-action-btn" data-toggle="modal" data-target="#createProductoModal">
                        <i class="fas fa-box"></i> Nuevo Producto
                    </button>
                    <button class="btn btn-secondary quick-action-btn" data-toggle="modal" data-target="#createInsumoModal">
                        <i class="fas fa-flask"></i> Nuevo Insumo
                    </button>
                    <button class="btn btn-danger quick-action-btn" data-toggle="modal" data-target="#manageStockModal">
                        <i class="fas fa-boxes"></i> Gestionar Stock
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Lista de Almacenes y su Stock --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-warehouse"></i> Almacenes y su Inventario
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="list-group" id="listaAlmacenes">
                                @foreach($almacenes as $almacen)
                                    <a href="#" class="list-group-item list-group-item-action almacen-item" data-id="{{ $almacen->id_almacen }}">
                                        <i class="fas fa-warehouse"></i> {{ $almacen->nombre }}
                                        <span class="badge badge-primary float-right">{{ $almacen->items_count ?? 0 }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
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
    // Cargar items de almacén al hacer clic
    $('.almacen-item').on('click', function(e) {
        e.preventDefault();
        var almacenId = $(this).data('id');
        var almacenNombre = $(this).text().trim();
        
        $('.almacen-item').removeClass('active');
        $(this).addClass('active');
        
        $.get('/modulo-almacen/' + almacenId + '/items', function(response) {
            var html = '';
            if (response.items.length > 0) {
                response.items.forEach(function(item) {
                    html += '<tr>';
                    html += '<td>' + item.nombre + '</td>';
                    html += '<td><span class="badge badge-' + (item.tipo === 'producto' ? 'success' : 'warning') + '">' + item.tipo + '</span></td>';
                    html += '<td>' + item.stock + '</td>';
                    html += '<td>' + item.unidad + '</td>';
                    html += '</tr>';
                });
            } else {
                html = '<tr><td colspan="4" class="text-center text-muted">Este almacén no tiene items</td></tr>';
            }
            $('#itemsAlmacenBody').html(html);
        });
    });
    
    // Funciones genéricas para enviar formularios modales
    function submitModalForm(formId, successCallback) {
        $('#' + formId).on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('.modal').modal('hide');
                        alert(response.message);
                        form[0].reset();
                        setTimeout(() => location.reload(), 1000);
                    }
                },
                error: function(xhr) {
                    var msg = 'Error';
                    if (xhr.responseJSON?.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    } else if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    }
                    alert(msg);
                }
            });
        });
    }
    
    submitModalForm('formCreateAlmacen');
    submitModalForm('formCreateCategoriaInsumo');
    submitModalForm('formCreateInsumo');
    submitModalForm('formCreateCategoriaProducto');
    submitModalForm('formCreateProducto');
    submitModalForm('formManageStock');
    
    // Auto-recargar categorías en select al crear una nueva (usando los modales de categoría)
    // Se puede implementar con un callback que refresque los <select> vía AJAX, 
    // pero por simplicidad recargamos la página después de crear categoría.
});
</script>
@endpush