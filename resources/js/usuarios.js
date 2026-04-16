// resources/js/usuarios.js o en tu archivo de scripts

$(document).ready(function() {
    // Mostrar/Ocultar campos según tipo de usuario
    $('#tipo_usuario, #edit_tipo_usuario').on('change', function() {
        const tipo = $(this).val();
        const isEdit = $(this).attr('id') === 'edit_tipo_usuario';
        
        if (tipo === 'empleado') {
            $(isEdit ? '#edit_empleado_container' : '#empleado_container').show();
            $(isEdit ? '#edit_cliente_container' : '#cliente_container').hide();
            $(isEdit ? '#edit_id_empleado' : '#id_empleado').prop('required', true);
            $(isEdit ? '#edit_id_cliente' : '#id_cliente').prop('required', false);
        } else if (tipo === 'cliente') {
            $(isEdit ? '#edit_empleado_container' : '#empleado_container').hide();
            $(isEdit ? '#edit_cliente_container' : '#cliente_container').show();
            $(isEdit ? '#edit_id_empleado' : '#id_empleado').prop('required', false);
            $(isEdit ? '#edit_id_cliente' : '#id_cliente').prop('required', true);
        } else {
            $(isEdit ? '#edit_empleado_container, #edit_cliente_container' : '#empleado_container, #cliente_container').hide();
            $(isEdit ? '#edit_id_empleado, #edit_id_cliente' : '#id_empleado, #id_cliente').prop('required', false);
        }
    });

    // Crear Empleado vía AJAX
    $('#formCrearEmpleado').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        
        $.ajax({
            url: '{{ route("usuarios.store-empleado") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Agregar al select
                    const newOption = new Option(
                        `${response.empleado.nombre} ${response.empleado.apellido}`, 
                        response.empleado.id_empleado, 
                        true, 
                        true
                    );
                    $('#id_empleado, #edit_id_empleado').append(newOption);
                    
                    // Cerrar modal
                    $('#createEmpleadoModal').modal('hide');
                    
                    // Limpiar formulario
                    $('#formCrearEmpleado')[0].reset();
                    
                    // Mostrar notificación
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('Error al crear empleado');
            }
        });
    });

    // Crear Cliente vía AJAX
    $('#formCrearCliente').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        
        $.ajax({
            url: '{{ route("usuarios.store-cliente") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Agregar al select
                    const newOption = new Option(
                        `${response.cliente.nombre} ${response.cliente.apellido || ''}`, 
                        response.cliente.id_cliente, 
                        true, 
                        true
                    );
                    $('#id_cliente, #edit_id_cliente').append(newOption);
                    
                    // Cerrar modal
                    $('#createClienteModal').modal('hide');
                    
                    // Limpiar formulario
                    $('#formCrearCliente')[0].reset();
                    
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('Error al crear cliente');
            }
        });
    });

    // Editar Usuario
    $('.btn-edit-usuario').on('click', function() {
        const userId = $(this).data('id');
        
        $.ajax({
            url: `/usuarios/${userId}/edit`,
            method: 'GET',
            success: function(response) {
                const usuario = response.usuario;
                
                // Llenar datos básicos
                $('#edit_id_usuario').val(usuario.id_usuario);
                $('#edit_correo').val(usuario.correo);
                $('#edit_tipo_usuario').val(usuario.tipo_usuario).trigger('change');
                $('#edit_estado').val(usuario.estado);
                
                if (usuario.tipo_usuario === 'empleado' && usuario.id_empleado) {
                    $('#edit_id_empleado').val(usuario.id_empleado);
                } else if (usuario.tipo_usuario === 'cliente' && usuario.id_cliente) {
                    $('#edit_id_cliente').val(usuario.id_cliente);
                }
                
                // Generar checkboxes de permisos
                let html = '';
                const permisosAgrupados = {};
                
                response.todos_rol_permisos.forEach(rp => {
                    const rolNombre = rp.rol.nombre;
                    if (!permisosAgrupados[rolNombre]) {
                        permisosAgrupados[rolNombre] = [];
                    }
                    permisosAgrupados[rolNombre].push(rp);
                });
                
                for (const [rolNombre, permisos] of Object.entries(permisosAgrupados)) {
                    html += `<div class="mb-3">
                        <strong class="text-primary">${rolNombre}</strong>
                        <hr class="my-1">`;
                    
                    permisos.forEach(rp => {
                        const checked = response.permisos_actuales.includes(rp.id_rol_permiso) ? 'checked' : '';
                        html += `<div class="checkbox">
                            <label>
                                <input type="checkbox" name="rol_permiso_ids[]" value="${rp.id_rol_permiso}" ${checked}>
                                ${rp.permiso.nombre}
                                <small class="text-muted">(${rp.descripcion || 'Sin descripción'})</small>
                            </label>
                        </div>`;
                    });
                    
                    html += `</div>`;
                }
                
                $('#edit_permisos_container').html(html);
                $('#editUsuarioModal').modal('show');
            },
            error: function(xhr) {
                toastr.error('Error al cargar datos del usuario');
            }
        });
    });

    // Actualizar Usuario
    $('#formEditarUsuario').on('submit', function(e) {
        e.preventDefault();
        const userId = $('#edit_id_usuario').val();
        const formData = $(this).serialize();
        
        $.ajax({
            url: `/usuarios/${userId}`,
            method: 'PUT',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#editUsuarioModal').modal('hide');
                    toastr.success(response.message);
                    // Recargar la tabla de usuarios
                    setTimeout(() => location.reload(), 1000);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error al actualizar usuario');
            }
        });
    });
});