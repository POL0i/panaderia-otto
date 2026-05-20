{{-- resources/views/modulo-almacen/partials/modal-stock.blade.php --}}
<div class="modal fade" id="manageStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-panaderia shadow-lg">
            <div class="modal-header modal-header-danger">
                <h5 class="modal-title">
                    <i class="fas fa-boxes mr-2"></i> Gestionar Stock
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formManageStock" action="{{ route('modulo-almacen.stock.store') }}" method="POST">
                @csrf
                <div class="modal-body bg-panaderia-light">
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-warehouse mr-1"></i> Almacén <span class="text-danger">*</span>
                        </label>
                        <select name="id_almacen" id="stockAlmacenSelect" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            @foreach($almacenes as $alm)
                                <option value="{{ $alm->id_almacen }}" data-tipo="{{ $alm->tipo_almacen }}">
                                    {{ $alm->nombre }} 
                                    ({{ $alm->tipo_almacen === 'insumo' ? 'Solo Insumos' : ($alm->tipo_almacen === 'producto' ? 'Solo Productos' : 'Mixto') }})
                                </option>
                            @endforeach
                        </select>
                        <small id="tipoAlmacenInfo"></small>
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-box mr-1"></i> Item (Producto/Insumo) <span class="text-danger">*</span>
                        </label>
                        <select name="id_item" id="stockItemSelect" class="form-control" required>
                            <option value="">Primero selecciona un almacén...</option>
                        </select>
                        <small id="itemFilterInfo"></small>
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-cubes mr-1"></i> Cantidad (Stock) <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="stock" class="form-control" step="0.01" min="0" required>
                        <small class="text-muted">Si el item ya existe en este almacén, se actualizará el stock.</small>
                    </div>
                </div>
                <div class="modal-footer bg-panaderia-lighter">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save mr-1"></i> Guardar Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const itemsData = @json($items->map(function($item) {
        return [
            'id' => $item->id_item,
            'nombre' => $item->nombre ?? 'Item #' . $item->id_item,
            'tipo' => $item->tipo_item
        ];
    }));
    
    $('#stockAlmacenSelect').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const tipoAlmacen = selectedOption.data('tipo');
        
        const $itemSelect = $('#stockItemSelect');
        $itemSelect.empty().append('<option value="">Cargando items...</option>').prop('disabled', true);
        
        let tipoInfo = '';
        let filterMessage = '';
        
        if (tipoAlmacen === 'insumo') {
            tipoInfo = '📦 Este almacén solo acepta INSUMOS';
            filterMessage = 'Mostrando solo insumos...';
        } else if (tipoAlmacen === 'producto') {
            tipoInfo = '📦 Este almacén solo acepta PRODUCTOS';
            filterMessage = 'Mostrando solo productos...';
        } else {
            tipoInfo = '🌐 Este almacén es MIXTO - Acepta todo tipo de items';
            filterMessage = 'Mostrando todos los items disponibles...';
        }
        
        $('#tipoAlmacenInfo').html('<i class="fas fa-info-circle mr-1"></i> ' + tipoInfo)
            .removeClass('text-muted text-warning text-success text-info')
            .addClass(tipoAlmacen === 'insumo' ? 'text-warning' : (tipoAlmacen === 'producto' ? 'text-success' : 'text-info'));
        
        let filteredItems = (tipoAlmacen === 'mixto') 
            ? itemsData 
            : itemsData.filter(item => item.tipo === tipoAlmacen);
        
        $itemSelect.empty();
        
        if (filteredItems.length === 0) {
            $itemSelect.append('<option value="">No hay items disponibles</option>');
            $('#itemFilterInfo')
                .html('<i class="fas fa-exclamation-triangle mr-1"></i> ' + filterMessage + ' No se encontraron items.')
                .addClass('text-danger');
        } else {
            $itemSelect.append('<option value="">Seleccionar item...</option>');
            filteredItems.forEach(item => {
                $itemSelect.append(
                    '<option value="' + item.id + '">' + item.nombre + 
                    ' (' + (item.tipo === 'producto' ? 'Producto' : 'Insumo') + ')</option>'
                );
            });
            $('#itemFilterInfo')
                .html('<i class="fas fa-filter mr-1"></i> ' + filterMessage + ' (' + filteredItems.length + ' items)')
                .removeClass('text-danger')
                .addClass('text-muted');
        }
        
        $itemSelect.prop('disabled', false);
    });
    
    // Reset al cerrar
    $('#manageStockModal').on('hidden.bs.modal', function() {
        $('#stockAlmacenSelect').val('').trigger('change');
        $('#stockItemSelect').empty().append('<option value="">Primero selecciona un almacén...</option>');
        $('#tipoAlmacenInfo').empty();
        $('#itemFilterInfo').empty();
        $('input[name="stock"]').val('');
    });
});
</script>
@endpush