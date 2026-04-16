
<div class="modal fade" id="createInsumoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title">
                    <i class="fas fa-flask"></i> Nuevo Insumo
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateInsumo" action="<?php echo e(route('modulo-almacen.insumos.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre del Insumo <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="insumoNombre" class="form-control" 
                               placeholder="Ej: Harina de trigo, Azúcar, Huevos..." required>
                    </div>
                    
                    <div class="form-group">
                        <label>Categoría <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="id_cat_insumo" id="insumoCategoria" class="form-control" required>
                                <option value="">Seleccionar categoría...</option>
                                <?php $__currentLoopData = $categorias ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($categoria->id_cat_insumo); ?>"><?php echo e($categoria->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-warning" 
                                        data-toggle="modal" 
                                        data-target="#createCategoriaInsumoModal"
                                        data-dismiss="modal">
                                    <i class="fas fa-plus"></i> Nueva
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">
                            Si no encuentras la categoría, crea una nueva.
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label>Unidad de Medida <span class="text-danger">*</span></label>
                        <select name="unidad_medida" id="insumoUnidad" class="form-control" required>
                            <option value="kg">Kilogramos (kg)</option>
                            <option value="g">Gramos (g)</option>
                            <option value="lb">Libras (lb)</option>
                            <option value="oz">Onzas (oz)</option>
                            <option value="L">Litros (L)</option>
                            <option value="mL">Mililitros (mL)</option>
                            <option value="unidad">Unidad</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Precio de Compra</label>
                        <input type="number" name="precio_compra" id="insumoPrecio" class="form-control" 
                               step="0.01" min="0" placeholder="0.00">
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Se creará automáticamente un registro en Items como "insumo".
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-save"></i> Crear Insumo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#formCreateInsumo').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createInsumoModal').modal('hide');
                    alert(response.message);
                    form[0].reset();
                    setTimeout(() => location.reload(), 1000);
                }
            },
            error: function(xhr) {
                var message = 'Error al crear el insumo';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                alert(message);
            }
        });
    });
    
    // Recargar categorías después de crear una nueva
    $('#formCreateCategoriaInsumo').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createCategoriaInsumoModal').modal('hide');
                    alert(response.message);
                    
                    // Agregar la nueva categoría al select
                    var newOption = new Option(response.categoria.nombre, response.categoria.id_cat_insumo);
                    $('#insumoCategoria').append(newOption).val(response.categoria.id_cat_insumo);
                    
                    // Volver a abrir el modal de insumo
                    $('#createInsumoModal').modal('show');
                    form[0].reset();
                }
            },
            error: function(xhr) {
                var message = 'Error al crear la categoría';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                alert(message);
            }
        });
    });
});
</script><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/modulo-almacen/partials/modal-insumo.blade.php ENDPATH**/ ?>