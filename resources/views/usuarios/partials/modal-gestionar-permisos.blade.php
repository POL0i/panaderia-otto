{{-- resources/views/usuarios/partials/modal-gestionar-permisos.blade.php --}}
<div class="modal fade" id="gestionarPermisosModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content glass-card" style="border-radius: 20px; overflow: hidden; border: 1px solid rgba(255,255,255,0.6); background: rgba(255,255,255,0.95);">
            <div class="modal-header bakery-header" style="background: transparent; border-bottom: 1px solid rgba(74,53,37,0.1); padding: 1rem 1.5rem;">
                <h5 class="modal-title font-weight-bold" style="color: #4a3525; font-size: 1.1rem;">
                    <i class="fas fa-lock mr-2" style="color: #c88647;"></i> Gestionar Permisos - <span id="modalUsuarioNombre"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" style="color: #4a3525;">&times;</button>
            </div>
            <div class="modal-body" style="background: transparent; padding: 1rem 1.5rem;">
                <input type="hidden" id="usuarioIdInput">
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <h6 class="mb-0 font-weight-bold" style="color: #4a3525; font-size: 0.95rem;">
                                <i class="fas fa-list mr-1"></i>Permisos disponibles 
                                (<span id="contadorPermisos" style="color: #c88647;">0</span>)
                            </h6>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" class="btn btn-sm" id="expandAllRoles" style="background: transparent; border: 1px solid #8c7361; color: #8c7361; padding: 2px 8px; font-size: 0.8rem; border-radius: 12px;">
                            <i class="fas fa-chevron-down"></i> Expandir
                        </button>
                        <button type="button" class="btn btn-sm" id="collapseAllRoles" style="background: transparent; border: 1px solid #8c7361; color: #8c7361; padding: 2px 8px; font-size: 0.8rem; border-radius: 12px;">
                            <i class="fas fa-chevron-up"></i> Colapsar
                        </button>
                        <button type="button" class="btn btn-sm" id="selectAllPermisos" style="background: transparent; border: 1px solid #4a3525; color: #4a3525; padding: 2px 8px; font-size: 0.8rem; border-radius: 12px;">
                            <i class="fas fa-check-square"></i> Todos
                        </button>
                        <button type="button" class="btn btn-sm" id="deselectAllPermisos" style="background: transparent; border: 1px solid #c88647; color: #c88647; padding: 2px 8px; font-size: 0.8rem; border-radius: 12px;">
                            <i class="fas fa-square"></i> Ninguno
                        </button>
                    </div>
                </div>

                <div class="permisos-container" style="max-height: 350px; overflow-y: auto; border: 1px solid rgba(74,53,37,0.1); border-radius: 12px; padding: 10px; background: rgba(255,255,255,0.5);">
                    <div id="permisosList">
                        <div class="text-center py-3">
                            <i class="fas fa-spinner fa-spin text-muted"></i>
                            <p class="mt-2 text-muted" style="font-size: 0.85rem;">Cargando permisos...</p>
                        </div>
                    </div>
                </div>
                
                {{-- Leyenda de permisos actuales --}}
                <div class="alert mt-2 mb-0" style="background: rgba(200, 134, 71, 0.1); border: none; border-radius: 12px; font-size: 0.85rem; padding: 10px;">
                    <i class="fas fa-info-circle mr-1" style="color: #c88647;"></i>
                    <strong style="color: #4a3525;">Permisos actuales:</strong> 
                    <span id="permisosActualesList" class="ml-1 text-muted"></span>
                </div>
            </div>
            <div class="modal-footer" style="background: transparent; border-top: 1px solid rgba(74,53,37,0.1); padding: 0.75rem 1.5rem;">
                <button type="button" class="btn btn-light btn-sm" data-dismiss="modal" style="border-radius: 50px; color: #8c7361;">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-coffee btn-sm" id="btnGuardarPermisos" style="border-radius: 50px;">
                    <i class="fas fa-save mr-1"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>