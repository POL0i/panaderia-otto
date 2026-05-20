<?php $__env->startSection('title', 'Reporte de Inventario - Panadería Otto'); ?>
<?php $__env->startSection('page-title', 'Reporte de Inventario'); ?>
<?php $__env->startSection('page-description', 'Stock, valorización, movimientos y caducidades'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ==========================================
       ESTILOS ESPECÍFICOS - REPORTE INVENTARIO
       ========================================== */
    
    /* Iconos en stats */
    .stat-icon { font-size: 2.5rem; opacity: 0.2; }
    
    /* Hover card */
    .hover-card {
        transition: transform 0.2s;
        cursor: default;
    }
    .hover-card:hover { transform: translateY(-3px); }
    
    /* Chart */
    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
    }
    
    /* Card headers de alerta */
    .card-header-danger {
        background: linear-gradient(135deg, var(--badge-danger) 0%, color-mix(in srgb, var(--badge-danger) 80%, black) 100%);
        color: white;
    }
    .card-header-warning-light {
        background: var(--color-bg-lighter);
        color: var(--badge-warning);
        border-bottom: 2px solid var(--badge-warning);
    }
    .card-header-danger h6,
    .card-header-warning-light h6 { margin: 0; }
    
    /* Item de stock bajo */
    .stock-bajo-item {
        background: rgba(var(--badge-danger-rgb, 220, 53, 69), 0.04);
        border-radius: var(--border-radius-sm);
        transition: background 0.2s;
    }
    .stock-bajo-item:hover { background: rgba(var(--badge-danger-rgb, 220, 53, 69), 0.08); }
    
    /* Item de próximo a vencer */
    .vencimiento-item {
        background: rgba(var(--badge-warning-rgb, 255, 193, 7), 0.05);
        border-radius: var(--border-radius-sm);
        transition: background 0.2s;
    }
    .vencimiento-item:hover { background: rgba(var(--badge-warning-rgb, 255, 193, 7), 0.1); }
    
    /* Badge días */
    .badge-vencimiento {
        background: var(--badge-warning);
        color: var(--color-primary-dark);
        font-size: 0.8rem;
    }
    
    /* Barra de valorización */
    .progress-bar-valorizacion {
        background: linear-gradient(90deg, var(--color-primary) 0%, var(--color-accent) 100%);
    }
    
    /* Badges de movimiento */
    .badge-movimiento-ingreso { background: var(--badge-info); color: white; }
    .badge-movimiento-egreso  { background: var(--badge-danger); color: white; }
    
    /* Card header inventario */
    .card-header-inventario {
        background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
        color: var(--text-on-primary);
    }
    .card-header-inventario h6 { color: var(--text-on-primary); }
    
    /* Leyenda del gráfico */
    .chart-legend {
        display: flex;
        gap: 1rem;
    }
    .chart-legend-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    .chart-legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    .chart-legend-dot-ingreso { background: var(--badge-info); }
    .chart-legend-dot-egreso  { background: var(--badge-danger); }
    
    /* Tabla scroll */
    .table-scroll {
        max-height: 300px;
        overflow-y: auto;
    }
    .table-scroll::-webkit-scrollbar { width: 6px; }
    .table-scroll::-webkit-scrollbar-track { background: var(--color-bg-light); border-radius: 3px; }
    .table-scroll::-webkit-scrollbar-thumb { background: var(--color-border); border-radius: 3px; }
    
    /* Badge de estado lote */
    .badge-estado-disponible { background: var(--badge-success); color: white; }
    .badge-estado-consumido  { background: var(--badge-warning); color: var(--color-primary-dark); }
    
    /* Texto valor stock */
    .text-valor-stock { color: var(--badge-info); font-weight: 600; }
    
    /* Progress mini */
    .progress-mini {
        width: 70px;
        height: 6px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">

    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-panaderia">
            <i class="fas fa-warehouse mr-2"></i> Reporte de Inventario
        </h1>
        <div>
            <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                <i class="fas fa-print mr-1"></i> Imprimir
            </button>
                <button class="btn btn-sm btn-outline-primary" onclick="$('#modalEnviarPDF').modal('show')">
                    <i class="fas fa-envelope mr-1"></i> Enviar PDF por correo
                </button>
        </div>
    </div>
        
    
    <div class="row mb-4">
        <?php
            $totalLotes = \App\Models\LoteInventario::count();
            $lotesDisponibles = \App\Models\LoteInventario::where('estado', 'disponible')->count();
            $lotesConsumidos = \App\Models\LoteInventario::where('estado', 'consumido')->count();
            $valorTotal = \App\Models\LoteInventario::where('estado', 'disponible')
                ->sum(DB::raw('cantidad_disponible * precio_unitario'));
            $movimientosHoy = \App\Models\MovimientoInventario::whereDate('fecha_movimiento', today())->count();
            
            $movimientosGrafico = \App\Models\MovimientoInventario::orderBy('id_movimiento', 'desc')
                ->limit(50)
                ->get()
                ->reverse();
        ?>
        
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card hover-card stat-card-produccion stat-card-produccion-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0"><?php echo e($totalLotes); ?></h3>
                            <small>Total Lotes</small>
                        </div>
                        <i class="fas fa-boxes stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card hover-card stat-card-produccion stat-card-produccion-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0"><?php echo e($lotesDisponibles); ?></h3>
                            <small>Lotes Disponibles</small>
                        </div>
                        <i class="fas fa-check-circle stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card hover-card stat-card-produccion stat-card-produccion-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">Bs. <?php echo e(number_format($valorTotal, 2)); ?></h3>
                            <small>Valor en Stock</small>
                        </div>
                        <i class="fas fa-dollar-sign stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card hover-card stat-card-produccion stat-card-produccion-warning h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0"><?php echo e($movimientosHoy); ?></h3>
                            <small>Movimientos Hoy</small>
                        </div>
                        <i class="fas fa-exchange-alt stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header card-header-inventario">
                    <h6 class="mb-0 d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-chart-bar mr-2"></i> Ingresos vs Egresos (Últimos 50 movimientos)</span>
                        <span class="chart-legend">
                            <span class="chart-legend-item">
                                <span class="chart-legend-dot chart-legend-dot-ingreso"></span> Ingresos
                            </span>
                            <span class="chart-legend-item">
                                <span class="chart-legend-dot chart-legend-dot-egreso"></span> Egresos
                            </span>
                        </span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="ingresosEgresosChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mb-4">
        
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header card-header-danger">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle mr-2"></i> Stock Bajo (<30%)</h6>
                </div>
                <div class="card-body p-2 table-scroll" style="max-height: 280px;">
                    <?php $__empty_1 = true; $__currentLoopData = $lotesBajos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $porc = ($lote->cantidad_disponible / $lote->cantidad_inicial) * 100; ?>
                        <div class="d-flex align-items-center mb-2 px-2 py-1 stock-bajo-item">
                            <span class="flex-fill">
                                <strong><?php echo e($lote->item_nombre); ?></strong>
                                <br><small class="text-muted"><?php echo e($lote->almacen_nombre); ?></small>
                            </span>
                            <span class="text-danger font-weight-bold mr-2">
                                <?php echo e(number_format($lote->cantidad_disponible, 0)); ?>/<?php echo e(number_format($lote->cantidad_inicial, 0)); ?>

                            </span>
                            <div class="progress progress-mini">
                                <div class="progress-bar bg-danger" style="width: <?php echo e($porc); ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted text-center py-3">
                            <i class="fas fa-thumbs-up mr-1"></i> Todo en orden
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header card-header-warning-light">
                    <h6 class="mb-0"><i class="fas fa-clock mr-2"></i> Próximos a Vencer (7 días)</h6>
                </div>
                <div class="card-body p-2 table-scroll" style="max-height: 280px;">
                    <?php $__empty_1 = true; $__currentLoopData = $lotesPorVencer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $dias = now()->diffInDays($lote->fecha_vencimiento); ?>
                        <div class="d-flex justify-content-between align-items-center mb-2 px-2 py-1 vencimiento-item">
                            <span>
                                <strong><?php echo e($lote->item_nombre); ?></strong>
                                <br><small class="text-muted"><?php echo e($lote->almacen_nombre); ?></small>
                            </span>
                            <span class="badge badge-vencimiento">
                                <?php echo e($lote->fecha_vencimiento->format('d/m/Y')); ?> (<?php echo e($dias); ?> d)
                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted text-center py-3">
                            <i class="fas fa-thumbs-up mr-1"></i> Sin vencimientos próximos
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header card-header-inventario">
                    <h6 class="mb-0"><i class="fas fa-exchange-alt mr-2"></i> Últimos Movimientos de Inventario</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-scroll">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Item</th>
                                    <th>Almacén</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $movimientos = \App\Models\MovimientoInventario::orderBy('id_movimiento', 'desc')
                                        ->limit(20)
                                        ->get();
                                ?>
                                <?php $__empty_1 = true; $__currentLoopData = $movimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><small>#<?php echo e($mov->id_movimiento); ?></small></td>
                                    <td><small><?php echo e(\Carbon\Carbon::parse($mov->fecha_movimiento)->format('d/m/Y H:i')); ?></small></td>
                                    <td>
                                        <span class="badge <?php echo e($mov->tipo_movimiento == 'ingreso' ? 'badge-movimiento-ingreso' : 'badge-movimiento-egreso'); ?>">
                                            <?php echo e($mov->tipo_movimiento == 'ingreso' ? 'Ingreso' : 'Egreso'); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e(\App\Models\Item::find($mov->id_item)->nombre ?? 'Item #'.$mov->id_item); ?></td>
                                    <td><?php echo e(\App\Models\Almacen::find($mov->id_almacen)->nombre ?? 'N/A'); ?></td>
                                    <td class="text-center"><?php echo e($mov->cantidad); ?></td>
                                    <td class="text-right">Bs. <?php echo e(number_format($mov->costo_total, 2)); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">
                                        <i class="fas fa-inbox mr-2"></i>Sin movimientos
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

    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header card-header-inventario">
                    <h6 class="mb-0"><i class="fas fa-warehouse mr-2"></i> Valorización por Almacén</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                            $almacenes = \App\Models\Almacen::all();
                            $maxValor = 0;
                            $valores = [];
                            foreach($almacenes as $alm) {
                                $val = \App\Models\LoteInventario::where('id_almacen', $alm->id_almacen)
                                    ->where('estado', 'disponible')
                                    ->sum(DB::raw('cantidad_disponible * precio_unitario'));
                                $valores[$alm->id_almacen] = $val;
                                if ($val > $maxValor) $maxValor = $val;
                            }
                        ?>
                        <?php $__currentLoopData = $almacenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $porc = $maxValor > 0 ? ($valores[$alm->id_almacen] / $maxValor) * 100 : 0; ?>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <strong><?php echo e($alm->nombre); ?></strong>
                                    <span class="text-valor-stock">Bs. <?php echo e(number_format($valores[$alm->id_almacen], 2)); ?></span>
                                </div>
                                <div class="progress" style="height: 16px; border-radius: 8px;">
                                    <div class="progress-bar progress-bar-valorizacion" style="width: <?php echo e($porc); ?>%;">
                                        <?php echo e(round($porc)); ?>%
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="modalEnviarPDF" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-panaderia shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-envelope mr-2"></i> Enviar Reporte PDF
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body bg-panaderia-light">
                <div class="form-group">
                    <label class="text-panaderia">
                        <i class="fas fa-users mr-1"></i> Destinatarios
                    </label>
                    
                    
                    <div class="mb-2">
                        <small class="text-muted">Sugerencias:</small>
                        <div class="d-flex flex-wrap gap-1 mt-1" id="sugerenciasCorreos">
                            <button type="button" class="btn btn-sm btn-outline-info sugerencia-btn" 
                                    data-correo="dennis@panaderia-otto.shop" style="font-size: 0.75rem; padding: 2px 8px;">
                                dennis
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info sugerencia-btn" 
                                    data-correo="admin@panaderia-otto.shop" style="font-size: 0.75rem; padding: 2px 8px;">
                                admin
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info sugerencia-btn" 
                                    data-correo="inventario@panaderia-otto.shop" style="font-size: 0.75rem; padding: 2px 8px;">
                                inventario
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info sugerencia-btn" 
                                    data-correo="produccion@panaderia-otto.shop" style="font-size: 0.75rem; padding: 2px 8px;">
                                produccion
                            </button>
                        </div>
                    </div>
                    
                    
                    <div id="emailsContainer">
                        <div class="input-group mb-2 email-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="text" class="form-control email-input" 
                                   placeholder="usuario@panaderia-otto.shop" autocomplete="off">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success btn-add-email" title="Agregar otro">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i> 
                        Escribe <strong>@pan</strong> para autocompletar o selecciona una sugerencia.
                    </small>
                </div>
                
                <div class="form-group">
                    <label class="text-panaderia">
                        <i class="fas fa-comment mr-1"></i> Mensaje adicional
                    </label>
                    <textarea class="form-control" id="mensajeAdicional" rows="2" 
                              placeholder="Mensaje opcional..."></textarea>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i>
                    Se enviará el reporte de inventario con stock, lotes y valorización.
                </div>
            </div>
            <div class="modal-footer bg-panaderia-lighter">
                <button type="button" class="btn btn-cancel" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-save" id="btnEnviarPDF">
                    <i class="fas fa-paper-plane mr-1"></i> Enviar PDF
                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ==========================================
// AGREGAR / ELIMINAR CAMPOS DE CORREO
// ==========================================
$(document).on('click', '.btn-add-email', function() {
    const newGroup = `
        <div class="input-group mb-2 email-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            </div>
            <input type="text" class="form-control email-input" 
                   placeholder="usuario@panaderia-otto.shop" autocomplete="off">
            <div class="input-group-append">
                <button type="button" class="btn btn-danger btn-remove-email" title="Quitar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>`;
    $('#emailsContainer').append(newGroup);
});

$(document).on('click', '.btn-remove-email', function() {
    if ($('.email-group').length > 1) {
        $(this).closest('.email-group').remove();
    } else {
        toastr.warning('Debe haber al menos un destinatario');
    }
});

// ==========================================
// AUTOCOMPLETAR @pan
// ==========================================
$(document).on('input', '.email-input', function() {
    const input = $(this);
    const val = input.val();
    if (val.endsWith('@pan') && !val.includes('@panaderia-otto.shop')) {
        input.val(val.replace('@pan', '') + '@panaderia-otto.shop');
    }
});

// ==========================================
// CLIC EN SUGERENCIA
// ==========================================
$(document).on('click', '.sugerencia-btn', function() {
    const correo = $(this).data('correo');
    let campoVacio = null;
    $('.email-input').each(function() {
        if ($(this).val().trim() === '' && !campoVacio) campoVacio = $(this);
    });
    if (campoVacio) {
        campoVacio.val(correo);
    } else {
        $('.btn-add-email').last().click();
        $('.email-input').last().val(correo);
    }
    toastr.info(correo.split('@')[0] + ' agregado', '', { timeOut: 1000 });
});

// ==========================================
// ENVIAR PDF
// ==========================================
$('#btnEnviarPDF').on('click', function() {
    const btn = $(this);
    const correos = [];
    let invalidos = false;
    
    $('.email-input').each(function() {
        const val = $(this).val().trim();
        if (val) {
            if (val.includes('@') && val.includes('.')) {
                correos.push(val);
            } else {
                $(this).addClass('is-invalid');
                invalidos = true;
            }
        }
    });
    
    if (invalidos) { toastr.error('Hay correos con formato inválido'); return; }
    if (correos.length === 0) { toastr.error('Ingrese al menos un correo válido'); return; }
    
    const mensaje = $('#mensajeAdicional').val().trim();
    
    btn.html('<i class="fas fa-spinner fa-spin"></i> Enviando...').prop('disabled', true);
    
    $.ajax({
        url: '<?php echo e(route("reportes.enviar-pdf")); ?>',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        data: JSON.stringify({ 
            correos: correos,
            tipo: '<?php echo e($tipo ?? "inventario"); ?>',
            mensaje: mensaje
        }),
        success: function(response) {
            if (response.success) {
                toastr.success(response.message || 'Enviado a ' + correos.length + ' destinatario(s)');
                $('#modalEnviarPDF').modal('hide');
                $('.email-group:not(:first)').remove();
                $('.email-input').val('').removeClass('is-invalid');
                $('#mensajeAdicional').val('');
            } else {
                toastr.error(response.message || 'Error al enviar');
            }
        },
        error: function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Error al enviar');
        },
        complete: function() {
            btn.html('<i class="fas fa-paper-plane mr-1"></i> Enviar PDF').prop('disabled', false);
        }
    });
});

// Quitar is-invalid al corregir
$(document).on('input', '.email-input', function() {
    if ($(this).val().includes('@') && $(this).val().includes('.')) {
        $(this).removeClass('is-invalid');
    }
});

// ==========================================
// GRÁFICO DE INGRESOS VS EGRESOS
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('ingresosEgresosChart');
    if (!ctx) return;

    const style = getComputedStyle(document.body);
    const infoColor = style.getPropertyValue('--badge-info').trim() || '#007bff';
    const dangerColor = style.getPropertyValue('--badge-danger').trim() || '#dc3545';
    const textMuted = style.getPropertyValue('--text-muted').trim() || '#6c757d';
    const gridColor = style.getPropertyValue('--color-border').trim() || 'rgba(0,0,0,0.05)';

    const movimientos = <?php echo $movimientosGrafico->values()->toJson(); ?>;
    
    const itemsNombres = {};
    <?php $__currentLoopData = $movimientosGrafico; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        itemsNombres[<?php echo e($mov->id_item); ?>] = "<?php echo e(\App\Models\Item::find($mov->id_item)->nombre ?? 'Item #'.$mov->id_item); ?>";
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    
    const labels = movimientos.map(m => {
        const nombre = itemsNombres[m.id_item] || ('Item #' + m.id_item);
        return nombre.substring(0, 20);
    });
    
    const ingresos = movimientos.map(m => m.tipo_movimiento === 'ingreso' ? parseFloat(m.cantidad) : 0);
    const egresos = movimientos.map(m => m.tipo_movimiento === 'egreso' ? parseFloat(m.cantidad) : 0);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Ingresos (unidades)',
                    data: ingresos,
                    backgroundColor: infoColor + '80',
                    borderColor: infoColor,
                    borderWidth: 1,
                    borderRadius: 3
                },
                {
                    label: 'Egresos (unidades)',
                    data: egresos,
                    backgroundColor: dangerColor + '80',
                    borderColor: dangerColor,
                    borderWidth: 1,
                    borderRadius: 3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.label + ': ' + parseFloat(ctx.raw).toFixed(2) + ' unidades';
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        maxRotation: 45,
                        font: { size: 9 },
                        color: textMuted,
                        autoSkip: true,
                        maxTicksLimit: 25
                    },
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return value + ' uds.'; },
                        color: textMuted
                    },
                    grid: { color: gridColor }
                }
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/reportes/inventario.blade.php ENDPATH**/ ?>