{{-- Modal para Proveedor --}}
<div class="modal fade" id="modalProveedor" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-truck"></i> Nuevo Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="formCreateProveedor" action="{{ route('compras.proveedor.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tipo de Proveedor</label>
                        <select name="tipo_proveedor" class="form-control" id="tipoProveedorSelect" required>
                            <option value="persona">Persona Natural</option>
                            <option value="empresa">Empresa</option>
                        </select>
                    </div>
                    
                    <!-- Campos para Persona Natural -->
                    <div id="camposPersona">
                        <div class="form-group">
                            <label>Nombre Completo</label>
                            <input type="text" name="nombre_persona" class="form-control" placeholder="Nombre del proveedor">
                        </div>
                    </div>
                    
                    <!-- Campos para Empresa -->
                    <div id="camposEmpresa" style="display: none;">
                        <div class="form-group">
                            <label>Razón Social</label>
                            <input type="text" name="razon_social" class="form-control" placeholder="Razón social de la empresa">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Dirección</label>
                        <textarea name="direccion" class="form-control" rows="2"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Proveedor</button>
                </div>
            </form>
        </div>
    </div>
</div>