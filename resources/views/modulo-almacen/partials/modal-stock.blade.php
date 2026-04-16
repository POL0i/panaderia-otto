<div class="modal fade" id="manageStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title"><i class="fas fa-boxes"></i> Gestionar Stock</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formManageStock" action="{{ route('modulo-almacen.stock.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Almacén <span class="text-danger">*</span></label>
                        <select name="id_almacen" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            @foreach($almacenes as $alm)
                                <option value="{{ $alm->id_almacen }}">{{ $alm->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Item (Producto/Insumo) <span class="text-danger">*</span></label>
                        <select name="id_item" class="form-control" required>
                            <option value="">Seleccionar...</option>
                            @foreach($items as $item)
                                @php
                                    $nombre = $item->producto ? $item->producto->nombre : ($item->insumo ? $item->insumo->nombre : 'Item #'.$item->id_item);
                                    $tipo = $item->tipo_item;
                                @endphp
                                <option value="{{ $item->id_item }}">{{ $nombre }} ({{ $tipo }})</option>
                            @endforeach
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
</div>