
<div class="modal fade" id="gestionarPermisosModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">
                    <i class="fas fa-lock"></i> Gestionar Permisos - <span id="modalUsuarioNombre"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="usuarioIdInput" name="usuario_id">
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-filter"></i> Filtrar por Rol</label>
                            <select id="filterRol" class="form-control">
                                <option value="">Todos los roles</option>
                                <?php $__currentLoopData = $roles ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rol): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($rol->id_rol); ?>"><?php echo e($rol->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><i class="fas fa-search"></i> Buscar Permiso</label>
                            <input type="text" id="searchPermiso" class="form-control" placeholder="Buscar permiso...">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6>Permisos disponibles (<span id="contadorPermisos">0</span> seleccionados)</h6>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllPermisos">
                            <i class="fas fa-check-square"></i> Todos
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllPermisos">
                            <i class="fas fa-square"></i> Ninguno
                        </button>
                    </div>
                </div>

                <div class="permisos-container" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; border-radius: 5px; padding: 10px;">
                    <div id="permisosList">
                        <div class="text-center py-3">
                            <i class="fas fa-spinner fa-spin"></i> Cargando permisos...
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Permisos actuales:</strong> <span id="permisosActualesList">Cargando...</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btnGuardarPermisos">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/usuarios/partials/modal-gestionar-permisos.blade.php ENDPATH**/ ?>