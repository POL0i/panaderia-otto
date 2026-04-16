
<div class="modal fade" id="createProductoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">
                    <i class="fas fa-box"></i> Nuevo Producto
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateProducto" action="<?php echo e(route('modulo-almacen.productos.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre del Producto <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="productoNombre" class="form-control" 
                               placeholder="Ej: Pan Francés, Tarta de Manzana..." required>
                    </div>
                    
                    <div class="form-group">
                        <label>Categoría <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="id_cat_producto" id="productoCategoria" class="form-control" required>
                                <option value="">Seleccionar categoría...</option>
                                <?php $__currentLoopData = $categorias ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($categoria->id_cat_producto); ?>"><?php echo e($categoria->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-info" 
                                        data-toggle="modal" 
                                        data-target="#createCategoriaProductoModal"
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
                        <select name="unidad_medida" id="productoUnidad" class="form-control" required>
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
                        <label>Precio de Venta</label>
                        <input type="number" name="precio" id="productoPrecio" class="form-control" 
                               step="0.01" min="0" placeholder="0.00">
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Se creará automáticamente un registro en Items como "producto".
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Crear Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#formCreateProducto').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createProductoModal').modal('hide');
                    alert(response.message);
                    form[0].reset();
                    setTimeout(() => location.reload(), 1000);
                }
            },
            error: function(xhr) {
                var message = 'Error al crear el producto';
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
    $('#formCreateCategoriaProducto').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createCategoriaProductoModal').modal('hide');
                    alert(response.message);
                    
                    // Agregar la nueva categoría al select
                    var newOption = new Option(response.categoria.nombre, response.categoria.id_cat_producto);
                    $('#productoCategoria').append(newOption).val(response.categoria.id_cat_producto);
                    
                    // Volver a abrir el modal de producto
                    $('#createProductoModal').modal('show');
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
</script><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/modulo-almacen/partials/modal-producto.blade.php ENDPATH**/ ?>