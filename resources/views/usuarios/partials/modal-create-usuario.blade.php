{{-- resources/views/usuarios/partials/modal-create-usuario.blade.php --}}
<div class="modal fade" id="createUsuarioModal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-panaderia">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--color-primary-medium) 0%, var(--color-primary-dark) 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-user-plus mr-2"></i> Crear Nuevo Usuario
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('usuarios.store-access') }}" method="POST" id="formCrearUsuario">
                @csrf
                <div class="modal-body bg-panaderia-light">
                    <div class="alert alert-info animate-fade-in">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Instrucciones:</strong> Complete los datos del nuevo usuario. El tipo de usuario determina si se asocia a un empleado o cliente.
                    </div>
                    
                    <div class="row">
                        {{-- Tipo de Usuario --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia" for="tipo_usuario">
                                    <i class="fas fa-user-tag mr-1"></i>Tipo de Usuario <span class="text-danger">*</span>
                                </label>
                                <select name="tipo_usuario" id="tipo_usuario" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <option value="empleado">Empleado</option>
                                    <option value="cliente">Cliente</option>
                                </select>
                            </div>
                        </div>

                        {{-- Estado --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia" for="estado">
                                    <i class="fas fa-toggle-on mr-1"></i>Estado <span class="text-danger">*</span>
                                </label>
                                <select name="estado" id="estado" class="form-control" required>
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia" for="correo">
                                    <i class="fas fa-envelope mr-1"></i>Correo Electrónico <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="correo" id="correo" class="form-control" 
                                       placeholder="usuario@ejemplo.com" required>
                            </div>
                        </div>

                        {{-- Contraseña --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia" for="contraseña">
                                    <i class="fas fa-lock mr-1"></i>Contraseña <span class="text-danger">*</span>
                                </label>
                                <input type="password" name="contraseña" id="contraseña" class="form-control" 
                                       placeholder="Mínimo 8 caracteres" required minlength="8">
                            </div>
                        </div>

                        {{-- Selector de Empleado/Cliente --}}
                        <div class="col-md-12">
                            <div id="empleado_container" style="display: none;">
                                <div class="form-group">
                                    <label class="text-panaderia" for="id_empleado">
                                        <i class="fas fa-user-tie mr-1"></i>Empleado <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <select name="id_empleado" id="id_empleado" class="form-control">
                                            <option value="">Seleccione un empleado...</option>
                                            @foreach($empleados as $empleado)
                                                <option value="{{ $empleado->id_empleado }}">
                                                    {{ $empleado->nombre }} {{ $empleado->apellido }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-success" data-target="#createEmpleadoModal">
                                                <i class="fas fa-plus"></i> Nuevo
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="cliente_container" style="display: none;">
                                <div class="form-group">
                                    <label class="text-panaderia" for="id_cliente">
                                        <i class="fas fa-user mr-1"></i>Cliente <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <select name="id_cliente" id="id_cliente" class="form-control">
                                            <option value="">Seleccione un cliente...</option>
                                            @foreach($clientes as $cliente)
                                                <option value="{{ $cliente->id_cliente }}">
                                                    {{ $cliente->nombre }} {{ $cliente->apellido ?? '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-success" data-target="#createClienteModal">
                                                <i class="fas fa-plus"></i> Nuevo
                                            </button>
                                        </div>
                                    </div>
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
                        <i class="fas fa-save mr-1"></i> Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>