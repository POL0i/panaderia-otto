
<div class="modal fade" id="createCategoriaProductoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-panaderia shadow-lg">
            <div class="modal-header modal-header-info">
                <h5 class="modal-title">
                    <i class="fas fa-tags mr-2"></i> Nueva Categoría de Producto
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateCategoriaProducto" action="<?php echo e(route('modulo-almacen.categorias-producto.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body bg-panaderia-light">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i> 
                        <strong>Instrucciones:</strong> Agrupa tus productos en categorías para organizarlos mejor.
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-tag mr-1"></i> Nombre de la Categoría <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nombre" class="form-control" 
                               placeholder="Ej: Panes, Pasteles, Bebidas, Postres..." required>
                        <small class="text-muted">Nombre único para la categoría.</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-align-left mr-1"></i> Descripción
                        </label>
                        <textarea name="descripcion" class="form-control" rows="2" 
                                  placeholder="Describe brevemente esta categoría..."></textarea>
                        <small class="text-muted">Opcional. Ayuda a entender qué productos pertenecen a esta categoría.</small>
                    </div>
                </div>
                <div class="modal-footer bg-panaderia-lighter">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save mr-1"></i> Crear Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/modulo-almacen/partials/modal-categoria-producto.blade.php ENDPATH**/ ?>