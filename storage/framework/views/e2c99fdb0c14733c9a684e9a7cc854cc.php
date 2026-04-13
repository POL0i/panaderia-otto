
<div class="modal fade" id="asignarPermisoRolModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title">
                    <i class="fas fa-link"></i> Asignar Permiso a Rol
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formAsignarPermisoRol" action="<?php echo e(route('rol-permisos.store-ajax')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Rol <span class="text-danger">*</span></label>
                        <select name="id_rol" class="form-control" required>
                            <option value="">Seleccionar rol...</option>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($rol->id_rol); ?>"><?php echo e($rol->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Permiso <span class="text-danger">*</span></label>
                        <select name="id_permiso" class="form-control" required>
                            <option value="">Seleccionar permiso...</option>
                            <?php $__currentLoopData = $permisos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permiso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($permiso->id_permiso); ?>"><?php echo e($permiso->nombre); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Descripción (opcional)</label>
                        <textarea name="descripcion" class="form-control" rows="2" 
                                  placeholder="Describe para qué sirve esta asignación..."></textarea>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Al asignar un permiso a un rol, todos los usuarios con ese rol heredarán este permiso.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-link"></i> Asignar Permiso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/usuarios/partials/modal-asignar-permiso-rol.blade.php ENDPATH**/ ?>