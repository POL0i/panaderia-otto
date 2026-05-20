<?php $__env->startSection('title', 'Módulo de Almacén - Panadería Otto'); ?>
<?php $__env->startSection('page-title', 'Panel de Almacén'); ?>
<?php $__env->startSection('page-description', 'Gestión de almacenes, productos, insumos y stock'); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <div class="row">
        
        <div class="col-md-3">
            <div class="stats-card stats-card-primary">
                <div class="stats-number"><?php echo e($totalAlmacenes ?? 0); ?></div>
                <div class="stats-label">Almacenes</div>
                <i class="fas fa-warehouse stats-icon"></i>
            </div>
        </div>
        
        
        <div class="col-md-3">
            <div class="stats-card stats-card-info">
                <div class="stats-number"><?php echo e($totalProductos ?? 0); ?></div>
                <div class="stats-label">Productos</div>
                <i class="fas fa-box stats-icon"></i>
            </div>
        </div>
        
        
        <div class="col-md-3">
            <div class="stats-card stats-card-accent">
                <div class="stats-number"><?php echo e($totalInsumos ?? 0); ?></div>
                <div class="stats-label">Insumos</div>
                <i class="fas fa-flask stats-icon"></i>
            </div>
        </div>
        
        
        <div class="col-md-3">
            <div class="stats-card stats-card-secondary">
                <div class="stats-number"><?php echo e($totalItems ?? 0); ?></div>
                <div class="stats-label">Items Totales</div>
                <i class="fas fa-cubes stats-icon"></i>
            </div>
        </div>
    </div>

    
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
                        
                        <div class="col-md-4">
                            <div class="list-group" id="listaAlmacenes">
                                <?php $__currentLoopData = $almacenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $almacen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="#" 
                                       class="list-group-item list-group-item-action almacen-list-item" 
                                       data-id="<?php echo e($almacen->id_almacen); ?>">
                                        <i class="fas fa-warehouse mr-2"></i> <?php echo e($almacen->nombre); ?>

                                        <span class="badge badge-primary float-right">
                                            <?php echo e($almacen->items_count ?? 0); ?>

                                        </span>
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

    // ============================================
    // CARGAR ITEMS DE ALMACÉN
    // ============================================
    $('.almacen-list-item').on('click', function(e) {
        e.preventDefault();
        var almacenId = $(this).data('id');
        
        $('.almacen-list-item').removeClass('active');
        $(this).addClass('active');
        
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
        }).fail(function() {
            $('#itemsAlmacenBody').html(
                '<tr><td colspan="4" class="text-center text-danger">' +
                '<i class="fas fa-exclamation-circle mr-2"></i>Error al cargar items</td></tr>'
            );
        });
    });

});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/modulo-almacen/index.blade.php ENDPATH**/ ?>