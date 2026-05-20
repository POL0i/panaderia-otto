
<div class="modal fade" id="createAlmacenModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-panaderia shadow-lg">
            <div class="modal-header modal-header-success">
                <h5 class="modal-title">
                    <i class="fas fa-warehouse mr-2"></i> Nuevo Almacén
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateAlmacen" action="<?php echo e(route('modulo-almacen.almacenes.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body bg-panaderia-light">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i> 
                        <strong>Instrucciones:</strong> Crea un nuevo almacén para organizar tu inventario.
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-tag mr-1"></i> Nombre del Almacén <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nombre" class="form-control" 
                               placeholder="Ej: Almacén Central, Bodega Norte..." required>
                        <small class="text-muted">Nombre único para identificar el almacén.</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-cubes mr-1"></i> Tipo de Almacén <span class="text-danger">*</span>
                        </label>
                        <select name="tipo_almacen" class="form-control" required>
                            <option value="mixto">Mixto (Insumos y Productos)</option>
                            <option value="insumo">Solo Insumos</option>
                            <option value="producto">Solo Productos</option>
                        </select>
                        <small class="text-muted">
                            <i class="fas fa-info-circle mr-1"></i> Define qué tipo de items podrás almacenar aquí.
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-map-marker-alt mr-1"></i> Ubicación
                        </label>
                        <input type="text" name="ubicacion" class="form-control" 
                               placeholder="Ej: Calle Principal #123, Planta Baja...">
                        <small class="text-muted">Dirección física o referencia del almacén.</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-weight-hanging mr-1"></i> Capacidad Máxima
                        </label>
                        <input type="number" name="capacidad" class="form-control" step="0.01" min="0" 
                               placeholder="Ej: 1000.00">
                        <small class="text-muted">Capacidad total en unidades (opcional).</small>
                    </div>
                </div>
                <div class="modal-footer bg-panaderia-lighter">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save mr-1"></i> Crear Almacén
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/modulo-almacen/partials/modal-almacen.blade.php ENDPATH**/ ?>