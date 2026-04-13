{{-- resources/views/usuarios/partials/modal-create-permiso.blade.php --}}
<div class="modal fade" id="createPermisoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">
                    <i class="fas fa-key"></i> Crear Nuevo Permiso
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCrearPermiso">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre del Permiso <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombrePermiso" class="form-control" 
                               placeholder="Ej: gestion_comercial_ver, almacen_crear, etc." required>
                        <small class="form-text text-muted">
                            Usa nombres descriptivos con guiones bajos. Ej: modulo_accion
                        </small>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb"></i> 
                        <strong>Sugerencias de permisos:</strong>
                        <ul class="mb-0 mt-2">
                            <li><code>gestion_comercial_ver</code> - Ver módulo comercial</li>
                            <li><code>almacen_crear</code> - Crear en almacén</li>
                            <li><code>inventario_editar</code> - Editar inventario</li>
                            <li><code>produccion_eliminar</code> - Eliminar producción</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Crear Permiso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formCrearPermiso');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // ✅ Evitar envío tradicional
            
            // Obtener el token CSRF
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Crear FormData
            const formData = new FormData(form);
            
            // Enviar con fetch API
            fetch('/permisos/store-ajax', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cerrar modal
                    $('#createPermisoModal').modal('hide');
                    
                    // Mostrar mensaje de éxito
                    if (typeof toastr !== 'undefined') {
                        toastr.success(data.message);
                    } else {
                        alert(data.message);
                    }
                    
                    // Limpiar formulario
                    form.reset();
                    
                    // Recargar página después de 1.5 segundos
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    alert('Error: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            });
        });
    }
});
</script>

<!-- Versión con jQuery por si prefieres -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#formCrearPermiso').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: '/permisos/store-ajax',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#createPermisoModal').modal('hide');
                    
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    } else {
                        alert(response.message);
                    }
                    
                    $('#formCrearPermiso')[0].reset();
                    
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                var message = 'Error al crear el permiso';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    message = Object.values(errors).flat().join('\n');
                }
                alert(message);
            }
        });
    });
});
</script>