{{-- resources/views/modulo-almacen/partials/modal-almacen.blade.php --}}
<div class="modal fade" id="createAlmacenModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title"><i class="fas fa-warehouse"></i> Nuevo Almacén</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateAlmacen" action="{{ route('modulo-almacen.almacenes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Instrucciones:</strong> Crea un nuevo almacén para organizar tu inventario.
                    </div>
                    
                    <div class="form-group">
                        <label>Nombre del Almacén <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" 
                               placeholder="Ej: Almacén Central, Bodega Norte..." required>
                        <small class="text-muted">Nombre único para identificar el almacén.</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Ubicación</label>
                        <input type="text" name="ubicacion" class="form-control" 
                               placeholder="Ej: Calle Principal #123, Planta Baja...">
                        <small class="text-muted">Dirección física o referencia del almacén.</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Capacidad Máxima</label>
                        <input type="number" name="capacidad" class="form-control" step="0.01" min="0" 
                               placeholder="Ej: 1000.00">
                        <small class="text-muted">Capacidad total en unidades (opcional).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Crear Almacén
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>