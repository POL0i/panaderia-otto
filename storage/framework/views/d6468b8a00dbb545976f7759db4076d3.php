


<?php $__env->startSection('title', 'Detalles de Receta: ' . $receta->nombre); ?>
<?php $__env->startSection('page-title', 'Gestionar Insumos de Receta'); ?>
<?php $__env->startSection('page-description', $receta->nombre); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .insumo-selector {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #f9f9f9;
    }
    .insumo-row {
        background: white;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .categoria-header {
        background: #8B4513;
        color: white;
        padding: 8px 15px;
        border-radius: 5px;
        margin-bottom: 10px;
        cursor: pointer;
    }
    .categoria-header i {
        transition: transform 0.3s;
    }
    .categoria-header.collapsed i {
        transform: rotate(-90deg);
    }
    .insumos-list {
        max-height: 300px;
        overflow-y: auto;
    }
    .detalle-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .badge-unidad {
        background: #5D3A1A;
        color: white;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 12px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="<?php echo e(route('produccion.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Panel
            </a>
            <a href="<?php echo e(route('recetas.show', $receta)); ?>" class="btn btn-info">
                <i class="fas fa-eye"></i> Ver Receta
            </a>
            <a href="<?php echo e(route('recetas.index')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-list"></i> Todas las Recetas
            </a>
        </div>
    </div>

    <div class="row">
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-utensils"></i> <?php echo e($receta->nombre); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <p><strong>Descripción:</strong> <?php echo e($receta->descripcion ?: 'Sin descripción'); ?></p>
                    <p><strong>Total de insumos:</strong> 
                        <span class="badge badge-info" id="totalInsumos"><?php echo e($receta->detalles->count()); ?></span>
                    </p>
                    <p><strong>Creada:</strong> <?php echo e($receta->created_at->format('d/m/Y H:i')); ?></p>
                </div>
            </div>
        </div>

        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle"></i> Agregar Insumos a la Receta
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-filter"></i> Filtrar por Categoría</label>
                                <select id="filterCategoria" class="form-control">
                                    <option value="">Todas las categorías</option>
                                    <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="categoria-<?php echo e($categoria->id_cat_insumo); ?>">
                                            <?php echo e($categoria->nombre); ?> (<?php echo e($categoria->insumos->count()); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-search"></i> Buscar Insumo</label>
                                <input type="text" id="searchInsumo" class="form-control" 
                                       placeholder="Buscar por nombre...">
                            </div>
                        </div>
                    </div>

                    <form id="formAddInsumos">
                        <?php echo csrf_field(); ?>
                        <div class="insumos-selector">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6>Selecciona los insumos a agregar</h6>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllVisible">
                                        <i class="fas fa-check-square"></i> Seleccionar Visibles
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                        <i class="fas fa-square"></i> Deseleccionar Todos
                                    </button>
                                </div>
                            </div>

                            <div id="insumosContainer" style="max-height: 350px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px; padding: 10px;">
                                <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($categoria->insumos->count() > 0): ?>
                                        <div class="categoria-section mb-3" id="categoria-<?php echo e($categoria->id_cat_insumo); ?>">
                                            <div class="categoria-header" data-toggle="collapse" data-target="#collapse-<?php echo e($categoria->id_cat_insumo); ?>">
                                                <i class="fas fa-chevron-down mr-2"></i>
                                                <strong><?php echo e($categoria->nombre); ?></strong>
                                                <span class="badge badge-light ml-2"><?php echo e($categoria->insumos->count()); ?></span>
                                            </div>
                                            <div class="collapse show" id="collapse-<?php echo e($categoria->id_cat_insumo); ?>">
                                                <div class="insumos-list p-2">
                                                    <?php $__currentLoopData = $categoria->insumos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $insumo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if(!in_array($insumo->id_insumo, $insumosEnReceta)): ?>
                                                            <div class="insumo-item mb-2 p-2 border rounded" 
                                                                 data-categoria="categoria-<?php echo e($categoria->id_cat_insumo); ?>"
                                                                 data-nombre="<?php echo e(strtolower($insumo->nombre)); ?>">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-5">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox" 
                                                                                   class="custom-control-input insumo-checkbox" 
                                                                                   id="insumo_<?php echo e($insumo->id_insumo); ?>"
                                                                                   value="<?php echo e($insumo->id_insumo); ?>">
                                                                            <label class="custom-control-label" for="insumo_<?php echo e($insumo->id_insumo); ?>">
                                                                                <strong><?php echo e($insumo->nombre); ?></strong>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="number" 
                                                                               name="cantidad_<?php echo e($insumo->id_insumo); ?>" 
                                                                               class="form-control form-control-sm cantidad-insumo"
                                                                               placeholder="Cantidad"
                                                                               step="0.001" min="0.001"
                                                                               disabled>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <select name="unidad_<?php echo e($insumo->id_insumo); ?>" 
                                                                                class="form-control form-control-sm unidad-insumo"
                                                                                disabled>
                                                                            <option value="kg">kg</option>
                                                                            <option value="g">g</option>
                                                                            <option value="lb">lb</option>
                                                                            <option value="oz">oz</option>
                                                                            <option value="L">L</option>
                                                                            <option value="mL">mL</option>
                                                                            <option value="unidad">unidad</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="text-right mt-3">
                            <span id="selectedCount" class="mr-3">0 insumos seleccionados</span>
                            <button type="submit" class="btn btn-success" id="btnAgregarInsumos" disabled>
                                <i class="fas fa-plus-circle"></i> Agregar Insumos Seleccionados
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Insumos Actuales de la Receta
                    </h5>
                </div>
                <div class="card-body">
                    <div id="detallesContainer">
                        <?php $__empty_1 = true; $__currentLoopData = $receta->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="detalle-card" id="detalle-<?php echo e($detalle->id_detalle_receta); ?>">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <strong><?php echo e($detalle->insumo->nombre ?? 'N/A'); ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?php echo e($detalle->insumo->categoria->nombre ?? 'Sin categoría'); ?>

                                        </small>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="cantidad-valor"><?php echo e($detalle->cantidad_requerida); ?></span>
                                        <span class="badge-unidad"><?php echo e($detalle->unidad_medida); ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-sm btn-warning btn-edit-detalle" 
                                                data-id="<?php echo e($detalle->id_detalle_receta); ?>"
                                                data-cantidad="<?php echo e($detalle->cantidad_requerida); ?>"
                                                data-unidad="<?php echo e($detalle->unidad_medida); ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <button class="btn btn-sm btn-danger btn-delete-detalle" 
                                                data-id="<?php echo e($detalle->id_detalle_receta); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center text-muted py-4" id="noInsumosMsg">
                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                <p>No hay insumos agregados a esta receta.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editDetalleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Editar Cantidad</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEditDetalle">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <input type="hidden" name="detalle_id" id="editDetalleId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cantidad</label>
                                <input type="number" name="cantidad" id="editCantidad" 
                                       class="form-control" step="0.001" min="0.001" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Unidad</label>
                                <select name="unidad" id="editUnidad" class="form-control" required>
                                    <option value="kg">kg</option>
                                    <option value="g">g</option>
                                    <option value="lb">lb</option>
                                    <option value="oz">oz</option>
                                    <option value="L">L</option>
                                    <option value="mL">mL</option>
                                    <option value="unidad">unidad</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    const recetaId = <?php echo e($receta->id_receta); ?>;

    // Habilitar/deshabilitar campos de cantidad
    $(document).on('change', '.insumo-checkbox', function() {
        var container = $(this).closest('.insumo-item');
        var isChecked = $(this).prop('checked');
        
        container.find('.cantidad-insumo, .unidad-insumo').prop('disabled', !isChecked);
        if (isChecked) {
            container.find('.cantidad-insumo').val('1');
        } else {
            container.find('.cantidad-insumo').val('');
        }
        updateSelectedCount();
    });

    function updateSelectedCount() {
        var count = $('.insumo-checkbox:checked').length;
        $('#selectedCount').text(count + ' insumos seleccionados');
        $('#btnAgregarInsumos').prop('disabled', count === 0);
    }

    $('#selectAllVisible').on('click', function() {
        $('.insumo-checkbox:visible').prop('checked', true).trigger('change');
    });

    $('#deselectAll').on('click', function() {
        $('.insumo-checkbox').prop('checked', false).trigger('change');
    });

    $('#filterCategoria').on('change', function() {
        var categoria = $(this).val();
        if (categoria) {
            $('.categoria-section').hide();
            $('#' + categoria).show();
        } else {
            $('.categoria-section').show();
        }
    });

    $('#searchInsumo').on('keyup', function() {
        var search = $(this).val().toLowerCase();
        $('.insumo-item').each(function() {
            var nombre = $(this).data('nombre');
            $(this).toggle(nombre.includes(search));
        });
    });

    // Agregar insumos
    $('#formAddInsumos').on('submit', function(e) {
        e.preventDefault();
        
        var insumos = [];
        $('.insumo-checkbox:checked').each(function() {
            var id = $(this).val();
            var container = $(this).closest('.insumo-item');
            insumos.push({
                id_insumo: id,
                cantidad: container.find('.cantidad-insumo').val(),
                unidad: container.find('.unidad-insumo').val()
            });
        });
        
        $.ajax({
            url: '/produccion/recetas/' + recetaId + '/detalles',
            method: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                insumos: insumos
            },
            success: function(response) {
                alert(response.message);
                location.reload();
            },
            error: function() {
                alert('Error al agregar insumos');
            }
        });
    });

    // Editar detalle
    $(document).on('click', '.btn-edit-detalle', function() {
        $('#editDetalleId').val($(this).data('id'));
        $('#editCantidad').val($(this).data('cantidad'));
        $('#editUnidad').val($(this).data('unidad'));
        $('#editDetalleModal').modal('show');
    });

    $('#formEditDetalle').on('submit', function(e) {
        e.preventDefault();
        var detalleId = $('#editDetalleId').val();
        
        $.ajax({
            url: '/produccion/detalles/' + detalleId,
            method: 'POST',
            data: $(this).serialize() + '&_method=PUT',
            success: function(response) {
                $('#editDetalleModal').modal('hide');
                alert(response.message);
                location.reload();
            },
            error: function() {
                alert('Error al actualizar');
            }
        });
    });

    // Eliminar detalle
    $(document).on('click', '.btn-delete-detalle', function() {
        if (!confirm('¿Eliminar este insumo de la receta?')) return;
        
        var detalleId = $(this).data('id');
        
        $.ajax({
            url: '/produccion/detalles/' + detalleId,
            method: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                _method: 'DELETE'
            },
            success: function(response) {
                alert(response.message);
                $('#detalle-' + detalleId).remove();
                $('#totalInsumos').text(response.total_insumos);
                if ($('.detalle-card').length === 0) {
                    location.reload();
                }
            },
            error: function() {
                alert('Error al eliminar');
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/produccion/recetas/detalles.blade.php ENDPATH**/ ?>