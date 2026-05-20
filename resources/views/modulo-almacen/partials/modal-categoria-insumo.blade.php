{{-- resources/views/modulo-almacen/partials/modal-categoria-insumo.blade.php --}}
<div class="modal fade" id="createCategoriaInsumoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-panaderia shadow-lg">
            <div class="modal-header modal-header-warning">
                <h5 class="modal-title">
                    <i class="fas fa-folder mr-2"></i> Nueva Categoría de Insumo
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateCategoriaInsumo" action="{{ route('modulo-almacen.categorias-insumo.store') }}" method="POST">
                @csrf
                <div class="modal-body bg-panaderia-light">
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-tag mr-1"></i> Nombre <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nombre" class="form-control" 
                               placeholder="Ej: Harinas, Lácteos, Endulzantes..." required>
                    </div>
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-align-left mr-1"></i> Descripción
                        </label>
                        <textarea name="descripcion" class="form-control" rows="2" 
                                  placeholder="Descripción opcional de la categoría"></textarea>
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
</div>