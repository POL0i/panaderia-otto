

<?php $__env->startSection('title', 'Módulo de Almacén - Panadería Otto'); ?>
<?php $__env->startSection('page-title', 'Panel de Almacén'); ?>
<?php $__env->startSection('page-description', 'Gestión de almacenes, productos, insumos y stock'); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number"><?php echo e($totalAlmacenes ?? 0); ?></div>
                <div class="stats-label">Almacenes</div>
                <i class="fas fa-warehouse float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #4A6FA5 0%, #2E4A7A 100%);">
                <div class="stats-number"><?php echo e($totalProductos ?? 0); ?></div>
                <div class="stats-label">Productos</div>
                <i class="fas fa-box float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #A58B4A 0%, #7A6B2E 100%);">
                <div class="stats-number"><?php echo e($totalInsumos ?? 0); ?></div>
                <div class="stats-label">Insumos</div>
                <i class="fas fa-flask float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #6A4A8A 0%, #4A2E6A 100%);">
                <div class="stats-number"><?php echo e($totalItems ?? 0); ?></div>
                <div class="stats-label">Items Totales</div>
                <i class="fas fa-cubes float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    
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
                                <?php $__currentLoopData = $almacenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $almacen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="#" class="list-group-item list-group-item-action almacen-item" data-id="<?php echo e($almacen->id_almacen); ?>">
                                        <i class="fas fa-warehouse"></i> <?php echo e($almacen->nombre); ?>

                                        <span class="badge badge-primary float-right"><?php echo e($almacen->items_count ?? 0); ?></span>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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


<?php echo $__env->make('modulo-almacen.partials.modal-almacen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('modulo-almacen.partials.modal-categoria-insumo', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('modulo-almacen.partials.modal-insumo', ['categorias' => $categoriasInsumo], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('modulo-almacen.partials.modal-categoria-producto', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('modulo-almacen.partials.modal-producto', ['categorias' => $categoriasProducto], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('modulo-almacen.partials.modal-stock', ['almacenes' => $almacenes, 'items' => $items], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Cargar items de almacén al hacer clic
      $('.almacen-item').on('click', function(e) {
        e.preventDefault();
        var almacenId = $(this).data('id');
        
        $('.almacen-item').removeClass('active');
        $(this).addClass('active');
        
        $.get('/modulo-almacen/' + almacenId + '/items', function(response) {
            var html = '';
            if (response.items && response.items.length > 0) {
                response.items.forEach(function(item) {
                    var nombreItem = item.item_nombre || item.nombre || 'N/A';
                    var tipoItem = item.tipo_item || item.tipo || 'N/A';
                    var tipoBadge = tipoItem === 'producto' ? 'success' : 'warning';
                    var tipoTexto = tipoItem === 'producto' ? 'Producto' : (tipoItem === 'insumo' ? 'Insumo' : tipoItem);
                    
                    html += '<tr>';
                    html += '<td>' + nombreItem + '</td>';
                    html += '<td><span class="badge badge-' + tipoBadge + '">' + tipoTexto + '</span></td>';
                    html += '<td>' + (item.stock || 0) + '</td>';
                    html += '<td>' + (item.unidad_medida || 'unidad') + '</td>';
                    html += '</tr>';
                });
            } else {
                html = '<tr><td colspan="4" class="text-center text-muted">Este almacén no tiene items</td></tr>';
            }
            $('#itemsAlmacenBody').html(html);
        }).fail(function(xhr) {
            console.error('Error:', xhr.responseText);
            $('#itemsAlmacenBody').html('<tr><td colspan="4" class="text-center text-danger">Error al cargar items</td></tr>');
        });
    });
    
    // ============================================
    // MANEJO CENTRALIZADO DE FORMULARIOS MODALES
    // ============================================
    
    // Evitar envíos múltiples
    var isSubmitting = false;
    
    // Formulario: Crear Almacén
    $('#formCreateAlmacen').off('submit').on('submit', function(e) {
        e.preventDefault();
        if (isSubmitting) return;
        isSubmitting = true;
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Creando...').prop('disabled', true);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createAlmacenModal').modal('hide');
                    toastr.success(response.message);
                    form[0].reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(response.message || 'Error al crear');
                    submitBtn.html(originalText).prop('disabled', false);
                    isSubmitting = false;
                }
            },
            error: function(xhr) {
                var message = 'Error al crear el almacén';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
                submitBtn.html(originalText).prop('disabled', false);
                isSubmitting = false;
            }
        });
    });
    
    // Formulario: Crear Categoría Insumo
    $('#formCreateCategoriaInsumo').off('submit').on('submit', function(e) {
        e.preventDefault();
        if (isSubmitting) return;
        isSubmitting = true;
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Creando...').prop('disabled', true);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createCategoriaInsumoModal').modal('hide');
                    toastr.success(response.message);
                    form[0].reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(response.message || 'Error al crear');
                    submitBtn.html(originalText).prop('disabled', false);
                    isSubmitting = false;
                }
            },
            error: function(xhr) {
                var message = 'Error al crear la categoría';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
                submitBtn.html(originalText).prop('disabled', false);
                isSubmitting = false;
            }
        });
    });
    
    // Formulario: Crear Insumo
    $('#formCreateInsumo').off('submit').on('submit', function(e) {
        e.preventDefault();
        if (isSubmitting) return;
        isSubmitting = true;
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Creando...').prop('disabled', true);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createInsumoModal').modal('hide');
                    toastr.success(response.message);
                    form[0].reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(response.message || 'Error al crear');
                    submitBtn.html(originalText).prop('disabled', false);
                    isSubmitting = false;
                }
            },
            error: function(xhr) {
                var message = 'Error al crear el insumo';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
                submitBtn.html(originalText).prop('disabled', false);
                isSubmitting = false;
            }
        });
    });
    
    // Formulario: Crear Categoría Producto
    $('#formCreateCategoriaProducto').off('submit').on('submit', function(e) {
        e.preventDefault();
        if (isSubmitting) return;
        isSubmitting = true;
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Creando...').prop('disabled', true);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createCategoriaProductoModal').modal('hide');
                    toastr.success(response.message);
                    form[0].reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(response.message || 'Error al crear');
                    submitBtn.html(originalText).prop('disabled', false);
                    isSubmitting = false;
                }
            },
            error: function(xhr) {
                var message = 'Error al crear la categoría';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
                submitBtn.html(originalText).prop('disabled', false);
                isSubmitting = false;
            }
        });
    });
    
    // Formulario: Crear Producto
    $('#formCreateProducto').off('submit').on('submit', function(e) {
        e.preventDefault();
        if (isSubmitting) return;
        isSubmitting = true;
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Creando...').prop('disabled', true);
        
        var formData = new FormData(this);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#createProductoModal').modal('hide');
                    toastr.success(response.message);
                    form[0].reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(response.message || 'Error al crear');
                    submitBtn.html(originalText).prop('disabled', false);
                    isSubmitting = false;
                }
            },
            error: function(xhr) {
                var message = 'Error al crear el producto';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
                submitBtn.html(originalText).prop('disabled', false);
                isSubmitting = false;
            }
        });
    });
    
    // Formulario: Gestionar Stock
    $('#formManageStock').off('submit').on('submit', function(e) {
        e.preventDefault();
        if (isSubmitting) return;
        isSubmitting = true;
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Procesando...').prop('disabled', true);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#manageStockModal').modal('hide');
                    toastr.success(response.message);
                    form[0].reset();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(response.message || 'Error al procesar');
                    submitBtn.html(originalText).prop('disabled', false);
                    isSubmitting = false;
                }
            },
            error: function(xhr) {
                var message = 'Error al gestionar stock';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
                submitBtn.html(originalText).prop('disabled', false);
                isSubmitting = false;
            }
        });
    });
    
    // Resetear flag al cerrar modales
    $('.modal').on('hidden.bs.modal', function() {
        isSubmitting = false;
        // Resetear botones si es necesario
        $(this).find('button[type="submit"]').html(function() {
            var originalText = $(this).data('original-text');
            if (originalText) {
                return originalText;
            }
            return $(this).html();
        }).prop('disabled', false);
    });
    
    // Guardar texto original de botones
    $('form button[type="submit"]').each(function() {
        $(this).data('original-text', $(this).html());
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/modulo-almacen/index.blade.php ENDPATH**/ ?>