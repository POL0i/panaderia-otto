


<?php $__env->startSection('title', 'Producción #' . $produccion->id_produccion . ' - Panadería Otto'); ?>
<?php $__env->startSection('page-title', 'Detalle de Producción #' . $produccion->id_produccion); ?>
<?php $__env->startSection('page-description', 'Revisión y autorización de orden de producción'); ?>

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
                <div class="card-body">
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
                                    <td>
                                        <?php echo e($detalle->item->nombre ?? 'Item #' . $detalle->id_item); ?>

                                    </td>
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

    
    <?php if($produccion->estado == 'pendiente'): ?>
    <div class="row mt-3">
        
        <div class="col-md-7">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-check-circle"></i> Aprobar Producción y Ejecutar Movimientos</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('producciones.aprobar', $produccion)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label>
                                <i class="fas fa-arrow-down text-danger"></i> 
                                Almacén de INSUMOS (origen) - Se descontarán de aquí
                                <span class="text-danger">*</span>
                            </label>
                            <select name="almacen_origen" class="form-control" required>
                                <option value="">Seleccione de dónde sacar insumos...</option>
                                <?php $__currentLoopData = \App\Models\Almacen::whereIn('tipo_almacen', ['insumo', 'mixto'])->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($alm->id_almacen); ?>">
                                        <?php echo e($alm->nombre); ?> (<?php echo e($alm->tipo_almacen); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>
                                <i class="fas fa-arrow-up text-success"></i> 
                                Almacén de PRODUCTO (destino) - Se ingresará aquí
                                <span class="text-danger">*</span>
                            </label>
                            <select name="almacen_destino" class="form-control" required>
                                <option value="">Seleccione dónde guardar producto...</option>
                                <?php $__currentLoopData = \App\Models\Almacen::whereIn('tipo_almacen', ['producto', 'mixto'])->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($alm->id_almacen); ?>">
                                        <?php echo e($alm->nombre); ?> (<?php echo e($alm->tipo_almacen); ?>)
                                        <?php if($alm->capacidad > 0): ?> - Cap: <?php echo e($alm->capacidad); ?> <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg btn-block">
                            <i class="fas fa-check"></i> Ejecutar Producción
                        </button>
                    </form>
                </div>
            </div>
        </div>

        
        <div class="col-md-5">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-times-circle"></i> Rechazar o Cancelar</h5>
                </div>
                <div class="card-body text-center">
                    <button class="btn btn-danger btn-lg btn-block mb-3" data-toggle="modal" data-target="#modalMotivoRechazo">
                        <i class="fas fa-times"></i> Rechazar Producción
                    </button>
                    <form action="<?php echo e(route('producciones.cancelar', $produccion)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-dark btn-lg btn-block" 
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
                <div class="modal-header bg-danger text-white">
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
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/produccion/producciones/show.blade.php ENDPATH**/ ?>