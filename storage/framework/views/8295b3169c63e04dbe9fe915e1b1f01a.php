
<div class="modal fade" id="createRolModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">
                    <i class="fas fa-tag"></i> Crear Nuevo Rol
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCrearRol" onsubmit="return false;">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre del Rol <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="rolNombre" class="form-control" 
                               placeholder="Ej: Administrador" required>
                        <small class="form-text text-muted">
                            El nombre del rol debe ser único en el sistema.
                        </small>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Los roles permiten agrupar permisos para asignarlos fácilmente a los usuarios.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-warning" onclick="crearRol()">Crear Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Definir la función globalmente
window.crearRol = function() {
    var nombre = document.getElementById('rolNombre').value;
    
    if (!nombre) {
        alert('El nombre del rol es requerido');
        return;
    }
    
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/roles/store-ajax', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ nombre: nombre })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#createRolModal').modal('hide');
            alert(data.message);
            document.getElementById('rolNombre').value = '';
            setTimeout(() => location.reload(), 1000);
        } else {
            alert('Error: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al crear el rol');
    });
};
</script><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/usuarios/partials/modal-create-rol.blade.php ENDPATH**/ ?>