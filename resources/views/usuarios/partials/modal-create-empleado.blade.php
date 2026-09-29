{{-- resources/views/usuarios/partials/modal-create-empleado.blade.php --}}
<div class="modal fade" id="createEmpleadoModal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content glass-card" style="border-radius: 20px; overflow: hidden; border: 1px solid rgba(255,255,255,0.6); background: rgba(255,255,255,0.95);">
            <div class="modal-header bakery-header" style="background: transparent; border-bottom: 1px solid rgba(74,53,37,0.1); padding: 1.5rem;">
                <h5 class="modal-title font-weight-bold" style="color: #4a3525;">
                    <i class="fas fa-user-tie mr-2" style="color: #c88647;"></i> Crear Nuevo Empleado
                </h5>
                <button type="button" class="close" data-dismiss="modal" style="color: #4a3525;">&times;</button>
            </div>
            <form id="formCrearEmpleado" action="{{ route('empleados.store-ajax') }}" method="POST">
                @csrf
                <div class="modal-body" style="background: transparent; padding: 1.5rem;">
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
                <div class="modal-footer" style="background: transparent; border-top: 1px solid rgba(74,53,37,0.1);">
                    <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius: 50px; color: #8c7361;">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-coffee" style="border-radius: 50px;">
                        <i class="fas fa-save mr-1"></i> Crear Empleado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>