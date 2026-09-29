{{-- resources/views/usuarios/partials/modal-create-usuario.blade.php --}}
<div class="modal fade" id="createUsuarioModal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content glass-card" style="border-radius: 20px; overflow: hidden; border: 1px solid rgba(255,255,255,0.6); background: rgba(255,255,255,0.95);">
            <div class="modal-header bakery-header" style="background: transparent; border-bottom: 1px solid rgba(74,53,37,0.1); padding: 1.5rem;">
                <h5 class="modal-title font-weight-bold" style="color: #4a3525;">
                    <i class="fas fa-user-plus mr-2" style="color: #c88647;"></i> Crear Nuevo Usuario
                </h5>
                <button type="button" class="close" data-dismiss="modal" style="color: #4a3525;">&times;</button>
            </div>
            <form action="{{ route('usuarios.store-access') }}" method="POST" id="formCrearUsuario">
                @csrf
                <div class="modal-body" style="background: transparent; padding: 1.5rem;">
                    <div class="alert alert-info animate-fade-in">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Instrucciones:</strong> Complete los datos del nuevo usuario. El tipo de usuario determina si se asocia a un empleado o cliente.
                    </div>
                    
                    <div class="row">
                        {{-- ✅ Tipo de Usuario (CORREGIDO) --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia" for="tipo_usuario">
                                    <i class="fas fa-user-tag mr-1"></i>Tipo de Usuario <span class="text-danger">*</span>
                                </label>
                                <select name="tipo_usuario" id="tipo_usuario" class="form-control" required>
                                    <option value="">Seleccione tipo...</option>
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
                                <div class="input-group">
                                    <input type="password" name="contraseña" id="contraseña" class="form-control" 
                                        placeholder="Mínimo 8 caracteres" required minlength="8">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="togglePasswordModal" tabindex="-1">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                {{-- Requisitos de contraseña --}}
                                <div class="requirement-list mt-2">
                                    <small class="text-muted">La contraseña debe cumplir:</small>
                                    <ul class="list-unstyled mb-0 mt-1" style="font-size: 0.8rem;">
                                        <li id="modal-req-length" class="text-danger">
                                            <i class="fas fa-times-circle"></i> Mínimo 8 caracteres
                                        </li>
                                        <li id="modal-req-uppercase" class="text-danger">
                                            <i class="fas fa-times-circle"></i> Al menos 1 mayúscula
                                        </li>
                                        <li id="modal-req-lowercase" class="text-danger">
                                            <i class="fas fa-times-circle"></i> Al menos 1 minúscula
                                        </li>
                                        <li id="modal-req-number" class="text-danger">
                                            <i class="fas fa-times-circle"></i> Al menos 2 números
                                        </li>
                                        <li id="modal-req-special" class="text-danger">
                                            <i class="fas fa-times-circle"></i> Al menos 1 carácter especial (!@#$%^&*)
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- ✅ Contenedores de Empleado y Cliente (se muestran según tipo) --}}
                        <div class="col-md-12">
                            {{-- Contenedor de Empleado --}}
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
                                            <button type="button" class="btn btn-caramel" data-toggle="modal" data-target="#createEmpleadoModal" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                                                <i class="fas fa-plus"></i> Nuevo
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Contenedor de Cliente --}}
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
                                            <button type="button" class="btn btn-caramel" data-toggle="modal" data-target="#createClienteModal" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                                                <i class="fas fa-plus"></i> Nuevo
                                            </button>
                                        </div>
                                    </div>
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
                        <i class="fas fa-save mr-1"></i> Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
