
<div class="modal fade" id="gestionarPermisosModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content border-panaderia">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--color-primary-medium) 0%, var(--color-primary-dark) 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-lock mr-2"></i> Gestionar Permisos - <span id="modalUsuarioNombre"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body bg-panaderia-light">
                <input type="hidden" id="usuarioIdInput">
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0 text-panaderia">
                                <i class="fas fa-list mr-2"></i>Permisos disponibles 
                                (<span id="contadorPermisos">0</span> seleccionados)
                            </h6>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="expandAllRoles">
                            <i class="fas fa-chevron-down mr-1"></i> Expandir Todo
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="collapseAllRoles">
                            <i class="fas fa-chevron-up mr-1"></i> Colapsar Todo
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success" id="selectAllPermisos">
                            <i class="fas fa-check-square mr-1"></i> Seleccionar Todo
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="deselectAllPermisos">
                            <i class="fas fa-square mr-1"></i> Deseleccionar Todo
                        </button>
                    </div>
                </div>

                <div class="permisos-container" style="max-height: 450px; overflow-y: auto; border: 1px solid var(--color-accent); border-radius: 10px; padding: 15px; background: white;">
                    <div id="permisosList">
                        <div class="text-center py-4">
                            <i class="fas fa-spinner fa-spin fa-2x text-panaderia"></i>
                            <p class="mt-2">Cargando permisos...</p>
                        </div>
                    </div>
                </div>
                
                
                <div class="alert alert-info mt-3 animate-fade-in">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Permisos actuales del usuario:</strong> 
                    <span id="permisosActualesList" class="ml-2"></span>
                </div>
            </div>
            <div class="modal-footer bg-panaderia-lighter">
                <button type="button" class="btn btn-cancel" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-save" id="btnGuardarPermisos">
                    <i class="fas fa-save mr-1"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/usuarios/partials/modal-gestionar-permisos.blade.php ENDPATH**/ ?>