
<div class="modal fade" id="createClienteModal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-panaderia">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--color-primary-medium) 0%, var(--color-primary-dark) 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-user mr-2"></i> Crear Nuevo Cliente
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCrearCliente" action="<?php echo e(route('usuarios.store-cliente')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body bg-panaderia-light">
                    <div class="alert alert-info animate-fade-in">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Instrucciones:</strong> Complete los datos del nuevo cliente.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia">
                                    <i class="fas fa-user mr-1"></i>Nombre <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre" class="form-control" 
                                       placeholder="Ej: María" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia">
                                    <i class="fas fa-user mr-1"></i>Apellido
                                </label>
                                <input type="text" name="apellido" class="form-control" 
                                       placeholder="Ej: González">
                                <small class="form-text text-muted">Opcional</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-phone mr-1"></i>Teléfono
                        </label>
                        <input type="text" name="telefono" class="form-control" 
                               placeholder="Ej: 987654321">
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-map-marker-alt mr-1"></i>Dirección
                        </label>
                        <input type="text" name="direccion" class="form-control" 
                               placeholder="Ej: Avenida Principal #456">
                    </div>
                </div>
                <div class="modal-footer bg-panaderia-lighter">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save mr-1"></i> Crear Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/usuarios/partials/modal-create-cliente.blade.php ENDPATH**/ ?>