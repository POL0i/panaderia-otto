{{-- resources/views/usuarios/partials/modal-create-rol.blade.php --}}
<div class="modal fade" id="createRolModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content border-panaderia">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--color-primary-medium) 0%, var(--color-primary-dark) 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-tag mr-2"></i> Nuevo Rol
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCrearRol">
                @csrf
                <div class="modal-body bg-panaderia-light">
                    <div class="form-group">
                        <label class="text-panaderia">Nombre del Rol <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" 
                               placeholder="Ej: Administrador, Gerente..." required>
                    </div>
                </div>
                <div class="modal-footer bg-panaderia-lighter">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-save">Crear Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>