<?php $__env->startSection('title', 'Editar Item'); ?>
<?php $__env->startSection('page-title', 'Editar Item'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .img-thumbnail {
        max-height: 120px;
        object-fit: cover;
    }
    .btn-edit-categoria {
        padding: 2px 8px;
        font-size: 12px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <form action="<?php echo e(route('items.update', $item->id_item)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            
            <div class="card">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title text-white">
                        <i class="fas fa-info-circle mr-2"></i> Datos Generales
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="nombre">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('nombre', $item->nombre)); ?>" required>
                        <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label>Tipo de Item</label>
                        <input type="text" class="form-control bg-light" 
                               value="<?php echo e($item->tipo_item == 'producto' ? '📦 Producto' : '🧪 Insumo'); ?>" readonly>
                        <input type="hidden" name="tipo_item" value="<?php echo e($item->tipo_item); ?>">
                    </div>

                    <div class="form-group">
                        <label for="unidad_medida">Unidad de Medida <span class="text-danger">*</span></label>
                        <select name="unidad_medida" id="unidad_medida" class="form-control <?php $__errorArgs = ['unidad_medida'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <?php $__currentLoopData = ['kg','g','lb','oz','L','mL','unidad','docena','paquete','bandeja']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $um): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($um); ?>" <?php echo e(old('unidad_medida', $item->unidad_medida) == $um ? 'selected' : ''); ?>><?php echo e($um); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['unidad_medida'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            
            <?php if($item->tipo_item == 'producto' && $item->producto): ?>
            <div class="card collapsed-card" id="card-producto">
                <div class="card-header bg-success" data-card-widget="collapse">
                    <h3 class="card-title text-white">
                        <i class="fas fa-box mr-2"></i> Información del Producto
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    
                    <div class="form-group">
                        <label for="id_cat_producto">Categoría <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="id_cat_producto" id="id_cat_producto" class="form-control <?php $__errorArgs = ['id_cat_producto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Seleccione...</option>
                                <?php $__currentLoopData = $categoriasProductos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id_cat_producto); ?>"
                                        <?php echo e(old('id_cat_producto', $item->producto->id_cat_producto) == $cat->id_cat_producto ? 'selected' : ''); ?>>
                                        <?php echo e($cat->nombre); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#createCategoriaProductoModal" title="Nueva categoría">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-edit-categoria-producto" 
                                        data-id="<?php echo e($item->producto->id_cat_producto); ?>"
                                        data-nombre="<?php echo e($item->producto->categoria->nombre ?? ''); ?>"
                                        data-descripcion="<?php echo e($item->producto->categoria->descripcion ?? ''); ?>"
                                        title="Editar categoría actual">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        <?php $__errorArgs = ['id_cat_producto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="form-group">
                        <label for="precio">Precio de Venta <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" step="0.01" name="precio" id="precio" class="form-control <?php $__errorArgs = ['precio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('precio', $item->producto->precio)); ?>" required>
                        </div>
                        <?php $__errorArgs = ['precio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <?php if($item->producto->imagen): ?>
                    <div class="form-group">
                        <label>Imagen Actual</label>
                        <div class="mb-2">
                            <?php
                                $imagen = $item->producto->imagen;
                                $esUrl = Str::startsWith($imagen, ['http://', 'https://']);
                                $src = $esUrl ? $imagen : asset('storage/' . $imagen);
                            ?>
                            <img src="<?php echo e($src); ?>" class="img-thumbnail" style="max-width: 150px;"
                                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2250%22 height=%2250%22><rect fill=%22%23eee%22 width=%2250%22 height=%2250%22/><text x=%2225%22 y=%2230%22 text-anchor=%22middle%22 fill=%22%23999%22 font-size=%2210%22>No img</text></svg>'">
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <div class="form-group">
                        <label for="imagen">Subir Nueva Imagen</label>
                        <div class="custom-file">
                            <input type="file" name="imagen" id="imagen" class="custom-file-input" accept="image/*">
                            <label class="custom-file-label" for="imagen">Seleccionar archivo</label>
                        </div>
                        <small class="form-text text-muted">jpg, png, gif. Máx 2MB</small>
                    </div>

                    
                    <div class="form-group">
                        <label for="imagen_url">O usar URL</label>
                        <input type="url" name="imagen_url" id="imagen_url" class="form-control"
                               placeholder="https://ejemplo.com/imagen.jpg" value="<?php echo e(old('imagen_url')); ?>">
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if($item->tipo_item == 'insumo' && $item->insumo): ?>
            <div class="card collapsed-card" id="card-insumo">
                <div class="card-header bg-info" data-card-widget="collapse">
                    <h3 class="card-title text-white">
                        <i class="fas fa-flask mr-2"></i> Información del Insumo
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    
                    <div class="form-group">
                        <label for="id_cat_insumo">Categoría <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="id_cat_insumo" id="id_cat_insumo" class="form-control <?php $__errorArgs = ['id_cat_insumo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Seleccione...</option>
                                <?php $__currentLoopData = $categoriasInsumos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id_cat_insumo); ?>"
                                        <?php echo e(old('id_cat_insumo', $item->insumo->id_cat_insumo) == $cat->id_cat_insumo ? 'selected' : ''); ?>>
                                        <?php echo e($cat->nombre); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#createCategoriaInsumoModal" title="Nueva categoría">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-edit-categoria-insumo" 
                                        data-id="<?php echo e($item->insumo->id_cat_insumo); ?>"
                                        data-nombre="<?php echo e($item->insumo->categoria->nombre ?? ''); ?>"
                                        data-descripcion="<?php echo e($item->insumo->categoria->descripcion ?? ''); ?>"
                                        title="Editar categoría actual">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        <?php $__errorArgs = ['id_cat_insumo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="form-group">
                        <label for="precio_compra">Precio de Compra</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" step="0.01" name="precio_compra" id="precio_compra" class="form-control"
                                   value="<?php echo e(old('precio_compra', $item->insumo->precio_compra)); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="text-right mt-3">
                <button type="submit" class="btn btn-lg btn-primary">
                    <i class="fas fa-save mr-1"></i> Actualizar Item
                </button>
                <a href="<?php echo e(route('items.index')); ?>" class="btn btn-lg btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>


<?php if($item->tipo_item == 'producto'): ?>
<div class="modal fade" id="createCategoriaProductoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloCategoriaProducto">Nueva Categoría de Producto</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCategoriaProducto">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="categoria_id" id="cat_producto_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="cat_producto_nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="cat_producto_descripcion" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnGuardarCategoriaProducto">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>


<?php if($item->tipo_item == 'insumo'): ?>
<div class="modal fade" id="createCategoriaInsumoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloCategoriaInsumo">Nueva Categoría de Insumo</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCategoriaInsumo">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="categoria_id" id="cat_insumo_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="cat_insumo_nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="cat_insumo_descripcion" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnGuardarCategoriaInsumo">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(function() {
    // Input file: mostrar nombre
    $('.custom-file-input').on('change', function() {
        $(this).next('.custom-file-label').html($(this).val().split('\\').pop() || 'Seleccionar archivo');
    });

    // ============================================
    // CREAR/EDITAR CATEGORÍA PRODUCTO
    // ============================================
    $('.btn-edit-categoria-producto').on('click', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        var descripcion = $(this).data('descripcion');
        
        $('#cat_producto_id').val(id);
        $('#cat_producto_nombre').val(nombre);
        $('#cat_producto_descripcion').val(descripcion);
        $('#tituloCategoriaProducto').text('Editar Categoría de Producto');
        $('#btnGuardarCategoriaProducto').text('Actualizar');
        $('#createCategoriaProductoModal').modal('show');
    });

    $('#createCategoriaProductoModal').on('hidden.bs.modal', function() {
        $('#cat_producto_id').val('');
        $('#cat_producto_nombre').val('');
        $('#cat_producto_descripcion').val('');
        $('#tituloCategoriaProducto').text('Nueva Categoría de Producto');
        $('#btnGuardarCategoriaProducto').text('Guardar');
    });

    $('#formCategoriaProducto').on('submit', function(e) {
        e.preventDefault();
        var id = $('#cat_producto_id').val();
        var url, method;
        
        if (id) {
            url = '/productos/categorias/' + id;
            method = 'PUT';
        } else {
            url = '<?php echo e(route("productos.categorias.store")); ?>';
            method = 'POST';
        }
        
        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function(response) {
                $('#createCategoriaProductoModal').modal('hide');
                var cat = response.categoria || response;
                if (id) {
                    $('#id_cat_producto option[value="' + id + '"]').text(cat.nombre || $('#cat_producto_nombre').val());
                } else {
                    var newOption = new Option(cat.nombre || $('#cat_producto_nombre').val(), cat.id_cat_producto, true, true);
                    $('#id_cat_producto').append(newOption);
                }
                toastr.success('Categoría ' + (id ? 'actualizada' : 'creada'));
                $('#formCategoriaProducto')[0].reset();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error');
            }
        });
    });

    // ============================================
    // CREAR/EDITAR CATEGORÍA INSUMO
    // ============================================
    $('.btn-edit-categoria-insumo').on('click', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        var descripcion = $(this).data('descripcion');
        
        $('#cat_insumo_id').val(id);
        $('#cat_insumo_nombre').val(nombre);
        $('#cat_insumo_descripcion').val(descripcion);
        $('#tituloCategoriaInsumo').text('Editar Categoría de Insumo');
        $('#btnGuardarCategoriaInsumo').text('Actualizar');
        $('#createCategoriaInsumoModal').modal('show');
    });

    $('#createCategoriaInsumoModal').on('hidden.bs.modal', function() {
        $('#cat_insumo_id').val('');
        $('#cat_insumo_nombre').val('');
        $('#cat_insumo_descripcion').val('');
        $('#tituloCategoriaInsumo').text('Nueva Categoría de Insumo');
        $('#btnGuardarCategoriaInsumo').text('Guardar');
    });

    $('#formCategoriaInsumo').on('submit', function(e) {
        e.preventDefault();
        var id = $('#cat_insumo_id').val();
        var url, method;
        
        if (id) {
            url = '/insumos/categorias/' + id;
            method = 'PUT';
        } else {
            url = '<?php echo e(route("insumos.categorias.store")); ?>';
            method = 'POST';
        }
        
        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function(response) {
                $('#createCategoriaInsumoModal').modal('hide');
                var cat = response.categoria || response;
                if (id) {
                    $('#id_cat_insumo option[value="' + id + '"]').text(cat.nombre || $('#cat_insumo_nombre').val());
                } else {
                    var newOption = new Option(cat.nombre || $('#cat_insumo_nombre').val(), cat.id_cat_insumo, true, true);
                    $('#id_cat_insumo').append(newOption);
                }
                toastr.success('Categoría ' + (id ? 'actualizada' : 'creada'));
                $('#formCategoriaInsumo')[0].reset();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error');
            }
        });
    });

    // Expandir sección si hay errores
    <?php if($errors->any()): ?>
        <?php if($item->tipo_item == 'producto'): ?>
            $('#card-producto').removeClass('collapsed-card');
        <?php else: ?>
            $('#card-insumo').removeClass('collapsed-card');
        <?php endif; ?>
    <?php endif; ?>
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/item/edit.blade.php ENDPATH**/ ?>