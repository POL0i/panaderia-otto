<?php $__env->startSection('title', 'Inventario Unificado'); ?>
<?php $__env->startSection('page-title', 'Gestión de Inventario'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number"><?php echo e($totalItems); ?></div>
                <div class="stat-label">
                    <i class="fas fa-boxes mr-2"></i>Total Items
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="border-left-color: #28a745;">
                <div class="stat-number"><?php echo e($totalProductos); ?></div>
                <div class="stat-label">
                    <i class="fas fa-box mr-2"></i>Productos
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="border-left-color: #17a2b8;">
                <div class="stat-number"><?php echo e($totalInsumos); ?></div>
                <div class="stat-label">
                    <i class="fas fa-flask mr-2"></i>Insumos
                </div>
            </div>
        </div>
    </div>

    
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" action="<?php echo e(route('items.index')); ?>" id="filtrosForm">
                <div class="row align-items-center">
                    
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="btn-group btn-group-sm w-100">
                            <a href="<?php echo e(route('items.index', ['filtro' => 'todos', 'buscar' => $buscar, 'categoria' => $categoria])); ?>" 
                               class="btn btn-outline-secondary flex-fill <?php echo e($filtro == 'todos' ? 'active' : ''); ?>">
                                <i class="fas fa-list mr-1"></i> Todos
                            </a>
                            <a href="<?php echo e(route('items.index', ['filtro' => 'productos', 'buscar' => $buscar, 'categoria' => $categoria])); ?>" 
                               class="btn btn-outline-success flex-fill <?php echo e($filtro == 'productos' ? 'active' : ''); ?>">
                                <i class="fas fa-box mr-1"></i> Productos
                            </a>
                            <a href="<?php echo e(route('items.index', ['filtro' => 'insumos', 'buscar' => $buscar, 'categoria' => $categoria])); ?>" 
                               class="btn btn-outline-info flex-fill <?php echo e($filtro == 'insumos' ? 'active' : ''); ?>">
                                <i class="fas fa-flask mr-1"></i> Insumos
                            </a>
                        </div>
                    </div>
                    
                    
                    <div class="col-md-3 mb-2 mb-md-0">
                        <select name="categoria" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">Todas las categorías</option>
                            <?php if($filtro == 'productos' || $filtro == 'todos'): ?>
                                <optgroup label="── Productos ──">
                                    <?php $__currentLoopData = $categoriasProductos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cat->id_cat_producto); ?>" <?php echo e($categoria == $cat->id_cat_producto ? 'selected' : ''); ?>>
                                            <?php echo e($cat->nombre); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </optgroup>
                            <?php endif; ?>
                            <?php if($filtro == 'insumos' || $filtro == 'todos'): ?>
                                <optgroup label="── Insumos ──">
                                    <?php $__currentLoopData = $categoriasInsumos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cat->id_cat_insumo); ?>" <?php echo e($categoria == $cat->id_cat_insumo ? 'selected' : ''); ?>>
                                            <?php echo e($cat->nombre); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </optgroup>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    
                    <div class="col-md-5">
                        <div class="input-group input-group-sm">
                            <input type="hidden" name="filtro" value="<?php echo e($filtro); ?>">
                            <input type="text" name="buscar" class="form-control" 
                                   placeholder="Buscar por nombre..." value="<?php echo e($buscar); ?>">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                <?php if($buscar || $categoria): ?>
                                    <a href="<?php echo e(route('items.index')); ?>" class="btn btn-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header bg-gradient-dark">
            <h3 class="card-title text-white">
                <i class="fas fa-boxes mr-2"></i>
                Listado de Items (<?php echo e($items->total()); ?>)
            </h3>
            <div class="card-tools">
                <a href="<?php echo e(route('items.create')); ?>" class="btn btn-success btn-sm">
                    <i class="fas fa-plus mr-1"></i> Nuevo Item
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th style="width: 8%">Imagen</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Categoría</th>
                            <th>Unidad</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($item->id_item); ?></td>
                                <td class="text-center">
                                    <?php if($item->tipo_item === 'producto' && $item->producto && $item->producto->imagen): ?>
                                        <?php
                                            $imagen = $item->producto->imagen;
                                            $esUrl = Str::startsWith($imagen, ['http://', 'https://']);
                                            $src = $esUrl ? $imagen : asset('storage/' . $imagen);
                                        ?>
                                        <img src="<?php echo e($src); ?>" class="img-thumbnail" 
                                             style="width: 45px; height: 45px; object-fit: cover;"
                                             onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-box text-muted\'></i>'">
                                    <?php elseif($item->tipo_item === 'producto'): ?>
                                        <i class="fas fa-box text-muted fa-lg"></i>
                                    <?php else: ?>
                                        <i class="fas fa-flask text-info fa-lg"></i>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo e($item->nombre); ?></strong></td>
                                <td>
                                    <?php if($item->tipo_item === 'producto'): ?>
                                        <span class="badge badge-success">Producto</span>
                                    <?php else: ?>
                                        <span class="badge badge-info">Insumo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($item->tipo_item === 'producto' && $item->producto && $item->producto->categoria): ?>
                                        <?php echo e($item->producto->categoria->nombre); ?>

                                    <?php elseif($item->tipo_item === 'insumo' && $item->insumo && $item->insumo->categoria): ?>
                                        <?php echo e($item->insumo->categoria->nombre); ?>

                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge badge-secondary"><?php echo e($item->unidad_medida); ?></span></td>
                                <td>
                                    <?php if($item->tipo_item === 'producto' && $item->producto): ?>
                                        <strong class="text-success">$<?php echo e(number_format($item->producto->precio, 2)); ?></strong>
                                    <?php elseif($item->insumo && $item->insumo->precio_compra): ?>
                                        <small>$<?php echo e(number_format($item->insumo->precio_compra, 2)); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo e(route('items.show', $item->id_item)); ?>" class="btn btn-outline-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('items.edit', $item->id_item)); ?>" class="btn btn-outline-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('items.destroy', $item->id_item)); ?>" method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Eliminar este item?');">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted">No se encontraron items</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($items->hasPages()): ?>
            <div class="card-footer clearfix">
                <div class="float-right"><?php echo e($items->links()); ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Estadísticas estilo stat-card (igual que personas) */
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgb(85, 70, 4);
        border-left: 4px solid #602c07; /* gris por defecto para Total */
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #673a07;
        line-height: 1.2;
    }
    
    .stat-label {
        color: #522b0b;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 0.25rem;
    }
    
    .img-thumbnail {
        padding: 2px;
        border-radius: 4px;
    }
    
    .btn-group-sm .btn {
        margin-right: 2px;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/item/index.blade.php ENDPATH**/ ?>