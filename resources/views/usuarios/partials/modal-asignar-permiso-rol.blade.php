{{-- resources/views/usuarios/partials/modal-asignar-permiso-rol.blade.php --}}
<div class="modal fade" id="asignarPermisoRolModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title">
                    <i class="fas fa-link"></i> Asignar Permiso a Rol
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formAsignarPermisoRol" action="{{ route('rol-permisos.store-ajax') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Rol <span class="text-danger">*</span></label>
                        <select name="id_rol" class="form-control" required>
                            <option value="">Seleccionar rol...</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->id_rol }}">{{ $rol->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Permiso <span class="text-danger">*</span></label>
                        <select name="id_permiso" class="form-control" required>
                            <option value="">Seleccionar permiso...</option>
                            @foreach($permisos as $permiso)
                                <option value="{{ $permiso->id_permiso }}">{{ $permiso->nombre }}</option>
                            @endforeach
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
</div>