{{-- resources/views/usuarios/partials/modal-create-empleado.blade.php --}}
<div class="modal fade" id="createEmpleadoModal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-panaderia">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--color-primary-medium) 0%, var(--color-primary-dark) 100%);">
                <h5 class="modal-title text-white">
                    <i class="fas fa-user-tie mr-2"></i> Crear Nuevo Empleado
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCrearEmpleado" action="{{ route('empleados.store-ajax') }}" method="POST">
                @csrf
                <div class="modal-body bg-panaderia-light">
                    <div class="alert alert-info animate-fade-in">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Instrucciones:</strong> Complete los datos del nuevo empleado. Los campos marcados con <span class="text-danger">*</span> son obligatorios.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia">
                                    <i class="fas fa-user mr-1"></i>Nombre <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="nombre" class="form-control" 
                                       placeholder="Ej: Juan" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia">
                                    <i class="fas fa-user mr-1"></i>Apellido
                                </label>
                                <input type="text" name="apellido" class="form-control" 
                                       placeholder="Ej: Pérez">
                                <small class="form-text text-muted">Opcional</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia">
                                    <i class="fas fa-phone mr-1"></i>Teléfono
                                </label>
                                <input type="text" name="telefono" class="form-control" 
                                       placeholder="Ej: 123456789">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia">
                                    <i class="fas fa-calendar mr-1"></i>Edad
                                </label>
                                <input type="number" name="edad" class="form-control" min="18" max="100" 
                                       placeholder="Ej: 30">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="text-panaderia">
                            <i class="fas fa-map-marker-alt mr-1"></i>Dirección
                        </label>
                        <input type="text" name="direccion" class="form-control" 
                               placeholder="Ej: Calle Principal #123">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia">
                                    <i class="fas fa-birthday-cake mr-1"></i>Fecha de Nacimiento
                                </label>
                                <input type="date" name="fecha_nac" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-panaderia">
                                    <i class="fas fa-dollar-sign mr-1"></i>Sueldo
                                </label>
                                <input type="number" name="sueldo" class="form-control" step="0.01" min="0" 
                                       placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-panaderia-lighter">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save mr-1"></i> Crear Empleado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>