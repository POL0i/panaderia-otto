<?php $__env->startSection('title', 'Inventario General'); ?>
<?php $__env->startSection('page-title', 'Inventario - Stock por Almacén'); ?>
<?php $__env->startSection('page-description', 'Gestión de stock por almacén'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ==========================================
       ESTILOS ESPECÍFICOS - STOCK POR ALMACÉN
       ========================================== */
    
    /* Badges de stock rápido */
    .stock-summary-badges .badge {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
        margin-right: 0.5rem;
    }
    
    /* Badge de stock en tabla */
    .badge-stock {
        font-size: 0.85rem;
        padding: 0.4rem 0.6rem;
        font-weight: 600;
    }
    
    /* Badges de tipo */
    .badge-tipo-producto { background: var(--badge-success); color: white; }
    .badge-tipo-insumo   { background: var(--badge-info); color: white; }
    
    /* Filtros */
    .filter-btn-group .btn {
        border-radius: 20px;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }
    .filter-btn-group .btn:hover { transform: translateY(-1px); }
    .filter-btn-group .btn.active { font-weight: 600; }
    
    /* Card header oscuro */
    .card-header-dark {
        background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
        color: var(--text-on-primary);
    }
    .card-header-dark .card-title { color: var(--text-on-primary); }
    
    /* Footer de filtros */
    .filter-footer {
        background: var(--color-bg-lighter);
        border-top: 1px solid var(--color-border);
        font-size: 0.85rem;
    }
    
    /* Almacén info */
    .almacen-nombre { color: var(--color-primary-dark); }
    .almacen-ubicacion { color: var(--text-muted); font-size: 0.85rem; }
    
    /* Empty state */
    .empty-state-icon {
        font-size: 3rem;
        color: var(--text-muted);
        display: block;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card stat-card-accent">
                <div class="stat-number"><?php echo e($totalItems); ?></div>
                <div class="stat-label">
                    <i class="fas fa-boxes mr-2"></i>Total Items
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-success">
                <div class="stat-number"><?php echo e($totalProductos); ?></div>
                <div class="stat-label">
                    <i class="fas fa-box mr-2"></i>Productos
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-info">
                <div class="stat-number"><?php echo e($totalInsumos); ?></div>
                <div class="stat-label">
                    <i class="fas fa-flask mr-2"></i>Insumos
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-number"><?php echo e($almacenItems->total()); ?></div>
                <div class="stat-label">
                    <i class="fas fa-warehouse mr-2"></i>Registros Stock
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mb-3">
        <div class="col-12">
            <div class="stock-summary-badges">
                <span class="badge badge-info">
                    <i class="fas fa-flask mr-1"></i> Stock Insumos: <?php echo e($totalInsumoStock); ?>

                </span>
                <span class="badge badge-success">
                    <i class="fas fa-box mr-1"></i> Stock Productos: <?php echo e($totalProductoStock); ?>

                </span>
                <span class="badge badge-warning">
                    <i class="fas fa-arrows-alt-h mr-1"></i> Stock Mixto: <?php echo e($totalMixtoStock); ?>

                </span>
            </div>
        </div>
    </div>

    
    <div class="card filter-card mb-3">
        <div class="card-body py-2">
            <form method="GET" action="<?php echo e(route('almacen-items.index')); ?>">
                
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="filter-btn-group btn-group-sm d-flex">
                            <a href="<?php echo e(route('almacen-items.index', ['tipo' => 'todos', 'buscar' => $buscar, 'orden' => $orden])); ?>" 
                               class="btn btn-outline-secondary flex-fill <?php echo e($filtroTipo == 'todos' ? 'active' : ''); ?>">
                                <i class="fas fa-list mr-1"></i> Todos
                            </a>
                            <a href="<?php echo e(route('almacen-items.index', ['tipo' => 'insumo', 'buscar' => $buscar, 'orden' => $orden])); ?>" 
                               class="btn btn-outline-info flex-fill <?php echo e($filtroTipo == 'insumo' ? 'active' : ''); ?>">
                                <i class="fas fa-flask mr-1"></i> Insumo
                            </a>
                            <a href="<?php echo e(route('almacen-items.index', ['tipo' => 'producto', 'buscar' => $buscar, 'orden' => $orden])); ?>" 
                               class="btn btn-outline-success flex-fill <?php echo e($filtroTipo == 'producto' ? 'active' : ''); ?>">
                                <i class="fas fa-box mr-1"></i> Producto
                            </a>
                            <a href="<?php echo e(route('almacen-items.index', ['tipo' => 'mixto', 'buscar' => $buscar, 'orden' => $orden])); ?>" 
                               class="btn btn-outline-warning flex-fill <?php echo e($filtroTipo == 'mixto' ? 'active' : ''); ?>">
                                <i class="fas fa-arrows-alt-h mr-1"></i> Mixto
                            </a>
                        </div>
                    </div>
                </div>
                
                
                <div class="row">
                    <div class="col-md-3 mb-2 mb-md-0">
                        <select name="orden" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="almacen" <?php echo e($orden == 'almacen' ? 'selected' : ''); ?>>📦 Por Almacén</option>
                            <option value="item" <?php echo e($orden == 'item' ? 'selected' : ''); ?>>📋 Por Item</option>
                            <option value="stock" <?php echo e($orden == 'stock' ? 'selected' : ''); ?>>📊 Por Stock</option>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <div class="input-group input-group-sm">
                            <input type="hidden" name="tipo" value="<?php echo e($filtroTipo); ?>">
                            <input type="text" name="buscar" class="form-control" 
                                   placeholder="Buscar almacén o item..." value="<?php echo e($buscar); ?>">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                <?php if($buscar || $filtroTipo != 'todos' || $orden != 'almacen'): ?>
                                    <a href="<?php echo e(route('almacen-items.index')); ?>" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Limpiar
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer filter-footer py-1">
            <small>
                <i class="fas fa-filter mr-1"></i> 
                Filtro: <strong><?php echo e($filtroTipo == 'todos' ? 'Todos' : ucfirst($filtroTipo)); ?></strong>
                <?php if($buscar): ?>
                    | Búsqueda: <strong>"<?php echo e($buscar); ?>"</strong>
                <?php endif; ?>
                | Orden: <strong><?php echo e($orden == 'almacen' ? 'Almacén' : ($orden == 'item' ? 'Item' : 'Stock')); ?></strong>
                | Resultados: <strong><?php echo e($almacenItems->total()); ?></strong>
            </small>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header card-header-dark">
            <h3 class="card-title">
                <i class="fas fa-warehouse mr-2"></i> Stock por Almacén
            </h3>
            <div class="card-tools">
                <a href="<?php echo e(route('almacen-items.create')); ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-plus mr-1"></i> Agregar Stock
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Almacén</th>
                            <th>Tipo</th>
                            <th>Item</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $almacenItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong class="almacen-nombre"><?php echo e($ai->almacen->nombre); ?></strong>
                                <?php if($ai->almacen->ubicacion): ?>
                                    <br><small class="almacen-ubicacion"><?php echo e($ai->almacen->ubicacion); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo e($ai->item->tipo_item === 'producto' ? 'badge-tipo-producto' : 'badge-tipo-insumo'); ?>">
                                    <?php echo e(ucfirst($ai->item->tipo_item)); ?>

                                </span>
                            </td>
                            <td><?php echo e($ai->item->nombre); ?></td>
                            <td>
                                <span class="badge badge-stock" style="background: var(--color-primary-dark); color: var(--text-on-primary);">
                                    <?php echo e($ai->stock); ?> <?php echo e($ai->item->unidad_medida); ?>

                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('almacen-items.edit', [$ai->id_almacen, $ai->id_item])); ?>" 
                                       class="btn btn-outline-primary" title="Editar stock">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('almacen-items.destroy', [$ai->id_almacen, $ai->id_item])); ?>" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Eliminar este stock?');">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-outline-danger" title="Eliminar stock">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-warehouse empty-state-icon d-block"></i>
                                <p class="text-muted">No hay stock registrado con los filtros actuales</p>
                                <a href="<?php echo e(route('almacen-items.create')); ?>" class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-plus mr-1"></i> Agregar primer stock
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($almacenItems->hasPages()): ?>
            <div class="card-footer">
                <div class="float-right"><?php echo e($almacenItems->links()); ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/almacen-item/index.blade.php ENDPATH**/ ?>