
<div class="modal fade" id="createPermisoModal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-panaderia">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--color-primary-medium) 0%, var(--color-primary-dark) 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-key mr-2"></i> Crear Nuevo Permiso
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCrearPermiso">
                <?php echo csrf_field(); ?>
                <div class="modal-body bg-panaderia-light">
                    <div class="alert alert-info animate-fade-in">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Instrucciones:</strong> Cree un nuevo permiso para asignarlo a roles. Use nombres descriptivos con guiones bajos.
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia" for="nombrePermiso">
                            <i class="fas fa-tag mr-1"></i>Nombre del Permiso <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nombre" id="nombrePermiso" class="form-control" 
                               placeholder="Ej: gestion_comercial_ver" required>
                        <small class="form-text text-muted">
                            Formato recomendado: <code>modulo_accion</code> (ej: almacen_ver, recetas_crear)
                        </small>
                    </div>
                    
                    <div class="alert alert-warning animate-fade-in">
                        <i class="fas fa-lightbulb mr-2"></i>
                        <strong>Sugerencias de permisos:</strong>
                        <ul class="mb-0 mt-2">
                            <li><i class="fas fa-eye mr-1"></i><code>gestion_comercial_ver</code> - Ver módulo comercial</li>
                            <li><i class="fas fa-plus mr-1"></i><code>almacen_crear</code> - Crear en almacén</li>
                            <li><i class="fas fa-edit mr-1"></i><code>inventario_editar</code> - Editar inventario</li>
                            <li><i class="fas fa-trash mr-1"></i><code>produccion_eliminar</code> - Eliminar producción</li>
                            <li><i class="fas fa-chart-bar mr-1"></i><code>reportes_ver</code> - Ver reportes</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer bg-panaderia-lighter">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save mr-1"></i> Crear Permiso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    $('#formCrearPermiso').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: '/permisos/store-ajax',
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createPermisoModal').modal('hide');
                    toastr.success(response.message);
                    form[0].reset();
                    
                    // Agregar al select de permisos
                    var newOption = new Option(response.permiso.nombre, response.permiso.id_permiso, true, true);
                    $('select[name="id_permiso"]').append(newOption.clone());
                    
                    // Volver al modal anterior si existe
                    if (typeof returnToPreviousModal === 'function') {
                        returnToPreviousModal();
                    }
                }
            },
            error: function(xhr) {
                var message = 'Error al crear el permiso';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                toastr.error(message);
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/usuarios/partials/modal-create-permiso.blade.php ENDPATH**/ ?>