<?php $__env->startSection('title', 'Reporte Comercial - Panadería Otto'); ?>
<?php $__env->startSection('page-title', 'Gestión Comercial'); ?>
<?php $__env->startSection('page-description', 'Ventas y compras por rango de fechas'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ==========================================
       ESTILOS ESPECÍFICOS - REPORTE COMERCIAL
       ========================================== */
    
    .report-card {
        border-radius: var(--border-radius-lg);
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    /* Chart */
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    /* Tabs */
    .card-header-tabs .nav-link {
        color: var(--text-muted);
        border: none;
        transition: all 0.2s ease;
    }
    .card-header-tabs .nav-link:hover {
        color: var(--color-primary);
        border: none;
    }
    .card-header-tabs .nav-link.active {
        color: var(--color-primary-dark);
        font-weight: 600;
        border-bottom: 2px solid var(--color-primary);
        background: transparent;
    }
    
    /* Tablas con scroll */
    .table-scroll {
        max-height: 400px;
        overflow-y: auto;
    }
    .table-scroll::-webkit-scrollbar { width: 6px; }
    .table-scroll::-webkit-scrollbar-track { background: var(--color-bg-light); border-radius: 3px; }
    .table-scroll::-webkit-scrollbar-thumb { background: var(--color-border); border-radius: 3px; }
    
    /* Monto en tabla */
    .text-monto-venta  { color: var(--badge-success); font-weight: 600; }
    .text-monto-compra { color: var(--badge-danger); font-weight: 600; }
    
    /* Badges de leyenda */
    .badge-leyenda-venta  { background: var(--badge-success); color: white; }
    .badge-leyenda-compra { background: var(--badge-danger); color: white; }
    
    /* Card header comercial */
    .card-header-comercial {
        background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
        color: var(--text-on-primary);
    }
    .card-header-comercial h5 { color: var(--text-on-primary); }
    
    /* Filtro info */
    .filtro-info {
        color: var(--text-muted);
        font-size: 0.85rem;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <div class="card report-card mb-4">
        <div class="card-body bg-panaderia-light">
            <form method="GET" class="row align-items-end">
                <div class="col-md-3">
                    <label class="text-panaderia font-weight-bold small">
                        <i class="fas fa-calendar-alt mr-1"></i> Fecha Inicio
                    </label>
                    <input type="date" name="fecha_inicio" class="form-control" value="<?php echo e($inicio); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="text-panaderia font-weight-bold small">
                        <i class="far fa-calendar-check mr-1"></i> Fecha Fin
                    </label>
                    <input type="date" name="fecha_fin" class="form-control" value="<?php echo e($fin); ?>" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter mr-1"></i> Actualizar
                    </button>
                </div>
                <div class="col-md-4">
                    <small class="filtro-info">
                        <i class="fas fa-info-circle mr-1"></i> 
                        Rango: <?php echo e(\Carbon\Carbon::parse($inicio)->format('d/m/Y')); ?> al <?php echo e(\Carbon\Carbon::parse($fin)->format('d/m/Y')); ?>

                        (<?php echo e(\Carbon\Carbon::parse($inicio)->diffInDays($fin)); ?> días)
                    </small>
                </div>
            </form>
        </div>
    </div>

    
    <div class="mb-3">
        <button class="btn btn-sm btn-outline-primary" onclick="enviarPDFComercial()">
            <i class="fas fa-envelope mr-1"></i> Enviar PDF por correo
        </button>
    </div>

    
    <div class="row mb-4">
        
        <div class="col-md-4 mb-2">
            <div class="card stat-card-produccion stat-card-produccion-success">
                <div class="card-body py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="opacity-75">VENTAS TOTALES</small>
                            <h3 class="mb-0">Bs. <?php echo e(number_format($totalVentas, 2)); ?></h3>
                            <small class="opacity-75"><?php echo e($countVentas); ?> notas</small>
                        </div>
                        <i class="fas fa-arrow-up fa-2x stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="col-md-4 mb-2">
            <div class="card stat-card-produccion stat-card-produccion-danger">
                <div class="card-body py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="opacity-75">COMPRAS TOTALES</small>
                            <h3 class="mb-0">Bs. <?php echo e(number_format($totalCompras, 2)); ?></h3>
                            <small class="opacity-75"><?php echo e($countCompras); ?> notas</small>
                        </div>
                        <i class="fas fa-arrow-down fa-2x stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="col-md-4 mb-2">
            <?php $diferencia = $totalVentas - $totalCompras; ?>
            <div class="card stat-card-produccion <?php echo e($diferencia >= 0 ? 'stat-card-produccion-info' : 'stat-card-produccion-warning'); ?>">
                <div class="card-body py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="opacity-75">DIFERENCIA</small>
                            <h3 class="mb-0">Bs. <?php echo e(number_format($diferencia, 2)); ?></h3>
                            <small class="opacity-75"><?php echo e($diferencia >= 0 ? 'Ganancia' : 'Pérdida'); ?></small>
                        </div>
                        <i class="fas <?php echo e($diferencia >= 0 ? 'fa-balance-scale-right' : 'fa-balance-scale-left'); ?> fa-2x stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card report-card mb-4">
        <div class="card-header card-header-comercial">
            <h5 class="mb-0">
                <i class="fas fa-chart-line mr-2"></i> Evolución Diaria de Ventas y Compras
            </h5>
        </div>
        <div class="card-body">
            <div class="chart-container">
                <canvas id="comercialChart"></canvas>
            </div>
            <div class="text-center mt-3">
                <span class="badge badge-leyenda-venta mr-2">
                    <i class="fas fa-circle mr-1"></i> Ventas
                </span>
                <span class="badge badge-leyenda-compra mr-2">
                    <i class="fas fa-circle mr-1"></i> Compras
                </span>
            </div>
        </div>
    </div>

    
    <div class="card report-card">
        <div class="card-header" style="background: var(--color-bg-lighter);">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab-ventas">
                        <i class="fas fa-shopping-cart mr-1" style="color: var(--badge-success);"></i> 
                        Ventas (<?php echo e($countVentas); ?>)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab-compras">
                        <i class="fas fa-truck mr-1" style="color: var(--badge-danger);"></i> 
                        Compras (<?php echo e($countCompras); ?>)
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content">
                
                <div class="tab-pane fade show active" id="tab-ventas">
                    <div class="table-scroll">
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Empleado</th>
                                    <th class="text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><small><?php echo e($v->id_nota_venta); ?></small></td>
                                    <td><small><?php echo e($v->fecha_venta->format('d/m/Y H:i')); ?></small></td>
                                    <td><?php echo e($v->cliente->nombre ?? 'N/A'); ?></td>
                                    <td><?php echo e($v->empleado->nombre ?? 'N/A'); ?></td>
                                    <td class="text-right text-monto-venta">Bs. <?php echo e(number_format($v->monto_total, 2)); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        <i class="fas fa-inbox mr-2"></i>No hay ventas en este período.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                
                <div class="tab-pane fade" id="tab-compras">
                    <div class="table-scroll">
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Proveedor</th>
                                    <th>Empleado</th>
                                    <th class="text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $compras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><small><?php echo e($c->id_nota_compra); ?></small></td>
                                    <td><small><?php echo e($c->fecha_compra->format('d/m/Y H:i')); ?></small></td>
                                    <td><?php echo e($c->proveedor->persona->nombre ?? $c->proveedor->empresa->razon_social ?? 'N/A'); ?></td>
                                    <td><?php echo e($c->empleado->nombre ?? 'N/A'); ?></td>
                                    <td class="text-right text-monto-compra">Bs. <?php echo e(number_format($c->monto_total, 2)); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        <i class="fas fa-inbox mr-2"></i>No hay compras en este período.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function enviarPDFComercial() {
    const correo = prompt('Ingrese el correo electrónico:');
    if (!correo) return;
    
    const fechaInicio = document.querySelector('input[name="fecha_inicio"]').value;
    const fechaFin = document.querySelector('input[name="fecha_fin"]').value;
    
    fetch('<?php echo e(route("reportes.enviar-pdf")); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            correo: correo, 
            tipo: 'comercial',
            fecha_inicio: fechaInicio,
            fecha_fin: fechaFin
        })
    })
    .then(r => r.json())
    .then(r => {
        if (r.success) toastr.success(r.message);
        else toastr.error(r.message);
    });
}
    
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('comercialChart');
    if (!ctx) return;

    // Obtener colores del tema
    const style = getComputedStyle(document.body);
    const successColor = style.getPropertyValue('--badge-success').trim() || '#28a745';
    const dangerColor = style.getPropertyValue('--badge-danger').trim() || '#dc3545';
    const textMuted = style.getPropertyValue('--text-muted').trim() || '#6c757d';
    const gridColor = style.getPropertyValue('--color-border').trim() || 'rgba(0,0,0,0.05)';

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dias, 15, 512) ?>,
            datasets: [
                {
                    label: 'Ventas (Bs.)',
                    data: <?php echo json_encode($ventasPorDia, 15, 512) ?>,
                    borderColor: successColor,
                    backgroundColor: successColor + '20',
                    borderWidth: 3,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: successColor,
                    pointBorderColor: '#fff'
                },
                {
                    label: 'Compras (Bs.)',
                    data: <?php echo json_encode($comprasPorDia, 15, 512) ?>,
                    borderColor: dangerColor,
                    backgroundColor: dangerColor + '20',
                    borderWidth: 3,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: dangerColor,
                    pointBorderColor: '#fff'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12 },
                        color: textMuted
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.label + ': Bs. ' + parseFloat(ctx.raw).toFixed(2);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        maxRotation: 45,
                        font: { size: 11 },
                        color: textMuted
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Bs. ' + value;
                        },
                        font: { size: 11 },
                        color: textMuted
                    },
                    grid: {
                        color: gridColor
                    }
                }
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/reportes/comercial.blade.php ENDPATH**/ ?>