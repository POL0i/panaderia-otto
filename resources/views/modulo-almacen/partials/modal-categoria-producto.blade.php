{{-- resources/views/modulo-almacen/partials/modal-categoria-producto.blade.php --}}
<div class="modal fade" id="createCategoriaProductoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-info">
                <h5 class="modal-title"><i class="fas fa-tags mr-2"></i> Nueva Categoría de Producto</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateCategoriaProducto" action="{{ route('modulo-almacen.categorias-producto.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i> 
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
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save mr-1"></i> Crear Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>