{{-- resources/views/usuarios/partials/modal-edit-usuario.blade.php --}}
<div class="modal fade" id="editUsuarioModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content glass-card" style="border-radius: 20px; overflow: hidden; border: 1px solid rgba(255,255,255,0.6); background: rgba(255,255,255,0.95);">
            <div class="modal-header bakery-header" style="background: transparent; border-bottom: 1px solid rgba(74,53,37,0.1); padding: 1.5rem;">
                <h5 class="modal-title font-weight-bold" style="color: #4a3525;">
                    <i class="fas fa-user-edit mr-2" style="color: #c88647;"></i> Editar Usuario
                </h5>
                <button type="button" class="close" data-dismiss="modal" style="color: #4a3525;">&times;</button>
            </div>
            <form id="formEditarUsuario">
                @csrf
                @method('PUT')
                <div class="modal-body" style="background: transparent; padding: 1.5rem;">
                    <input type="hidden" name="id_usuario" id="edit_id_usuario">
                    
                    <div class="row">
                        {{-- Correo --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia">Correo Electrónico <span class="text-danger">*</span></label>
                                <input type="email" name="correo" id="edit_correo" class="form-control" required>
                            </div>
                        </div>
                        
                        {{-- Contraseña --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia">Nueva Contraseña</label>
                                <input type="password" name="contraseña" id="edit_contraseña" class="form-control" minlength="8">
                                <small class="form-text text-muted">Dejar en blanco para mantener la actual</small>
                            </div>
                        </div>
                        
                        {{-- Tipo de Usuario --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia">Tipo de Usuario <span class="text-danger">*</span></label>
                                <select name="tipo_usuario" id="edit_tipo_usuario" class="form-control" required>
                                    <option value="empleado">Empleado</option>
                                    <option value="cliente">Cliente</option>
                                </select>
                            </div>
                        </div>
                        
                        {{-- Estado --}}
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
                    
                    {{-- Contenedores de Empleado y Cliente FUERA del row anterior --}}
                    {{-- Empleado --}}
                    <div id="edit_empleado_container" style="display: none;">
                        <div class="form-group">
                            <label class="text-panaderia">Empleado <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="id_empleado" id="edit_id_empleado" class="form-control">
                                    <option value="">Seleccione un empleado...</option>
                                    @foreach($empleados as $empleado)
                                        <option value="{{ $empleado->id_empleado }}">
                                            {{ $empleado->nombre }} {{ $empleado->apellido }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-caramel" data-toggle="modal" data-target="#createEmpleadoModal" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                                        <i class="fas fa-plus"></i> Nuevo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cliente --}}
                    <div id="edit_cliente_container" style="display: none;">
                        <div class="form-group">
                            <label class="text-panaderia">Cliente <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="id_cliente" id="edit_id_cliente" class="form-control">
                                    <option value="">Seleccione un cliente...</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id_cliente }}">
                                            {{ $cliente->nombre }} {{ $cliente->apellido ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-caramel" data-toggle="modal" data-target="#createClienteModal" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                                        <i class="fas fa-plus"></i> Nuevo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer" style="background: transparent; border-top: 1px solid rgba(74,53,37,0.1);">
                    <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 50px; color: #8c7361;">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-coffee" style="border-radius: 50px;">
                        <i class="fas fa-save mr-1"></i> Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>