<?php $__env->startSection('title', 'Reportes - Panadería Otto'); ?>
<?php $__env->startSection('page-title', 'Reportes'); ?>
<?php $__env->startSection('page-description', 'Análisis de gestión comercial, inventario y producción'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ==========================================
       ESTILOS ESPECÍFICOS - REPORTES INDEX
       ========================================== */
    
    /* Cards de acceso a reportes */
    .report-card {
        transition: all 0.3s ease;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        height: 100%;
        cursor: pointer;
        border: none;
    }
    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    .report-card .card-body {
        padding: 2.5rem 1.5rem;
        color: var(--text-on-primary);
    }
    .report-card .report-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.8;
        display: block;
    }
    
    /* Variantes de report cards */
    .report-card-comercial .card-body {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
    }
    .report-card-inventario .card-body {
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-secondary) 100%);
        color: var(--color-primary-dark);
    }
    .report-card-produccion .card-body {
        background: linear-gradient(135deg, var(--color-primary-light) 0%, var(--color-primary-dark) 100%);
    }
    
    /* Mini estadísticas */
    .mini-stat {
        border-radius: var(--border-radius-sm);
        padding: 12px;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        transition: background 0.2s ease;
    }
    .mini-stat:hover {
        filter: brightness(0.95);
    }
    .mini-stat-ingreso { background: rgba(0, 123, 255, 0.06); }
    .mini-stat-egreso  { background: rgba(220, 53, 69, 0.06); }
    .mini-stat-venta   { background: rgba(40, 167, 69, 0.06); }
    .mini-stat-produccion { background: rgba(160, 82, 45, 0.06); }
    
    /* Badges de movimiento */
    .badge-movimiento-ingreso { background: var(--badge-info); color: white; }
    .badge-movimiento-egreso  { background: var(--badge-danger); color: white; }
    
    /* Card header para secciones */
    .card-header-report {
        color: var(--text-on-primary);
    }
    .card-header-report-comercial {
        background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
    }
    .card-header-report-ventas {
        background: linear-gradient(135deg, var(--badge-success) 0%, color-mix(in srgb, var(--badge-success) 70%, black) 100%);
    }
    .card-header-report-produccion {
        background: linear-gradient(135deg, var(--color-primary-light) 0%, var(--color-primary) 100%);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <div class="row">
        
        <div class="col-md-4 mb-4">
            <div class="card report-card report-card-comercial" onclick="window.location='<?php echo e(route('reportes.comercial')); ?>'">
                <div class="card-body text-center">
                    <i class="fas fa-chart-bar report-icon"></i>
                    <h5><i class="fas fa-shopping-cart mr-2"></i>Gestión Comercial</h5>
                    <p class="mb-0 small">Ventas y compras por fechas</p>
                </div>
            </div>
        </div>

        
        <div class="col-md-4 mb-4">
            <div class="card report-card report-card-inventario" onclick="window.location='<?php echo e(route('reportes.inventario')); ?>'">
                <div class="card-body text-center">
                    <i class="fas fa-boxes report-icon"></i>
                    <h5><i class="fas fa-warehouse mr-2"></i>Inventario</h5>
                    <p class="mb-0 small">Stock, caducidad y movimientos</p>
                </div>
            </div>
        </div>

        
        <div class="col-md-4 mb-4">
            <div class="card report-card report-card-produccion" onclick="window.location='<?php echo e(route('reportes.produccion')); ?>'">
                <div class="card-body text-center">
                    <i class="fas fa-industry report-icon"></i>
                    <h5><i class="fas fa-cogs mr-2"></i>Producción</h5>
                    <p class="mb-0 small">Órdenes por fechas y totales</p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header card-header-report card-header-report-comercial">
                    <h6 class="mb-0"><i class="fas fa-exchange-alt mr-2"></i> Últimos Movimientos</h6>
                </div>
                <div class="card-body p-2">
                    <?php
                        $ultimosMovimientos = \App\Models\MovimientoInventario::orderBy('id_movimiento', 'desc')
                            ->limit(7)
                            ->get();
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $ultimosMovimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="mini-stat <?php echo e($mov->tipo_movimiento == 'ingreso' ? 'mini-stat-ingreso' : 'mini-stat-egreso'); ?>">
                            <div>
                                <strong><?php echo e(\App\Models\Item::find($mov->id_item)->nombre ?? 'Item #'.$mov->id_item); ?></strong>
                                <br><small class="text-muted"><?php echo e(\Carbon\Carbon::parse($mov->fecha_movimiento)->format('d/m H:i')); ?></small>
                            </div>
                            <span class="badge <?php echo e($mov->tipo_movimiento == 'ingreso' ? 'badge-movimiento-ingreso' : 'badge-movimiento-egreso'); ?>">
                                <?php echo e($mov->tipo_movimiento == 'ingreso' ? '+' : '-'); ?><?php echo e($mov->cantidad); ?>

                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted text-center py-3">Sin movimientos</p>
                    <?php endif; ?>
                    <a href="<?php echo e(route('reportes.inventario')); ?>" class="btn btn-sm btn-outline-primary btn-block mt-2">
                        <i class="fas fa-arrow-right mr-1"></i> Ver inventario completo
                    </a>
                </div>
            </div>
        </div>

        
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header card-header-report card-header-report-ventas">
                    <h6 class="mb-0"><i class="fas fa-calendar-week mr-2"></i> Ventas Últimos 7 Días</h6>
                </div>
                <div class="card-body p-2">
                    <?php
                        $ventas7dias = \App\Models\NotaVenta::where('estado', 'completado')
                            ->whereDate('fecha_venta', '>=', now()->subDays(7))
                            ->orderBy('fecha_venta', 'desc')
                            ->limit(7)
                            ->get();
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $ventas7dias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="mini-stat mini-stat-venta">
                            <div>
                                <strong><?php echo e($venta->cliente->nombre ?? 'Cliente'); ?></strong>
                                <br><small class="text-muted"><?php echo e($venta->fecha_venta->format('d/m/Y')); ?></small>
                            </div>
                            <span class="text-precio-venta font-weight-bold">Bs. <?php echo e(number_format($venta->monto_total, 2)); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted text-center py-3">Sin ventas recientes</p>
                    <?php endif; ?>
                    <a href="<?php echo e(route('reportes.comercial')); ?>" class="btn btn-sm btn-outline-success btn-block mt-2">
                        <i class="fas fa-arrow-right mr-1"></i> Ver reporte comercial
                    </a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header card-header-report card-header-report-produccion">
                    <h6 class="mb-0"><i class="fas fa-industry mr-2"></i> Últimas Producciones</h6>
                </div>
                <div class="card-body p-2">
                    <?php
                        $ultimasProducciones = \App\Models\Produccion::with('detalles.item', 'empleadoSolicita')
                            ->orderBy('id_produccion', 'desc')
                            ->limit(5)
                            ->get();
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $ultimasProducciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="mini-stat mini-stat-produccion">
                            <div>
                                <strong>#<?php echo e($prod->id_produccion); ?> - <?php echo e($prod->cantidad_producida); ?> unidades</strong>
                                <br><small class="text-muted">
                                    <?php echo e($prod->fecha_produccion->format('d/m/Y')); ?> | 
                                    <?php echo e($prod->empleadoSolicita->nombre ?? 'N/A'); ?> |
                                    <span class="badge <?php echo e($prod->estado == 'aprobado' ? 'badge-tipo-producto' : ($prod->estado == 'pendiente' ? 'badge badge-warning' : 'badge badge-secondary')); ?>">
                                        <?php echo e($prod->estado); ?>

                                    </span>
                                </small>
                            </div>
                            <small class="text-muted">
                                <?php $__currentLoopData = $prod->detalles->where('tipo_movimiento', 'egreso')->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($d->item->nombre ?? 'Item'); ?>: <?php echo e($d->cantidad); ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </small>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted text-center py-3">Sin producciones recientes</p>
                    <?php endif; ?>
                    <a href="<?php echo e(route('reportes.produccion')); ?>" class="btn btn-sm btn-outline-secondary btn-block mt-2">
                        <i class="fas fa-arrow-right mr-1"></i> Ver producción completa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/reportes/index.blade.php ENDPATH**/ ?>