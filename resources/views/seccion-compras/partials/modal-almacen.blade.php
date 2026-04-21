<div class="modal fade" id="modalAlmacen" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-warehouse"></i> Nuevo Almacén</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formAlmacen" action="{{ route('compras.almacen.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre del Almacén</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Ubicación</label>
                        <input type="text" name="ubicacion" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Almacén</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$('#formAlmacen').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                $('#modalAlmacen').modal('hide');
                toastr.success(response.message);
                refreshAlmacenes();
                $('#formAlmacen')[0].reset();
            }
        },
        error: function(xhr) {
            toastr.error('Error al crear almacén');
        }
    });
});
</script>