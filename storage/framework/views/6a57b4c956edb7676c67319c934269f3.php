
<div class="modal fade" id="createInsumoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-panaderia shadow-lg">
            <div class="modal-header modal-header-secondary">
                <h5 class="modal-title">
                    <i class="fas fa-flask mr-2"></i> Nuevo Insumo
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateInsumo" action="<?php echo e(route('modulo-almacen.insumos.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body bg-panaderia-light">
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-tag mr-1"></i> Nombre del Insumo <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nombre" id="insumoNombre" class="form-control" 
                               placeholder="Ej: Harina de trigo, Azúcar, Huevos..." required>
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-folder mr-1"></i> Categoría <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <select name="id_cat_insumo" id="insumoCategoria" class="form-control" required>
                                <option value="">Seleccionar categoría...</option>
                                <?php $__currentLoopData = $categorias ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($categoria->id_cat_insumo); ?>"><?php echo e($categoria->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-warning" 
                                        data-dismiss="modal"
                                        data-toggle="modal" 
                                        data-target="#createCategoriaInsumoModal">
                                    <i class="fas fa-plus"></i> Nueva
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">Si no encuentras la categoría, crea una nueva.</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-balance-scale mr-1"></i> Unidad de Medida <span class="text-danger">*</span>
                        </label>
                        <select name="unidad_medida" id="insumoUnidad" class="form-control" required>
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
                        <label class="text-panaderia">
                            <i class="fas fa-dollar-sign mr-1"></i> Precio de Compra
                        </label>
                        <input type="number" name="precio_compra" id="insumoPrecio" class="form-control" 
                               step="0.01" min="0" placeholder="0.00">
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-1"></i> 
                        Se creará automáticamente un registro en Items como "insumo".
                    </div>
                </div>
                <div class="modal-footer bg-panaderia-lighter">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save mr-1"></i> Crear Insumo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/modulo-almacen/partials/modal-insumo.blade.php ENDPATH**/ ?>