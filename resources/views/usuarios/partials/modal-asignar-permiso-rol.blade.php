{{-- resources/views/usuarios/partials/modal-asignar-permiso-rol.blade.php --}}
<div class="modal fade" id="asignarPermisoRolModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-panaderia">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--color-primary-medium) 0%, var(--color-primary-dark) 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-link mr-2"></i> Asignar Permiso a Rol
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formAsignarPermisoRol" action="{{ route('rol-permisos.store-ajax') }}" method="POST">
                @csrf
                <div class="modal-body bg-panaderia-light">
                    {{-- Selector de Rol con botón para crear nuevo --}}
                    <div class="form-group">
                        <label class="text-panaderia"><i class="fas fa-tag mr-1"></i>Rol <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="id_rol" class="form-control" required>
                                <option value="">Seleccionar rol...</option>
                                @foreach($roles as $rol)
                                    <option value="{{ $rol->id_rol }}">{{ $rol->nombre }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createRolModal" data-dismiss="modal">
                                    <i class="fas fa-plus"></i> Nuevo
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Selector de Permiso con botón para crear nuevo --}}
                    <div class="form-group">
                        <label class="text-panaderia"><i class="fas fa-key mr-1"></i>Permiso <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="id_permiso" class="form-control" required>
                                <option value="">Seleccionar permiso...</option>
                                @foreach($permisos as $permiso)
                                    <option value="{{ $permiso->id_permiso }}">{{ $permiso->nombre }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createPermisoModal" data-dismiss="modal">
                                    <i class="fas fa-plus"></i> Nuevo
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia"><i class="fas fa-toggle-on mr-1"></i>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia"><i class="fas fa-align-left mr-1"></i>Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="2" 
                                  placeholder="Opcional: describe esta asignación..."></textarea>
                    </div>
                    
                    <div class="alert alert-warning animate-fade-in">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Los usuarios con este rol heredarán automáticamente este permiso.
                    </div>
                </div>
                <div class="modal-footer bg-panaderia-lighter">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-link mr-1"></i> Asignar Permiso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>