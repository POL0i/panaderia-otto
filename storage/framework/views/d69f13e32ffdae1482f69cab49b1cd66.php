

<?php $__env->startSection('title', 'Panel de Ventas - Panadería Otto'); ?>
<?php $__env->startSection('page-title', 'Ventas'); ?>
<?php $__env->startSection('page-description', 'Registro de ventas a clientes'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-7">
            <!-- Selección de Cliente -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user"></i> Seleccionar Cliente
                        <button type="button" class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#createClienteModal">
                            <i class="fas fa-plus"></i> Nuevo Cliente
                        </button>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row" id="clientesList">
                        <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 mb-3">
                            <div class="card cliente-card" data-id="<?php echo e($cliente->id_cliente); ?>" data-nombre="<?php echo e($cliente->nombre); ?>">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-circle fa-2x mr-3" style="color: #8B4513;"></i>
                                        <div>
                                            <h6 class="mb-0"><?php echo e($cliente->nombre); ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-phone"></i> <?php echo e($cliente->telefono ?? 'N/A'); ?>

                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <input type="hidden" id="selectedCliente" value="">
                    <div id="clienteSeleccionadoInfo" class="mt-2" style="display: none;">
                        <div class="alert alert-info">
                            <i class="fas fa-check-circle"></i> Cliente seleccionado: <strong id="clienteSeleccionadoNombre"></strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agregar Productos -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cart-plus"></i> Agregar Productos</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <label>Almacén</label>
                            <select class="form-control" id="itemAlmacen" required>
                                <option value="">Seleccionar Almacén</option>
                                <?php $__currentLoopData = $almacenes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $almacen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($almacen->id_almacen); ?>"><?php echo e($almacen->nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-link mt-1" data-toggle="modal" data-target="#createAlmacenModal">
                                <i class="fas fa-plus"></i> Nuevo Almacén
                            </button>
                        </div>
                        <div class="col-md-5">
                            <label>Producto</label>
                            <select class="form-control" id="itemSelect" required>
                                <option value="">Seleccionar Producto</option>
                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        // Obtener stock total del producto en todos los almacenes (para mostrar)
                                        $stockTotal = $item->almacenItems->sum('stock');
                                    ?>
                                    <option value="<?php echo e($item->id_item); ?>" 
                                            data-nombre="<?php echo e($item->producto->nombre ?? 'Producto'); ?>"
                                            data-stock-total="<?php echo e($stockTotal); ?>">
                                        <?php echo e($item->producto->nombre ?? 'Producto'); ?> (Stock: <?php echo e($stockTotal); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-link mt-1" data-toggle="modal" data-target="#createProductoModal">
                                <i class="fas fa-plus"></i> Nuevo Producto
                            </button>
                        </div>
                        <div class="col-md-2">
                            <label>Cantidad</label>
                            <input type="number" class="form-control" id="itemCantidad" placeholder="Cantidad" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label>Precio Unitario (Bs.)</label>
                            <input type="number" step="0.01" class="form-control" id="itemPrecio" placeholder="Precio unitario" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="button" class="btn btn-add-item btn-block" onclick="addItemToCart()">
                                <i class="fas fa-plus-circle"></i> Agregar a la Venta
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carrito -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart"></i> Carrito de Ventas
                        <span class="badge badge-primary float-right" id="cartCount">0</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="cartItems" class="table-detalles">
                        <p class="text-muted text-center">No hay productos agregados</p>
                    </div>
                    <div class="total-card mt-3">
                        <div class="row">
                            <div class="col-6"><strong>Total:</strong></div>
                            <div class="col-6 text-right"><strong id="cartTotal">Bs. 0.00</strong></div>
                        </div>
                    </div>
                    <button class="btn btn-success btn-block mt-3" onclick="confirmSale()" id="btnConfirmarVenta" disabled>
                        <i class="fas fa-check-circle"></i> Confirmar Venta
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Historial de Ventas Recientes</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#notasVenta">Notas de Venta</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#detallesVenta">Detalles de Venta</a></li>
                    </ul>
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="notasVenta">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead><tr><th>ID</th><th>Fecha</th><th>Cliente</th><th>Empleado</th><th>Total</th><th>Estado</th><th>Acciones</th></tr></thead>
                                    <tbody>
                                        <?php $__currentLoopData = $notasVenta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($nota->id_nota_venta); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($nota->fecha_venta)->format('d/m/Y H:i')); ?></td>
                                            <td><?php echo e($nota->cliente->nombre ?? 'N/A'); ?></td>
                                            <td><?php echo e($nota->empleado->nombre ?? 'N/A'); ?></td>
                                            <td>Bs. <?php echo e(number_format($nota->monto_total, 2)); ?></td>
                                            <td><span class="badge badge-success"><?php echo e($nota->estado); ?></span></td>
                                            <td><button class="btn btn-sm btn-info" onclick="verDetalleNota(<?php echo e($nota->id_nota_venta); ?>)"><i class="fas fa-eye"></i></button></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="detallesVenta">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead><tr><th>Nota</th><th>Almacén</th><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead>
                                    <tbody>
                                        <?php $__currentLoopData = $detallesVenta; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($detalle->id_nota_venta); ?></td>
                                            <td><?php echo e($detalle->almacen_nombre); ?></td>
                                            <td><?php echo e($detalle->producto_nombre); ?></td>
                                            <td><?php echo e($detalle->cantidad); ?></td>
                                            <td>Bs. <?php echo e(number_format($detalle->precio, 2)); ?></td>
                                            <td>Bs. <?php echo e(number_format($detalle->cantidad * $detalle->precio, 2)); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php echo $__env->make('usuarios.partials.modal-create-cliente', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('modulo-almacen.partials.modal-almacen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('modulo-almacen.partials.modal-categoria-producto', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('modulo-almacen.partials.modal-producto', ['categorias' => $categoriasProducto], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


<div class="modal fade" id="modalDetalleNotaVenta" tabindex="-1" aria-labelledby="modalDetalleNotaVentaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark text-white" style="background: linear-gradient(135deg, #5D3A1A 0%, #8B4513 100%);">
                <h5 class="modal-title" id="modalDetalleNotaVentaLabel">
                    <i class="fas fa-receipt"></i> Comprobante de Venta
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                
                <div class="p-3 border-bottom" style="background-color: #f8f9fa;">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="mb-0"><strong>PANADERÍA OTTO</strong></h4>
                            <small class="text-muted">NIT: 123456789</small><br>
                            <small class="text-muted">Av. Principal #123, Santa Cruz</small><br>
                            <small class="text-muted">Tel: (591) 123-45678</small>
                        </div>
                        <div class="col-6 text-right">
                            <h5><strong>NOTA DE VENTA</strong></h5>
                            <h6><span class="badge badge-success" id="reciboVentaNumero">#001</span></h6>
                            <small id="reciboVentaFecha">Fecha: 01/01/2026</small>
                        </div>
                    </div>
                </div>

                
                <div class="p-3 border-bottom">
                    <div class="row">
                        <div class="col-6">
                            <strong>Cliente:</strong><br>
                            <span id="reciboVentaClienteNombre">Nombre Cliente</span><br>
                            <small id="reciboVentaClienteTelefono">Tel: N/A</small>
                        </div>
                        <div class="col-6 text-right">
                            <strong>Atendido por:</strong><br>
                            <span id="reciboVentaEmpleadoNombre">Nombre Empleado</span><br>
                            <small>ID: <span id="reciboVentaEmpleadoId">1</span></small>
                        </div>
                    </div>
                </div>

                
                <div class="p-3">
                    <h6><i class="fas fa-boxes"></i> Detalle de Productos</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Cant.</th>
                                    <th>Producto</th>
                                    <th>Almacén</th>
                                    <th class="text-right">P. Unit.</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="reciboVentaItemsBody">
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                    <td class="text-right"><strong id="reciboVentaTotal">Bs. 0.00</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                
                <div class="p-3 bg-light border-top">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Gracias por su compra</small><br>
                            <small class="text-muted">Documento generado electrónicamente</small>
                        </div>
                        <div class="col-6 text-right">
                            <small class="text-muted">_________________________</small><br>
                            <small class="text-muted">Firma y sello</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" onclick="imprimirReciboVenta()">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                    <button type="button" class="btn btn-success" id="btnEnviarCorreoVenta">
                        <i class="fas fa-envelope"></i> Enviar por Correo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalEnvioCorreoVenta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-envelope"></i> Enviar Comprobante</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" class="form-control" id="correoDestinoVenta" placeholder="ejemplo@correo.com" required>
                    <small class="form-text text-muted">Se enviará una copia del comprobante a esta dirección.</small>
                </div>
                <input type="hidden" id="idNotaVentaEnvio">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarEnvioVenta">
                    <i class="fas fa-paper-plane"></i> Enviar
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    window.routes = {
        ventasStore: '<?php echo e(route("ventas.store")); ?>',
        ventasClientes: '<?php echo e(route("ventas.clientes")); ?>',
        ventasEnviarCorreo: '<?php echo e(route("ventas.enviar-correo")); ?>'
    };
</script>
<script src="<?php echo e(asset('js/gestion-comercial.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/seccion-ventas/index.blade.php ENDPATH**/ ?>