
<div class="modal fade" id="createCategoriaInsumoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-folder"></i> Nueva Categoría de Insumo
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateCategoriaInsumo" action="<?php echo e(route('modulo-almacen.categorias-insumo.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" 
                               placeholder="Ej: Harinas, Lácteos, Endulzantes..." required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="2" 
                                  placeholder="Descripción opcional de la categoría"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Crear Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/modulo-almacen/partials/modal-categoria-insumo.blade.php ENDPATH**/ ?>