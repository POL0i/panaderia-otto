
<div class="modal fade" id="modalProveedor" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-panaderia shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-truck"></i> Nuevo Proveedor
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="formCreateProveedor" action="<?php echo e(route('compras.proveedor.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body bg-panaderia-light">
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-tag mr-1"></i> Tipo de Proveedor
                        </label>
                        <select name="tipo_proveedor" class="form-control" id="tipoProveedorSelect" required>
                            <option value="persona">Persona Natural</option>
                            <option value="empresa">Empresa</option>
                        </select>
                    </div>
                    
                    
                    <div id="camposPersona">
                        <div class="form-group">
                            <label class="text-panaderia">
                                <i class="fas fa-user mr-1"></i> Nombre Completo
                            </label>
                            <input type="text" name="nombre_persona" class="form-control" 
                                   placeholder="Nombre del proveedor">
                        </div>
                    </div>
                    
                    
                    <div id="camposEmpresa" style="display: none;">
                        <div class="form-group">
                            <label class="text-panaderia">
                                <i class="fas fa-building mr-1"></i> Razón Social
                            </label>
                            <input type="text" name="razon_social" class="form-control" 
                                   placeholder="Razón social de la empresa">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-phone mr-1"></i> Teléfono
                        </label>
                        <input type="text" name="telefono" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-map-marker-alt mr-1"></i> Dirección
                        </label>
                        <textarea name="direccion" class="form-control" rows="2"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-envelope mr-1"></i> Correo Electrónico
                        </label>
                        <input type="email" name="correo" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-panaderia-lighter">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save mr-1"></i> Guardar Proveedor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Alternar campos según tipo de proveedor
    $('#tipoProveedorSelect').on('change', function() {
        if ($(this).val() === 'persona') {
            $('#camposPersona').show();
            $('#camposEmpresa').hide();
        } else {
            $('#camposPersona').hide();
            $('#camposEmpresa').show();
        }
    });
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/seccion-compras/partials/modal-proveedor.blade.php ENDPATH**/ ?>