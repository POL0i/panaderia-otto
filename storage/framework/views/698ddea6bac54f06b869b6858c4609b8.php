<?php $__env->startSection('title', 'Crear Traspaso de Inventario'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-exchange-alt"></i> Nuevo Traspaso</h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="<?php echo e(route('traspasos.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <form action="<?php echo e(route('traspasos.store')); ?>" method="POST" id="formTraspaso">
        <?php echo csrf_field(); ?>
        <div class="row">
            
            <div class="col-md-7">
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-warehouse"></i> Almacenes</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Almacén Origen <span class="text-danger">*</span></label>
                                    <select name="id_almacen_origen" id="almacen_origen" class="form-control" required>
                                        <option value="">Seleccione origen...</option>
                                        <?php $__currentLoopData = $almacenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($alm->id_almacen); ?>" 
                                                    data-tipo="<?php echo e($alm->tipo_almacen); ?>">
                                                <?php echo e($alm->nombre); ?> (<?php echo e($alm->tipo_almacen); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small class="text-muted" id="origen_info"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Almacén Destino <span class="text-danger">*</span></label>
                                    <select name="id_almacen_destino" id="almacen_destino" class="form-control" required>
                                        <option value="">Seleccione destino...</option>
                                        <?php $__currentLoopData = $almacenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($alm->id_almacen); ?>" 
                                                    data-tipo="<?php echo e($alm->tipo_almacen); ?>">
                                                <?php echo e($alm->nombre); ?> (<?php echo e($alm->tipo_almacen); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small class="text-muted" id="destino_info"></small>
                                </div>
                            </div>
                        </div>
                        <div id="validacion_almacenes" class="alert alert-warning" style="display:none;"></div>
                    </div>
                </div>

                
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-box"></i> Agregar Items al Traspaso</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Item</label>
                                    <select id="item_select" class="form-control">
                                        <option value="">Seleccione item...</option>
                                        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($item->id_item); ?>" 
                                                    data-nombre="<?php echo e($item->nombre); ?>"
                                                    data-tipo="<?php echo e($item->tipo_item); ?>"
                                                    data-unidad="<?php echo e($item->unidad_medida); ?>">
                                                <?php echo e($item->nombre); ?> (<?php echo e($item->tipo_item); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input type="number" id="cantidad_input" class="form-control" 
                                           min="0.01" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-success btn-block" onclick="agregarItem()">
                                    <i class="fas fa-plus"></i> 
                                </button>
                            </div>
                        </div>
                        <div id="error_item" class="text-danger mt-2" style="display:none;"></div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart"></i> Items a Traspasar
                            <span class="badge badge-primary float-right" id="cart_count">0</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="cart_items" style="max-height: 350px; overflow-y: auto;">
                            <p class="text-muted text-center">No hay items agregados</p>
                        </div>
                    </div>
                </div>

                
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label>Descripción / Observaciones</label>
                            <textarea name="descripcion" class="form-control" rows="3" 
                                      placeholder="Motivo del traspaso..."><?php echo e(old('descripcion')); ?></textarea>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block mt-3" id="btn_submit" disabled>
                    <i class="fas fa-exchange-alt"></i> Realizar Traspaso
                </button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>

    // Variables globales
let stockOrigen = 0;
let capacidadDisponible = null;
let itemSeleccionado = null;

// Cuando cambia el almacén origen, actualizar información de stock para el item seleccionado
$('#almacen_origen').on('change', function() {
    if (itemSeleccionado) {
        verificarStock();
    }
});

// Cuando cambia el almacén destino, verificar capacidad disponible
$('#almacen_destino').on('change', function() {
    const destinoId = $(this).val();
    if (destinoId) {
        $.get('<?php echo e(route("traspasos.capacidad")); ?>', { id_almacen: destinoId })
            .done(function(res) {
                if (res.success) {
                    capacidadDisponible = res.disponible;
                    let msg = '';
                    if (res.sin_limite) {
                        msg = '✅ Sin límite de capacidad';
                    } else {
                        msg = `📦 Capacidad total: ${res.capacidad} | Ocupado: ${res.stock_actual} | Disponible: ${res.disponible}`;
                    }
                    $('#destino_info').html(msg).removeClass('text-danger text-success').addClass('text-info');
                }
            })
            .fail(() => $('#destino_info').text('⚠️ Error al obtener capacidad').addClass('text-danger'));
    } else {
        $('#destino_info').empty();
        capacidadDisponible = null;
    }
    actualizarBotonSubmit();
});

// Al seleccionar un item, mostrar stock en origen
$('#item_select').on('change', function() {
    const item = $(this).find(':selected');
    if (item.val()) {
        itemSeleccionado = {
            id: item.val(),
            nombre: item.data('nombre'),
            tipo: item.data('tipo'),
            unidad: item.data('unidad')
        };
        verificarStock();
    } else {
        itemSeleccionado = null;
        $('#origen_info').empty();
    }
});

function verificarStock() {
    const origenId = $('#almacen_origen').val();
    if (!origenId || !itemSeleccionado) return;
    
    $.get('<?php echo e(route("traspasos.stock")); ?>', { id_almacen: origenId, id_item: itemSeleccionado.id })
        .done(function(res) {
            if (res.success) {
                stockOrigen = res.stock;
                $('#origen_info').html(`📦 Stock disponible: ${stockOrigen} ${res.unidad}`)
                    .removeClass('text-danger text-success')
                    .addClass(stockOrigen > 0 ? 'text-success' : 'text-danger');
            }
        })
        .fail(() => $('#origen_info').text('⚠️ Error al consultar stock').addClass('text-danger'));
}

function agregarItem() {
    const origenId = $('#almacen_origen').val();
    const destinoId = $('#almacen_destino').val();
    const cantidad = parseFloat($('#cantidad_input').val());
    
    $('#error_item').hide();
    
    if (!origenId || !destinoId) {
        mostrarError('Seleccione ambos almacenes primero.');
        return;
    }
    if (!itemSeleccionado) {
        mostrarError('Seleccione un item.');
        return;
    }
    if (!cantidad || cantidad <= 0) {
        mostrarError('Ingrese una cantidad válida.');
        return;
    }
    
    // Validar stock suficiente
    if (cantidad > stockOrigen) {
        mostrarError(`Stock insuficiente. Disponible: ${stockOrigen} ${itemSeleccionado.unidad}`);
        return;
    }
    
    // Validar capacidad en destino (si aplica)
    if (capacidadDisponible !== null && capacidadDisponible < cantidad) {
        mostrarError(`El almacén destino no tiene suficiente espacio. Disponible: ${capacidadDisponible} unidades (sumando todos los items)`);
        return;
    }
    
    // Validar compatibilidad de tipos (ya lo tienes)
    // ... (código actual de validación de tipos)
    
    // Verificar duplicados
    if (cart.some(i => i.id_item == itemSeleccionado.id)) {
        mostrarError('Este item ya está en la lista.');
        return;
    }
    
    // Agregar al carrito
    cart.push({
        id_item: itemSeleccionado.id,
        nombre: itemSeleccionado.nombre,
        tipo: itemSeleccionado.tipo,
        unidad: itemSeleccionado.unidad,
        cantidad: cantidad,
        stock_origen: stockOrigen,
        stock_nuevo_origen: stockOrigen - cantidad
    });
    
    // Actualizar stockOrigen local (simulado) para evitar que se agregue otra vez el mismo item
    stockOrigen -= cantidad;
    $('#origen_info').html(`📦 Stock disponible después del traspaso: ${stockOrigen} ${itemSeleccionado.unidad}`);
    
    $('#item_select').val('');
    $('#cantidad_input').val('');
    itemSeleccionado = null;
    renderCart();
    actualizarBotonSubmit();
}

let cart = [];

// Validar almacenes al cambiar
$('#almacen_origen, #almacen_destino').on('change', function() {
    validarAlmacenes();
    actualizarBotonSubmit();
});

$('#formTraspaso').on('submit', function(e) {
    // Eliminar campos ocultos anteriores
    $('.detalle-hidden').remove();
    
    // Agregar cada item del carrito como campo oculto
    cart.forEach(function(item, index) {
        $('<input>').attr({
            type: 'hidden',
            name: 'detalles[' + index + '][id_item]',
            value: item.id_item,
            class: 'detalle-hidden'
        }).appendTo('#formTraspaso');
        
        $('<input>').attr({
            type: 'hidden',
            name: 'detalles[' + index + '][cantidad]',
            value: item.cantidad,
            class: 'detalle-hidden'
        }).appendTo('#formTraspaso');
    });
});

function validarAlmacenes() {
    const origen = $('#almacen_origen').find(':selected');
    const destino = $('#almacen_destino').find(':selected');
    const origenId = origen.val();
    const destinoId = destino.val();
    const origenTipo = origen.data('tipo');
    const destinoTipo = destino.data('tipo');
    
    $('#origen_info').text(origenId ? `Tipo: ${origenTipo}` : '');
    $('#destino_info').text(destinoId ? `Tipo: ${destinoTipo}` : '');
    
    let errores = [];
    
    if (origenId && destinoId) {
        // Validar que no sea el mismo almacén
        if (origenId === destinoId) {
            errores.push('❌ El almacén origen y destino no pueden ser el mismo.');
        }
        
        // Validar compatibilidad de tipos
        if (origenTipo && destinoTipo) {
            // Origen "insumo" solo puede traspasar a "insumo" o "mixto"
            // Origen "producto" solo puede traspasar a "producto" o "mixto"
            // Origen "mixto" puede traspasar a cualquiera
        }
    }
    
    if (errores.length > 0) {
        $('#validacion_almacenes').html(errores.join('<br>')).show();
    } else {
        $('#validacion_almacenes').hide();
    }
    
    // Limpiar carrito si cambian los almacenes
    if (cart.length > 0) {
        cart = [];
        renderCart();
    }
}

function agregarItem() {
    const origenTipo = $('#almacen_origen').find(':selected').data('tipo');
    const destinoTipo = $('#almacen_destino').find(':selected').data('tipo');
    const itemSelect = $('#item_select').find(':selected');
    const itemId = itemSelect.val();
    const itemNombre = itemSelect.data('nombre');
    const itemTipo = itemSelect.data('tipo');
    const itemUnidad = itemSelect.data('unidad');
    const cantidad = parseFloat($('#cantidad_input').val());
    
    $('#error_item').hide();
    
    if (!$('#almacen_origen').val() || !$('#almacen_destino').val()) {
        $('#error_item').text('Seleccione ambos almacenes primero.').show();
        return;
    }
    
    if (!itemId) {
        $('#error_item').text('Seleccione un item.').show();
        return;
    }
    
    if (!cantidad || cantidad <= 0) {
        $('#error_item').text('Ingrese una cantidad válida.').show();
        return;
    }
    
    // Validar compatibilidad de tipos
    if (origenTipo === 'insumo' && itemTipo === 'producto') {
        $('#error_item').text(`El almacén origen es de tipo "${origenTipo}" y no contiene productos.`).show();
        return;
    }
    if (origenTipo === 'producto' && itemTipo === 'insumo') {
        $('#error_item').text(`El almacén origen es de tipo "${origenTipo}" y no contiene insumos.`).show();
        return;
    }
    if (destinoTipo === 'insumo' && itemTipo === 'producto') {
        $('#error_item').text(`El almacén destino es de tipo "${destinoTipo}" y no acepta productos.`).show();
        return;
    }
    if (destinoTipo === 'producto' && itemTipo === 'insumo') {
        $('#error_item').text(`El almacén destino es de tipo "${destinoTipo}" y no acepta insumos.`).show();
        return;
    }
    
    // Verificar duplicados
    const existente = cart.find(i => i.id_item == itemId);
    if (existente) {
        $('#error_item').text('Este item ya está en la lista.').show();
        return;
    }
    
    cart.push({
        id_item: itemId,
        nombre: itemNombre,
        tipo: itemTipo,
        unidad: itemUnidad,
        cantidad: cantidad
    });
    
    $('#item_select').val('');
    $('#cantidad_input').val('');
    renderCart();
    actualizarBotonSubmit();
}

function removerItem(index) {
    cart.splice(index, 1);
    renderCart();
    actualizarBotonSubmit();
}

function renderCart() {
    const container = $('#cart_items');
    $('#cart_count').text(cart.length);
    
    if (cart.length === 0) {
        container.html('<p class="text-muted text-center">No hay items agregados</p>');
        return;
    }
    
    let html = '<div class="table-responsive"><table class="table table-sm table-bordered">';
    html += '<thead><tr><th>Item</th><th>Cant.</th><th></th></tr></thead><tbody>';
    
    cart.forEach((item, index) => {
        html += `<tr>
            <td>${item.nombre} <small class="text-muted">(${item.unidad})</small></td>
            <td>${item.cantidad}</td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="removerItem(${index})"><i class="fas fa-trash"></i></button></td>
        </tr>`;
    });
    
    html += '</tbody></table></div>';
    container.html(html);
}

function actualizarBotonSubmit() {
    const btn = $('#btn_submit');
    const origenOk = !!$('#almacen_origen').val();
    const destinoOk = !!$('#almacen_destino').val();
    const mismoAlmacen = $('#almacen_origen').val() === $('#almacen_destino').val();
    
    btn.prop('disabled', !origenOk || !destinoOk || mismoAlmacen || cart.length === 0);
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/inventario/traspasos/create.blade.php ENDPATH**/ ?>