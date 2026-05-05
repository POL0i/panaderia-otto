


<?php $__env->startSection('title', 'Módulo de Producción - Panadería Otto'); ?>
<?php $__env->startSection('page-title', 'Panel de Producción'); ?>
<?php $__env->startSection('page-description', 'Gestión de recetas, insumos y categorías'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .module-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 15px;
        overflow: hidden;
        height: 100%;
    }
    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .module-icon {
        font-size: 48px;
        margin-bottom: 15px;
        color: #8B4513;
    }
    .module-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #5D3A1A;
    }
    .stats-card {
        background: linear-gradient(135deg, #8B4513 0%, #5D3A1A 100%);
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
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number"><?php echo e($totalRecetas ?? 0); ?></div>
                <div class="stats-label">Recetas Totales</div>
                <i class="fas fa-utensils float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #A0522D 0%, #8B4513 100%);">
                <div class="stats-number"><?php echo e($totalProducciones ?? 0); ?></div>
                <div class="stats-label">Producciones</div>
                <i class="fas fa-chart-bar float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #CD853F 0%, #A0522D 100%);">
                <div class="stats-number"><?php echo e($totalCategorias ?? 0); ?></div>
                <div class="stats-label">Categorías</div>
                <i class="fas fa-folder float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #DEB887 0%, #CD853F 100%);">
                <div class="stats-number"><?php echo e($totalInsumos ?? 0); ?></div>
                <div class="stats-label">Insumos</div>
                <i class="fas fa-box float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background: #FFF5E6; border-bottom: 2px solid #D2B48C;">
                    <h5 class="mb-0" style="color: #5D3A1A;">
                        <i class="fas fa-bolt"></i> Acciones Rápidas - Crear Nuevo
                    </h5>
                </div>
                <div class="card-body text-center">
                    
                    <button class="btn btn-info quick-action-btn" data-toggle="modal" data-target="#createInsumoModal">
                        <i class="fas fa-box-open"></i> Nuevo Insumo
                    </button>
                    <button class="btn btn-primary quick-action-btn" data-toggle="modal" data-target="#createRecetaModal">
                        <i class="fas fa-book-medical"></i> Nueva Receta
                    </button>
                    <a href="<?php echo e(route('producciones.index')); ?>" class="btn btn-warning quick-action-btn">
                        <i class="fas fa-list-alt"></i> Ver Órdenes de Producción
                    </a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background: #FFF5E6; border-bottom: 2px solid #D2B48C;">
                    <h5 class="mb-0" style="color: #5D3A1A;">
                        <i class="fas fa-industry"></i> Nueva Orden de Producción
                    </h5>
                </div>
                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                    <?php endif; ?>
                    <?php if(session('success')): ?>
                        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>

                    <form id="formNuevaProduccion" action="<?php echo e(route('producciones.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Receta a producir <span class="text-danger">*</span></label>
                                    <select name="id_receta" id="produccion_receta" class="form-control" required>
                                        <option value="">Seleccione receta...</option>
                                        <?php $__currentLoopData = \App\Models\Receta::with('producto')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($receta->id_receta); ?>" 
                                                    data-producto="<?php echo e($receta->producto->item->nombre ?? 'Sin producto'); ?>">
                                                <?php echo e($receta->nombre); ?>

                                                <?php if($receta->producto): ?>
                                                    (Producto: <?php echo e($receta->producto->item->nombre); ?>)
                                                <?php else: ?>
                                                    (Sin producto)
                                                <?php endif; ?>
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Cantidad a producir <span class="text-danger">*</span></label>
                                    <input type="number" name="cantidad_producida" id="produccion_cantidad" 
                                        class="form-control" step="0.1" min="0.1" placeholder="Ej: 5" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Notificar a empleado (opcional)</label>
                                    <select name="notificar_empleado" class="form-control">
                                        <option value="">No notificar</option>
                                        <?php $__currentLoopData = \App\Models\Empleado::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($emp->id_empleado); ?>"><?php echo e($emp->nombre); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observaciones</label>
                                    <textarea name="observaciones" class="form-control" rows="2" 
                                        placeholder="Notas adicionales..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6><i class="fas fa-calculator"></i> Insumos requeridos:</h6>
                                <div id="preview-insumos" class="table-responsive">
                                    <p class="text-muted">Seleccione receta y cantidad para calcular.</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-primary btn-lg" id="btnCrearProduccion">
                                <i class="fas fa-paper-plane"></i> Solicitar Producción
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <?php if(isset($ultimasRecetas) && count($ultimasRecetas) > 0): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Últimas Recetas Creadas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr><th>Nombre</th><th>Descripción</th><th>Insumos</th><th>Creada</th><th>Acciones</th></tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $ultimasRecetas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($receta->nombre); ?></strong></td>
                                    <td><?php echo e(Str::limit($receta->descripcion, 40) ?: '-'); ?></td>
                                    <td><span class="badge badge-info"><?php echo e($receta->detalles_count ?? 0); ?> insumos</span></td>
                                    <td><?php echo e($receta->created_at->diffForHumans()); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('recetas.show', $receta)); ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('produccion.recetas.detalles', $receta)); ?>" class="btn btn-sm btn-success">
                                            <i class="fas fa-plus-circle"></i> Insumos
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>




<?php echo $__env->make('modulo-almacen.partials.modal-categoria-insumo', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('modulo-almacen.partials.modal-insumo', ['categorias' => $categorias], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('modulo-almacen.partials.modal-categoria-producto', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<div class="modal fade" id="createRecetaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title"><i class="fas fa-book-medical"></i> Nueva Receta</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateRecetaCompleta">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre de la Receta <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="recetaNombre" class="form-control" 
                                       placeholder="Ej: Pan Francés, Tarta de Manzana..." required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Producto Final <span class="text-danger">*</span></label>
                                <select name="id_producto" id="recetaProducto" class="form-control" required>
                                    <option value="">Seleccione producto...</option>
                                    <?php $__currentLoopData = \App\Models\Producto::with('item')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($producto->id_producto); ?>">
                                            <?php echo e($producto->item->nombre); ?> (<?php echo e($producto->item->unidad_medida ?? 'unidad'); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea name="descripcion" id="recetaDescripcion" class="form-control" rows="2" 
                                          placeholder="Describe brevemente la receta..."></textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h6><i class="fas fa-boxes"></i> Agregar Insumos a la Receta</h6>
                    <div id="insumosContainer" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px; padding: 10px; margin-bottom: 15px;">
                        <?php $__currentLoopData = $categorias ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($categoria->insumos->count() > 0): ?>
                                <div class="categoria-section mb-3">
                                    <div class="categoria-header bg-light p-2 mb-2" style="border-left: 4px solid #8B4513;">
                                        <strong><i class="fas fa-folder"></i> <?php echo e($categoria->nombre); ?></strong>
                                        <span class="badge badge-secondary ml-2"><?php echo e($categoria->insumos->count()); ?></span>
                                    </div>
                                    <div class="row">
                                        <?php $__currentLoopData = $categoria->insumos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $insumo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-md-6 mb-2">
                                                <div class="insumo-item border rounded p-2">
                                                    <div class="custom-control custom-checkbox mb-2">
                                                        <input type="checkbox" class="custom-control-input insumo-checkbox" 
                                                            id="modal_insumo_<?php echo e($insumo->id_insumo); ?>" value="<?php echo e($insumo->id_insumo); ?>">
                                                        <label class="custom-control-label" for="modal_insumo_<?php echo e($insumo->id_insumo); ?>">
                                                            <strong><?php echo e($insumo->item->nombre ?? $insumo->nombre ?? 'Insumo'); ?></strong>
                                                            <small class="text-muted">(<?php echo e($insumo->item->unidad_medida ?? 'unidad'); ?>)</small>
                                                        </label>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <input type="number" name="cantidad_<?php echo e($insumo->id_insumo); ?>" 
                                                                class="form-control form-control-sm cantidad-insumo"
                                                                placeholder="Cantidad" step="0.001" min="0.001" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="text-right">
                        <span id="insumosSeleccionadosCount" class="mr-2">0 insumos seleccionados</span>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Selecciona los insumos y especifica la cantidad. La unidad de medida se toma del insumo.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnCrearRecetaCompleta">
                        <i class="fas fa-save"></i> Crear Receta con Insumos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    
    // Habilitar/deshabilitar campos de cantidad al marcar checkbox
    $(document).on('change', '.insumo-checkbox', function() {
        var container = $(this).closest('.insumo-item');
        var isChecked = $(this).prop('checked');
        container.find('.cantidad-insumo').prop('disabled', !isChecked);
        if (isChecked) {
            container.find('.cantidad-insumo').prop('required', true).val('1');
        } else {
            container.find('.cantidad-insumo').prop('required', false).val('');
        }
        actualizarContadorInsumos();
    });
    
    function actualizarContadorInsumos() {
        var count = $('.insumo-checkbox:checked').length;
        $('#insumosSeleccionadosCount').text(count + ' insumos seleccionados');
    }
    
    // Crear Receta
    $('#formCreateRecetaCompleta').on('submit', function(e) {
        e.preventDefault();
        var nombre = $('#recetaNombre').val();
        var descripcion = $('#recetaDescripcion').val();
        var id_producto = $('#recetaProducto').val();
        
        if (!nombre) { toastr.error('El nombre de la receta es requerido'); return; }
        if (!id_producto) { toastr.error('Debe seleccionar un producto final'); return; }
        
        var insumos = [];
        $('.insumo-checkbox:checked').each(function() {
            var id = $(this).val();
            var container = $(this).closest('.insumo-item');
            var cantidad = container.find('.cantidad-insumo').val();
            if (cantidad && cantidad > 0) {
                insumos.push({ id_insumo: id, cantidad: cantidad });
            }
        });
        
        $.ajax({
            url: '<?php echo e(route("produccion.recetas.store")); ?>',
            method: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                nombre: nombre, descripcion: descripcion, id_producto: id_producto
            },
            success: function(response) {
                if (response.success && response.receta) {
                    var recetaId = response.receta.id_receta;
                    if (insumos.length > 0) {
                        $.ajax({
                            url: '/produccion/recetas/' + recetaId + '/detalles',
                            method: 'POST',
                            data: { _token: '<?php echo e(csrf_token()); ?>', insumos: insumos },
                            success: function() {
                                $('#createRecetaModal').modal('hide');
                                toastr.success('Receta creada con ' + insumos.length + ' insumos');
                                $('#formCreateRecetaCompleta')[0].reset();
                                $('.insumo-checkbox').prop('checked', false).trigger('change');
                                setTimeout(() => { window.location.href = '/produccion/recetas/' + recetaId + '/detalles'; }, 1000);
                            },
                            error: function() {
                                toastr.warning('Receta creada pero hubo error al agregar insumos');
                                setTimeout(() => location.reload(), 1500);
                            }
                        });
                    } else {
                        $('#createRecetaModal').modal('hide');
                        toastr.success('Receta creada correctamente');
                        $('#formCreateRecetaCompleta')[0].reset();
                        setTimeout(() => { window.location.href = '/produccion/recetas/' + recetaId + '/detalles'; }, 1000);
                    }
                }
            },
            error: function(xhr) {
                var message = 'Error al crear la receta';
                if (xhr.responseJSON?.errors) message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                toastr.error(message);
            }
        });
    });
    
    // Previsualización de insumos
    $('#produccion_receta, #produccion_cantidad').on('change keyup', function() {
        var recetaId = $('#produccion_receta').val();
        var cantidad = parseFloat($('#produccion_cantidad').val());
        if (recetaId && cantidad && cantidad > 0) {
            $.ajax({
                url: '<?php echo e(route("producciones.calcular-insumos")); ?>',
                method: 'POST',
                data: { _token: '<?php echo e(csrf_token()); ?>', id_receta: recetaId, cantidad: cantidad },
                success: function(response) {
                    var html = '<table class="table table-sm table-bordered"><thead><tr><th>Insumo</th><th>Cant. base</th><th>Cant. requerida</th><th>Unidad</th></tr></thead><tbody>';
                    response.insumos.forEach(function(ins) {
                        html += '<tr><td>' + ins.insumo + '</td><td>' + ins.cantidad_teorica + '</td><td><strong>' + ins.cantidad_requerida.toFixed(3) + '</strong></td><td>' + ins.unidad + '</td></tr>';
                    });
                    html += '</tbody></table>';
                    $('#preview-insumos').html(html);
                },
                error: function() { $('#preview-insumos').html('<p class="text-danger">Error al calcular insumos.</p>'); }
            });
        } else {
            $('#preview-insumos').html('<p class="text-muted">Seleccione receta y cantidad para calcular.</p>');
        }
    });
    
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/produccion/index.blade.php ENDPATH**/ ?>