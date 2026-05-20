<?php $__env->startSection('title', 'Reporte de Producción - Panadería Otto'); ?>
<?php $__env->startSection('page-title', 'Reporte de Producción'); ?>
<?php $__env->startSection('page-description', 'Productos más creados e insumos más usados'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ==========================================
       ESTILOS ESPECÍFICOS - REPORTE PRODUCCIÓN
       ========================================== */
    
    /* Barras de progreso */
    .progress-bar-produccion {
        height: 26px;
        border-radius: 13px;
        background: linear-gradient(90deg, var(--color-primary-light) 0%, var(--color-primary) 100%);
        color: var(--text-on-primary);
        font-weight: bold;
        font-size: 0.8rem;
        line-height: 26px;
        padding-left: 10px;
        min-width: 50px;
        transition: width 0.5s ease;
    }
    .progress-bar-insumo {
        height: 26px;
        border-radius: 13px;
        background: linear-gradient(90deg, var(--color-accent) 0%, var(--color-primary) 100%);
        color: var(--color-primary-dark);
        font-weight: bold;
        font-size: 0.8rem;
        line-height: 26px;
        padding-left: 10px;
        min-width: 50px;
        transition: width 0.5s ease;
    }
    
    /* Stats cards pequeñas */
    .stat-card-produccion {
        border-radius: var(--border-radius-md);
        color: var(--text-on-primary);
        transition: transform 0.2s ease;
        height: 100%;
    }
    .stat-card-produccion:hover { transform: translateY(-2px); }
    .stat-card-produccion .card-body { padding: 1.25rem; }
    .stat-card-produccion .stat-icon {
        font-size: 2.5rem;
        opacity: 0.2;
    }
    .stat-card-produccion h3 { font-weight: 700; }
    
    /* Variantes */
    .stat-card-produccion-primary {
        background: linear-gradient(135deg, var(--color-primary-light) 0%, var(--color-primary) 100%);
    }
    .stat-card-produccion-success {
        background: linear-gradient(135deg, var(--badge-success) 0%, color-mix(in srgb, var(--badge-success) 70%, black) 100%);
    }
    .stat-card-produccion-info {
        background: linear-gradient(135deg, var(--badge-info) 0%, color-mix(in srgb, var(--badge-info) 70%, black) 100%);
    }
    .stat-card-produccion-warning {
        background: linear-gradient(135deg, var(--badge-warning) 0%, color-mix(in srgb, var(--badge-warning) 70%, black) 100%);
        color: var(--color-primary-dark);
    }
    
    /* Card headers */
    .card-header-produccion {
        background: linear-gradient(135deg, var(--color-primary-light) 0%, var(--color-primary) 100%);
        color: var(--text-on-primary);
    }
    .card-header-insumo {
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-primary) 100%);
        color: var(--text-on-primary);
    }
    .card-header-historial {
        background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
        color: var(--text-on-primary);
    }
    .card-header-produccion h6,
    .card-header-insumo h6,
    .card-header-historial h6 { color: var(--text-on-primary); }
    
    /* Badge insumo en tabla */
    .badge-insumo-item {
        background: var(--color-bg-lighter);
        color: var(--color-primary-dark);
        border: 1px solid var(--color-border);
        font-size: 0.75rem;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">

    
    <div class="row mb-4">
        <?php
            $totalProductosDiferentes = \App\Models\DetalleProduccion::where('tipo_movimiento', 'ingreso')
                ->distinct('id_item')->count('id_item');
            $totalInsumosDiferentes = \App\Models\DetalleProduccion::where('tipo_movimiento', 'egreso')
                ->distinct('id_item')->count('id_item');
            $totalProducciones = \App\Models\Produccion::where('estado', 'aprobado')->count();
            $totalUnidadesProducidas = \App\Models\DetalleProduccion::where('tipo_movimiento', 'ingreso')->sum('cantidad');
        ?>
        
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card stat-card-produccion stat-card-produccion-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0"><?php echo e($totalProducciones); ?></h3>
                            <small>Total Producciones</small>
                        </div>
                        <i class="fas fa-industry stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card stat-card-produccion stat-card-produccion-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0"><?php echo e($totalProductosDiferentes); ?></h3>
                            <small>Productos Diferentes</small>
                        </div>
                        <i class="fas fa-boxes stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card stat-card-produccion stat-card-produccion-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0"><?php echo e(number_format($totalUnidadesProducidas, 0)); ?></h3>
                            <small>Unidades Producidas</small>
                        </div>
                        <i class="fas fa-cubes stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card stat-card-produccion stat-card-produccion-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0"><?php echo e($totalInsumosDiferentes); ?></h3>
                            <small>Insumos Diferentes</small>
                        </div>
                        <i class="fas fa-flask stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="mb-3">
        <button class="btn btn-sm btn-outline-primary" onclick="$('#modalEnviarPDF').modal('show')">
            <i class="fas fa-envelope mr-1"></i> Enviar PDF por correo
        </button>
    </div>

    
    <div class="row">
        
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header card-header-produccion">
                    <h6 class="mb-0"><i class="fas fa-star mr-2"></i> Productos Más Creados</h6>
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $productosMasCreados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $porc = $maxProducido > 0 ? ($producto->total_producido / $maxProducido) * 100 : 0; ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <strong><?php echo e($producto->nombre); ?></strong>
                                <small class="text-muted"><?php echo e(number_format($producto->total_producido, 0)); ?> uds. (<?php echo e($producto->total_ordenes); ?> órdenes)</small>
                            </div>
                            <div class="progress" style="height: 26px;">
                                <div class="progress-bar-produccion" style="width: <?php echo e($porc); ?>%;"><?php echo e(round($porc)); ?>%</div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted text-center py-3">No hay productos creados aún.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header card-header-insumo">
                    <h6 class="mb-0"><i class="fas fa-fire mr-2"></i> Insumos Más Usados</h6>
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $insumosMasUsados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $insumo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $porc = $maxConsumo > 0 ? ($insumo->total_consumido / $maxConsumo) * 100 : 0; ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <strong><?php echo e($insumo->nombre); ?></strong>
                                <small class="text-muted"><?php echo e(number_format($insumo->total_consumido, 2)); ?> uds. (<?php echo e($insumo->total_ordenes); ?> órdenes)</small>
                            </div>
                            <div class="progress" style="height: 26px;">
                                <div class="progress-bar-insumo" style="width: <?php echo e($porc); ?>%;"><?php echo e(round($porc)); ?>%</div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted text-center py-3">No hay insumos usados aún.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header card-header-historial">
                    <h6 class="mb-0"><i class="fas fa-history mr-2"></i> Últimas Producciones</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th>Insumos</th>
                                    <th>Solicitante</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $ultimasProducciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>#<?php echo e($p->id_produccion); ?></td>
                                    <td><small><?php echo e($p->fecha_produccion->format('d/m/Y')); ?></small></td>
                                    <td>
                                        <?php $ingreso = $p->detalles->where('tipo_movimiento', 'ingreso')->first(); ?>
                                        <?php echo e($ingreso->item->nombre ?? 'N/A'); ?>

                                    </td>
                                    <td class="text-center"><?php echo e($p->cantidad_producida); ?></td>
                                    <td>
                                        <?php $__currentLoopData = $p->detalles->where('tipo_movimiento', 'egreso')->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge badge-insumo-item mr-1"><?php echo e($d->item->nombre ?? 'Item'); ?>: <?php echo e($d->cantidad); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                    <td><small><?php echo e($p->empleadoSolicita->nombre ?? 'N/A'); ?></small></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">Sin producciones</td>
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
                                    data-correo="produccion@panaderia-otto.shop" style="font-size: 0.75rem; padding: 2px 8px;">
                                produccion
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
                                    data-correo="dennis@panaderia-otto.shop" style="font-size: 0.75rem; padding: 2px 8px;">
                                dennis
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
                    Se enviará el reporte de producción con productos más creados e insumos más usados.
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
            tipo: '<?php echo e($tipo ?? "produccion"); ?>',
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
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/reportes/produccion.blade.php ENDPATH**/ ?>