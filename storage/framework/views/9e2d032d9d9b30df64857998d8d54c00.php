
<div class="modal fade" id="createCategoriaProductoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title"><i class="fas fa-tags"></i> Nueva Categoría de Producto</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateCategoriaProducto" action="<?php echo e(route('modulo-almacen.categorias-producto.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Instrucciones:</strong> Agrupa tus productos en categorías para organizarlos mejor.
                    </div>
                    
                    <div class="form-group">
                        <label>Nombre de la Categoría <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" 
                               placeholder="Ej: Panes, Pasteles, Bebidas, Postres..." required>
                        <small class="text-muted">Nombre único para la categoría.</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="2" 
                                  placeholder="Describe brevemente esta categoría..."></textarea>
                        <small class="text-muted">Opcional. Ayuda a entender qué productos pertenecen a esta categoría.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save"></i> Crear Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/modulo-almacen/partials/modal-categoria-producto.blade.php ENDPATH**/ ?>