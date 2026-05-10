
<div class="modal fade" id="editUsuarioModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-panaderia">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--color-primary-medium) 0%, var(--color-primary-dark) 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-user-edit mr-2"></i> Editar Usuario
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formEditarUsuario">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body bg-panaderia-light">
                    <input type="hidden" name="id_usuario" id="edit_id_usuario">
                    
                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia">Correo Electrónico <span class="text-danger">*</span></label>
                                <input type="email" name="correo" id="edit_correo" class="form-control" required>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia">Nueva Contraseña</label>
                                <input type="password" name="contraseña" id="edit_contraseña" class="form-control" minlength="8">
                                <small class="form-text text-muted">Dejar en blanco para mantener la actual</small>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia">Tipo de Usuario <span class="text-danger">*</span></label>
                                <select name="tipo_usuario" id="edit_tipo_usuario" class="form-control" required>
                                    <option value="empleado">Empleado</option>
                                    <option value="cliente">Cliente</option>
                                </select>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia">Estado <span class="text-danger">*</span></label>
                                <select name="estado" id="edit_estado" class="form-control" required>
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    <div id="edit_empleado_container" style="display: none;">
                        <div class="form-group">
                            <label class="text-panaderia">Empleado <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="id_empleado" id="edit_id_empleado" class="form-control">
                                    <option value="">Seleccione un empleado...</option>
                                    <?php $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empleado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($empleado->id_empleado); ?>">
                                            <?php echo e($empleado->nombre); ?> <?php echo e($empleado->apellido); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createEmpleadoModal">
                                        <i class="fas fa-plus"></i> Nuevo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div id="edit_cliente_container" style="display: none;">
                        <div class="form-group">
                            <label class="text-panaderia">Cliente <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="id_cliente" id="edit_id_cliente" class="form-control">
                                    <option value="">Seleccione un cliente...</option>
                                    <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cliente->id_cliente); ?>">
                                            <?php echo e($cliente->nombre); ?> <?php echo e($cliente->apellido ?? ''); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createClienteModal">
                                        <i class="fas fa-plus"></i> Nuevo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-panaderia-lighter">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save mr-1"></i> Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/usuarios/partials/modal-edit-usuario.blade.php ENDPATH**/ ?>