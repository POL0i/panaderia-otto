
<div class="modal fade" id="createProductoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">
                    <i class="fas fa-box"></i> Nuevo Producto
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateProducto" action="<?php echo e(route('modulo-almacen.productos.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre del Producto <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="productoNombre" class="form-control"
                                       placeholder="Ej: Pan Francés, Tarta de Manzana..." required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Precio de Venta <span class="text-danger">*</span></label>
                                <input type="number" name="precio" id="productoPrecio" class="form-control"
                                       step="0.01" min="0" placeholder="0.00" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
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
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="col-md-6">
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
                            </div>
                        </div>
                    </div>

                    
                    <div class="form-group">
                        <label>Imagen del Producto</label>
                        <div class="card">
                            <div class="card-body">
                                
                                <div class="mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="imagen_tipo" id="tipoArchivo" value="file" checked>
                                        <label class="form-check-label" for="tipoArchivo">Subir archivo</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="imagen_tipo" id="tipoUrl" value="url">
                                        <label class="form-check-label" for="tipoUrl">Usar URL</label>
                                    </div>
                                </div>

                                
                                <div class="image-preview mb-2 text-center" id="localImagePreview" style="display: none;">
                                    <img id="localPreviewImg" src="" alt="Vista previa" style="max-width: 150px; max-height: 150px; border-radius: 5px;">
                                </div>

                                
                                <div id="grupoArchivo">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="productoImagen" name="imagen" accept="image/*">
                                        <label class="custom-file-label" for="productoImagen">
                                            <i class="fas fa-upload"></i> Seleccionar imagen
                                        </label>
                                    </div>
                                    <small class="text-muted">
                                        Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB.
                                    </small>
                                </div>

                                
                                <div id="grupoUrl" style="display: none;">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-link"></i></span>
                                        </div>
                                        <input type="url" class="form-control" id="productoImagenUrl" name="imagen_url" placeholder="https://ejemplo.com/imagen.jpg">
                                    </div>
                                    <small class="text-muted">
                                        Pegue la URL completa de una imagen (jpg, png, gif).
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Se creará automáticamente un registro en Items como "producto" con la unidad de medida seleccionada.
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

<style>
.image-preview {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 10px;
}
.custom-file-label::after {
    content: "Examinar";
}
</style>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Cambiar entre archivo y URL
    $('input[name="imagen_tipo"]').on('change', function() {
        const tipo = $(this).val();
        if (tipo === 'file') {
            $('#grupoArchivo').show();
            $('#grupoUrl').hide();
            // Limpiar el campo de URL para no enviar ambos
            $('#productoImagenUrl').val('');
            // Reactivar el input file
            $('#productoImagen').prop('disabled', false);
            // La vista previa se actualizará si se selecciona un archivo nuevamente
            actualizarVistaPrevia();
        } else {
            $('#grupoArchivo').hide();
            $('#grupoUrl').show();
            // Limpiar el input file para no subir archivo
            $('#productoImagen').val('');
            $('#productoImagen').prop('disabled', true);
            // Actualizar label del file input
            $('.custom-file-label').html('<i class="fas fa-upload"></i> Seleccionar imagen');
            // Mostrar vista previa desde URL si tiene algo
            actualizarVistaPreviaUrl();
        }
    });

    // Previsualizar imagen desde archivo
    $('#productoImagen').on('change', function(e) {
        if ($('input[name="imagen_tipo"]:checked').val() !== 'file') return;
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#localPreviewImg').attr('src', e.target.result);
                $('#localImagePreview').show();
            }
            reader.readAsDataURL(file);
        } else {
            $('#localImagePreview').hide();
        }
    });

    // Previsualizar imagen desde URL
    $('#productoImagenUrl').on('input', function() {
        if ($('input[name="imagen_tipo"]:checked').val() !== 'url') return;
        actualizarVistaPreviaUrl();
    });

    function actualizarVistaPreviaUrl() {
        const url = $('#productoImagenUrl').val().trim();
        if (url === '') {
            $('#localImagePreview').hide();
        } else {
            // Intentar cargar la imagen; si falla, ocultar
            $('#localPreviewImg').attr('src', url).off('error').on('error', function() {
                $(this).attr('src', '');
                $('#localImagePreview').hide();
            }).off('load').on('load', function() {
                $('#localImagePreview').show();
            });
        }
    }

    function actualizarVistaPrevia() {
        const tipo = $('input[name="imagen_tipo"]:checked').val();
        if (tipo === 'file') {
            const fileInput = $('#productoImagen')[0];
            if (fileInput.files && fileInput.files[0]) {
                // Disparar el evento change manualmente o usar FileReader
                $('#productoImagen').trigger('change');
            } else {
                $('#localImagePreview').hide();
            }
        } else {
            actualizarVistaPreviaUrl();
        }
    }

    // Actualizar el label del custom file input
    $('.custom-file-input').on('change', function() {
        const fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName ? fileName : '<i class="fas fa-upload"></i> Seleccionar imagen');
    });

    // Limpiar vista previa al abrir el modal
    $('#createProductoModal').on('show.bs.modal', function() {
        // Resetear al estado inicial: archivo
        $('#tipoArchivo').prop('checked', true);
        $('#grupoArchivo').show();
        $('#grupoUrl').hide();
        $('#productoImagen').val('');
        $('#productoImagenUrl').val('');
        $('.custom-file-label').html('<i class="fas fa-upload"></i> Seleccionar imagen');
        $('#localImagePreview').hide();
        $('#localPreviewImg').attr('src', '');
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/modulo-almacen/partials/modal-producto.blade.php ENDPATH**/ ?>