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
                        <select name="id_almacen" id="stockAlmacenSelect" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            <?php $__currentLoopData = $almacenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($alm->id_almacen); ?>" data-tipo="<?php echo e($alm->tipo_almacen); ?>">
                                    <?php echo e($alm->nombre); ?> 
                                    (<?php echo e($alm->tipo_almacen === 'insumo' ? 'Solo Insumos' : ($alm->tipo_almacen === 'producto' ? 'Solo Productos' : 'Mixto')); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <small class="text-muted" id="tipoAlmacenInfo"></small>
                    </div>
                    
                    <div class="form-group">
                        <label>Item (Producto/Insumo) <span class="text-danger">*</span></label>
                        <select name="id_item" id="stockItemSelect" class="form-control" required>
                            <option value="">Primero selecciona un almacén...</option>
                        </select>
                        <small class="text-muted" id="itemFilterInfo"></small>
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
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Datos de items con su tipo
    const itemsData = <?php echo json_encode($items->map(function($item) {
        $nombre = $item->producto ? $item->producto->nombre : ($item->insumo ? $item->insumo->nombre : 'Item #'.$item->id_item);
        return [
            'id' => $item->id_item, 'nombre' => $nombre, 'tipo' => $item->tipo_item
        ];
    })) ?>;
    
    // Cuando se selecciona un almacén
    $('#stockAlmacenSelect').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const tipoAlmacen = selectedOption.data('tipo');
        const almacenNombre = selectedOption.text();
        
        // Limpiar y deshabilitar el select de items mientras se carga
        const $itemSelect = $('#stockItemSelect');
        $itemSelect.empty().append('<option value="">Cargando items...</option>').prop('disabled', true);
        
        // Mostrar información del tipo de almacén
        let tipoInfo = '';
        let filterMessage = '';
        
        if (tipoAlmacen === 'insumo') {
            tipoInfo = '📦 Este almacén solo acepta INSUMOS';
            filterMessage = 'Mostrando solo insumos...';
        } else if (tipoAlmacen === 'producto') {
            tipoInfo = '📦 Este almacén solo acepta PRODUCTOS';
            filterMessage = 'Mostrando solo productos...';
        } else if (tipoAlmacen === 'mixto') {
            tipoInfo = '🌐 Este almacén es MIXTO - Acepta todo tipo de items';
            filterMessage = 'Mostrando todos los items disponibles...';
        }
        
        $('#tipoAlmacenInfo').html(`<i class="fas fa-info-circle"></i> ${tipoInfo}`).removeClass('text-muted text-warning text-success').addClass(
            tipoAlmacen === 'insumo' ? 'text-warning' : (tipoAlmacen === 'producto' ? 'text-success' : 'text-info')
        );
        
        // Filtrar items según el tipo de almacén
        let filteredItems = [];
        
        if (tipoAlmacen === 'mixto') {
            // Mixto: muestra todos los items
            filteredItems = itemsData;
        } else {
            // Filtrar por tipo_item
            filteredItems = itemsData.filter(item => item.tipo === tipoAlmacen);
        }
        
        // Construir el select de items
        $itemSelect.empty();
        
        if (filteredItems.length === 0) {
            $itemSelect.append('<option value="">No hay items disponibles para este almacén</option>');
            $('#itemFilterInfo').html(`<i class="fas fa-exclamation-triangle"></i> ${filterMessage} No se encontraron items.`).addClass('text-danger');
        } else {
            $itemSelect.append('<option value="">Seleccionar item...</option>');
            filteredItems.forEach(item => {
                $itemSelect.append(`<option value="${item.id}">${item.nombre} (${item.tipo === 'producto' ? 'Producto' : 'Insumo'})</option>`);
            });
            $('#itemFilterInfo').html(`<i class="fas fa-filter"></i> ${filterMessage} (${filteredItems.length} items disponibles)`).removeClass('text-danger').addClass('text-muted');
        }
        
        $itemSelect.prop('disabled', false);
    });
    
    // Resetear cuando se cierra el modal
    $('#manageStockModal').on('hidden.bs.modal', function() {
        $('#stockAlmacenSelect').val('').trigger('change');
        $('#stockItemSelect').empty().append('<option value="">Primero selecciona un almacén...</option>');
        $('#tipoAlmacenInfo').empty();
        $('#itemFilterInfo').empty();
        $('input[name="stock"]').val('');
    });
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/modulo-almacen/partials/modal-stock.blade.php ENDPATH**/ ?>