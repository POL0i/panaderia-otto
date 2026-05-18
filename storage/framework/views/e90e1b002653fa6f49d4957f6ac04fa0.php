<?php $__env->startSection('title', 'Producción #' . $produccion->id_produccion . ' - Panadería Otto'); ?>
<?php $__env->startSection('page-title', 'Detalle de Producción #' . $produccion->id_produccion); ?>
<?php $__env->startSection('page-description', 'Revisión y autorización de orden de producción'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .info-row p { margin-bottom: 0.5rem; }
    .info-row strong { color: var(--color-primary-dark); }
    .lote-table th { white-space: nowrap; font-size: 0.85rem; }
    .lote-table td { font-size: 0.85rem; vertical-align: middle; }
    .aprobacion-card { border: 2px solid var(--badge-success); }
    .rechazo-card { border: 2px solid var(--badge-danger); }
    .almacen-badge {
        font-size: 0.9rem;
        background: var(--color-bg-lighter);
        padding: 0.4rem 0.8rem;
        border-radius: var(--border-radius-sm);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">

    
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo nl2br(e(session('error'))); ?></div>
    <?php endif; ?>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información de la Producción</h5>
                </div>
                <div class="card-body info-row">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID:</strong> #<?php echo e($produccion->id_produccion); ?></p>
                            <p><strong>Fecha producción:</strong> <?php echo e(\Carbon\Carbon::parse($produccion->fecha_produccion)->format('d/m/Y')); ?></p>
                            <p><strong>Cantidad a producir:</strong> <?php echo e($produccion->cantidad_producida); ?></p>
                            <p><strong>Solicitante:</strong> <?php echo e($produccion->empleadoSolicita->nombre ?? 'N/A'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Estado:</strong> 
                                <?php switch($produccion->estado):
                                    case ('pendiente'): ?> <span class="badge badge-warning">Pendiente</span> <?php break; ?>
                                    <?php case ('aprobado'): ?> <span class="badge badge-success">Aprobado</span> <?php break; ?>
                                    <?php case ('rechazado'): ?> <span class="badge badge-danger">Rechazado</span> <?php break; ?>
                                    <?php case ('cancelado'): ?> <span class="badge badge-secondary">Cancelado</span> <?php break; ?>
                                <?php endswitch; ?>
                            </p>
                            <p><strong>Fecha solicitud:</strong> <?php echo e($produccion->fecha_solicitud ? $produccion->fecha_solicitud->format('d/m/Y H:i') : 'No registrada'); ?></p>
                            <?php if($produccion->fecha_autorizacion): ?>
                                <p><strong>Autorizado por:</strong> <?php echo e($produccion->empleadoAutoriza->nombre ?? 'N/A'); ?></p>
                                <p><strong>Fecha autorización:</strong> <?php echo e($produccion->fecha_autorizacion->format('d/m/Y H:i')); ?></p>
                            <?php endif; ?>
                            <?php if($produccion->observaciones): ?>
                                <p><strong>Observaciones:</strong> <?php echo e($produccion->observaciones); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-right">
            <a href="<?php echo e(route('producciones.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a lista
            </a>
        </div>
    </div>

    
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <?php
                        $detalleConReceta = $produccion->detalles
                            ->whereNotNull('id_detalle_receta')
                            ->first();
                        $receta = $detalleConReceta?->detalleReceta?->receta;
                    ?>
                    <h5 class="mb-0"><i class="fas fa-book"></i> Receta: 
                        <strong><?php echo e($receta->nombre ?? 'N/A'); ?></strong>
                        <?php if($receta && $receta->producto): ?>
                            → Producto final: <strong><?php echo e($receta->producto->item->nombre ?? 'N/A'); ?></strong>
                        <?php endif; ?>
                    </h5>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($produccion->estado == 'aprobado'): ?>
        <?php
            $detalleEgreso = $produccion->detalles->where('tipo_movimiento', 'egreso')->first();
            $detalleIngreso = $produccion->detalles->where('tipo_movimiento', 'ingreso')->first();
            $almacenOrigen = $detalleEgreso?->almacen;
            $almacenDestino = $detalleIngreso?->almacen;
        ?>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-warehouse"></i> Almacenes utilizados</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <span class="almacen-badge">
                                    <i class="fas fa-arrow-down text-danger"></i> 
                                    <strong>Origen (insumos):</strong> 
                                    <?php echo e($almacenOrigen->nombre ?? 'No asignado'); ?>

                                </span>
                            </div>
                            <div class="col-md-6">
                                <span class="almacen-badge">
                                    <i class="fas fa-arrow-up text-success"></i> 
                                    <strong>Destino (producto):</strong> 
                                    <?php echo e($almacenDestino->nombre ?? 'No asignado'); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Movimientos Planificados</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Ítem</th>
                                    <th>Almacén actual</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $produccion->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <?php if($detalle->tipo_movimiento == 'egreso'): ?>
                                            <span class="badge badge-danger">Consume (Insumo)</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Produce (Producto)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($detalle->item->nombre ?? 'Item #' . $detalle->id_item); ?></td>
                                    <td>
                                        <?php if($detalle->id_almacen && $detalle->id_almacen != 1): ?>
                                            <?php echo e($detalle->almacen->nombre ?? 'Almacén #' . $detalle->id_almacen); ?>

                                        <?php else: ?>
                                            <span class="text-muted">Pendiente de asignación</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?php echo e($detalle->cantidad); ?></strong></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-boxes"></i> Lotes Afectados</h5>
                </div>
                <div class="card-body">
                    <?php if($produccion->estado == 'aprobado'): ?>
                        <?php
                            $lotesProducidos = \App\Models\LoteInventario::where('referencia_id', $produccion->id_produccion)
                                ->where('referencia_tipo', 'produccion')
                                ->orderBy('id_lote', 'desc')
                                ->get();
                            
                            $consumosProduccion = $produccion->detalles()
                                ->where('tipo_movimiento', 'egreso')
                                ->get();
                        ?>
                        
                        <?php if($lotesProducidos->count() > 0 || $consumosProduccion->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered lote-table">
                                    <thead>
                                        <tr>
                                            <th>Lote</th>
                                            <th>Tipo</th>
                                            <th>Item</th>
                                            <th>Almacén</th>
                                            <th>Cant. Inicial</th>
                                            <th>Cant. Disponible</th>
                                            <th>Esta producción</th>
                                            <th>Total consumido</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php $__currentLoopData = $lotesProducidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="table-success">
                                            <td>#<?php echo e($lote->id_lote); ?></td>
                                            <td><span class="badge badge-success">Producido</span></td>
                                            <td><?php echo e($lote->item_nombre); ?></td>
                                            <td><?php echo e($lote->almacen_nombre); ?></td>
                                            <td class="text-center"><?php echo e(number_format($lote->cantidad_inicial, 2)); ?></td>
                                            <td class="text-center"><?php echo e(number_format($lote->cantidad_disponible, 2)); ?></td>
                                            <td class="text-center"><strong><?php echo e(number_format($lote->cantidad_inicial, 2)); ?></strong></td>
                                            <td class="text-center">-</td>
                                            <td><span class="badge badge-success">Ingresado</span></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        
                                        
                                        <?php $__currentLoopData = $consumosProduccion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $consumo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                // Solo se buscan lotes si hay fecha de autorización (está aprobado)
                                                $lotesAfectados = \App\Models\LoteInventario::where('id_almacen', $consumo->id_almacen)
                                                    ->where('id_item', $consumo->id_item)
                                                    ->whereNotNull('fecha_salida')
                                                    ->whereDate('fecha_salida', $produccion->fecha_autorizacion->toDateString())
                                                    ->orderBy('fecha_salida', 'desc')
                                                    ->get();
                                            ?>
                                            
                                            <?php if($lotesAfectados->count() > 0): ?>
                                                <?php $__currentLoopData = $lotesAfectados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="table-danger">
                                                    <td>#<?php echo e($lote->id_lote); ?></td>
                                                    <td><span class="badge badge-danger">Consumido</span></td>
                                                    <td><?php echo e($consumo->item->nombre ?? 'Item #' . $consumo->id_item); ?></td>
                                                    <td><?php echo e($consumo->almacen->nombre ?? 'Almacén #' . $consumo->id_almacen); ?></td>
                                                    <td class="text-center"><?php echo e(number_format($lote->cantidad_inicial, 2)); ?></td>
                                                    <td class="text-center"><?php echo e(number_format($lote->cantidad_disponible, 2)); ?></td>
                                                    <td class="text-center"><strong class="text-danger"><?php echo e(number_format($consumo->cantidad, 2)); ?></strong></td>
                                                    <td class="text-center"><?php echo e(number_format($lote->cantidad_inicial - $lote->cantidad_disponible, 2)); ?></td>
                                                    <td>
                                                        <?php if($lote->cantidad_disponible == 0): ?>
                                                            <span class="badge badge-secondary">Agotado</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-warning">Parcial</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <tr class="table-warning">
                                                    <td>-</td>
                                                    <td><span class="badge badge-danger">Consumido</span></td>
                                                    <td><?php echo e($consumo->item->nombre ?? 'Item #' . $consumo->id_item); ?></td>
                                                    <td><?php echo e($consumo->almacen->nombre ?? 'Almacén #' . $consumo->id_almacen); ?></td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center">-</td>
                                                    <td class="text-center"><strong class="text-danger"><?php echo e(number_format($consumo->cantidad, 2)); ?></strong></td>
                                                    <td class="text-center">-</td>
                                                    <td><span class="badge badge-info">Sin lote</span></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted text-center">
                                <i class="fas fa-info-circle"></i> No se encontraron lotes afectados.
                            </p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted text-center">
                            <i class="fas fa-info-circle"></i> Los lotes se mostrarán cuando se apruebe la producción.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    
    
    <?php if($produccion->estado == 'pendiente'): ?>
        <?php if(auth()->user()->esAdmin() || in_array('almacen_ver', auth()->user()->obtenerPermisos())): ?>
        <div class="row mt-3">
            
            <div class="col-md-7">
                <div class="card aprobacion-card">
                    <div class="card-header modal-header-success">
                        <h5 class="mb-0"><i class="fas fa-check-circle"></i> Aprobar Producción y Ejecutar Movimientos</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('producciones.aprobar', $produccion)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label>
                                    <i class="fas fa-arrow-down text-danger"></i> 
                                    Almacén de INSUMOS (origen)
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="almacen_origen" id="almacen_origen" class="form-control" required>
                                    <option value="">Seleccione de dónde sacar insumos...</option>
                                    <?php $__currentLoopData = \App\Models\Almacen::whereIn('tipo_almacen', ['insumo', 'mixto'])->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($alm->id_almacen); ?>">
                                            <?php echo e($alm->nombre); ?> (<?php echo e($alm->tipo_almacen); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div id="info-stock-insumos" class="mt-2 small"></div>
                            </div>

                            <div class="form-group">
                                <label>
                                    <i class="fas fa-arrow-up text-success"></i> 
                                    Almacén de PRODUCTO (destino)
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="almacen_destino" id="almacen_destino" class="form-control" required>
                                    <option value="">Seleccione dónde guardar producto...</option>
                                    <?php $__currentLoopData = \App\Models\Almacen::whereIn('tipo_almacen', ['producto', 'mixto'])->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($alm->id_almacen); ?>">
                                            <?php echo e($alm->nombre); ?> (<?php echo e($alm->tipo_almacen); ?>)
                                            <?php if($alm->capacidad > 0): ?> - Cap: <?php echo e($alm->capacidad); ?> <?php endif; ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div id="info-capacidad-producto" class="mt-2 small"></div>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg btn-block">
                                <i class="fas fa-check"></i> Ejecutar Producción
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="col-md-5">
                <div class="card rechazo-card">
                    <div class="card-header modal-header-danger">
                        <h5 class="mb-0"><i class="fas fa-times-circle"></i> Rechazar o Cancelar</h5>
                    </div>
                    <div class="card-body text-center">
                        <button class="btn btn-danger btn-lg btn-block mb-3" data-toggle="modal" data-target="#modalMotivoRechazo">
                            <i class="fas fa-times"></i> Rechazar Producción
                        </button>
                        <form action="<?php echo e(route('producciones.cancelar', $produccion)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-secondary btn-lg btn-block" 
                                    onclick="return confirm('¿Está seguro de CANCELAR esta producción?')">
                                <i class="fas fa-ban"></i> Cancelar Producción
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="modal fade" id="modalMotivoRechazo" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                        <h5 class="modal-title"><i class="fas fa-times-circle"></i> Rechazar Producción</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="<?php echo e(route('producciones.rechazar', $produccion)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Motivo del rechazo <span class="text-danger">*</span></label>
                                <textarea name="motivo" class="form-control" rows="3" required 
                                          placeholder="Explique por qué se rechaza..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Confirmar Rechazo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="row mt-3">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-lock"></i> No tienes permisos para aprobar, rechazar o cancelar esta producción. Contacta al administrador o encargado de almacén.
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const produccionId = <?php echo e($produccion->id_produccion); ?>;
    const selectOrigen = document.getElementById('almacen_origen');
    const divInsumos = document.getElementById('info-stock-insumos');
    const selectDestino = document.getElementById('almacen_destino');
    const divCapacidad = document.getElementById('info-capacidad-producto');

    if (selectOrigen) {
        selectOrigen.addEventListener('change', function() {
            const almacenId = this.value;
            if (!almacenId) { divInsumos.innerHTML = ''; return; }
            divInsumos.innerHTML = '<span class="text-muted"><i class="fas fa-spinner fa-pulse"></i> Verificando...</span>';

            fetch(`/almacen/${almacenId}/insumos-stock/${produccionId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.insumos?.length) {
                        let ok = true, html = '';
                        data.insumos.forEach(item => {
                            let icon = item.suficiente ? 'fa-check-circle text-success' : 'fa-exclamation-circle text-danger';
                            html += `<div class="d-flex align-items-center mb-1">
                                <i class="fas ${icon} mr-2"></i>
                                <span class="flex-fill">${item.nombre}</span>
                                <span class="badge ${item.suficiente ? 'badge-success' : 'badge-danger'} ml-2">${item.stock} / ${item.requerido}</span>
                            </div>`;
                            if (!item.suficiente) ok = false;
                        });
                        divInsumos.innerHTML = `<div class="alert ${ok ? 'alert-success' : 'alert-danger'} py-2 px-3 mb-0 mt-2">
                            <i class="fas ${ok ? 'fa-check-circle' : 'fa-exclamation-triangle'} mr-1"></i>
                            <strong>${ok ? 'Stock suficiente' : 'Stock insuficiente'}</strong>
                            <div class="mt-1">${html}</div></div>`;
                    } else {
                        divInsumos.innerHTML = '<div class="alert alert-warning py-2 px-3 mb-0 mt-2">No se encontraron insumos.</div>';
                    }
                })
                .catch(() => divInsumos.innerHTML = '<div class="alert alert-danger py-2 px-3 mb-0 mt-2">Error al consultar stock.</div>');
        });
    }

    if (selectDestino) {
        selectDestino.addEventListener('change', function() {
            const almacenId = this.value;
            if (!almacenId) { divCapacidad.innerHTML = ''; return; }
            divCapacidad.innerHTML = '<span class="text-muted"><i class="fas fa-spinner fa-pulse"></i> Verificando...</span>';

            fetch(`/almacen/${almacenId}/capacidad-disponible/${produccionId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.error) {
                        divCapacidad.innerHTML = `<div class="alert alert-danger py-2 px-3 mb-0 mt-2">${data.error}</div>`;
                        return;
                    }
                    const disponible = data.disponible !== null ? data.disponible : '∞';
                    const suficiente = data.suficiente;
                    divCapacidad.innerHTML = `<div class="alert ${suficiente ? 'alert-success' : 'alert-danger'} py-2 px-3 mb-0 mt-2">
                        <i class="fas ${suficiente ? 'fa-check-circle' : 'fa-exclamation-triangle'} mr-1"></i>
                        <strong>${suficiente ? 'Espacio suficiente' : 'Espacio insuficiente'} para ${data.cantidad_prod} unidades</strong><br>
                        <small><i class="fas fa-warehouse"></i> ${data.almacen} | Cap: ${data.capacidad > 0 ? data.capacidad : 'Sin límite'} | Ocupado: ${data.stock_actual}</small>
                    </div>`;
                })
                .catch(() => divCapacidad.innerHTML = '<div class="alert alert-danger py-2 px-3 mb-0 mt-2">Error al verificar capacidad.</div>');
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/produccion/producciones/show.blade.php ENDPATH**/ ?>