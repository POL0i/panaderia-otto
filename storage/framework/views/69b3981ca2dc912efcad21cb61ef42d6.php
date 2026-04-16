<div class="modal fade" id="manageStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title"><i class="fas fa-boxes"></i> Gestionar Stock</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formManageStock" action="<?php echo e(route('modulo-almacen.stock.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Almacén <span class="text-danger">*</span></label>
                        <select name="id_almacen" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            <?php $__currentLoopData = $almacenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($alm->id_almacen); ?>"><?php echo e($alm->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Item (Producto/Insumo) <span class="text-danger">*</span></label>
                        <select name="id_item" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $nombre = $item->producto ? $item->producto->nombre : ($item->insumo ? $item->insumo->nombre : 'Item #'.$item->id_item);
                                    $tipo = $item->tipo_item;
                                ?>
                                <option value="<?php echo e($item->id_item); ?>"><?php echo e($nombre); ?> (<?php echo e($tipo); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Cantidad (Stock) <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" step="0.01" min="0" required>
                        <small class="text-muted">Si el item ya existe en este almacén, se actualizará el stock.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Guardar Stock</button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/modulo-almacen/partials/modal-stock.blade.php ENDPATH**/ ?>