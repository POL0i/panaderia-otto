@extends('layouts.adminlte')

@section('title', 'Panel de Ventas - Panadería Otto')
@section('page-title', 'Ventas')
@section('page-description', 'Registro de ventas a clientes')

@push('styles')
<style>
    .producto-card-modal {
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 15px;
        border: 1px solid #e0e0e0;
    }
    .producto-card-modal:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-color: #8B4513;
    }
    .producto-card-modal.selected {
        border: 2px solid #28a745;
        background-color: #f0fff0;
    }
    .producto-imagen-modal {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }
    .stock-badge {
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 20px;
    }
    .stock-suficiente { background: #d4edda; color: #155724; }
    .stock-bajo { background: #fff3cd; color: #856404; }
    .stock-agotado { background: #f8d7da; color: #721c24; }
</style>
@endpush

@section('content')
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
                        @foreach($clientes as $cliente)
                        <div class="col-md-6 mb-3">
                            <div class="card cliente-card" data-id="{{ $cliente->id_cliente }}" data-nombre="{{ $cliente->nombre }}">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-circle fa-2x mr-3" style="color: #8B4513;"></i>
                                        <div>
                                            <h6 class="mb-0">{{ $cliente->nombre }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-phone"></i> {{ $cliente->telefono ?? 'N/A' }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
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
                    <!-- Botón para abrir el modal de selección -->
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary btn-block" id="btnSeleccionarProducto">
                                <i class="fas fa-search"></i> Seleccionar Producto por Almacén
                            </button>
                        </div>
                    </div>
                    
                    <!-- Información del producto seleccionado -->
                    <div class="row mt-3" id="productoSeleccionadoInfo" style="display: none;">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <img id="productoSeleccionadoImg" src="" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; margin-right: 15px;">
                                    <div class="flex-grow-1">
                                        <strong id="productoSeleccionadoNombre"></strong><br>
                                        <small>Almacén: <span id="productoSeleccionadoAlmacen"></span></small><br>
                                        <small>Stock disponible: <span id="productoSeleccionadoStock"></span></small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger" id="btnLimpiarSeleccion">
                                        <i class="fas fa-times"></i> Cambiar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campos ocultos para almacenar IDs seleccionados -->
                    <input type="hidden" id="selectedAlmacenId" value="">
                    <input type="hidden" id="selectedItemId" value="">
                    <input type="hidden" id="selectedStock" value="0">
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label>Cantidad <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="itemCantidad" placeholder="Cantidad" min="1" required>
                            <small class="text-muted" id="maxStockMsg"></small>
                        </div>
                        <div class="col-md-6">
                            <label>Precio Unitario (Bs.) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="itemPrecio" placeholder="Precio unitario" required>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="button" class="btn btn-add-item btn-block" onclick="addItemToCart()" id="btnAgregarCarrito" disabled>
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
                                        @foreach($notasVenta as $nota)
                                        <tr>
                                            <td>{{ $nota->id_nota_venta }}</td>
                                            <td>{{ \Carbon\Carbon::parse($nota->fecha_venta)->format('d/m/Y H:i') }}</td>
                                            <td>{{ $nota->cliente->nombre ?? 'N/A' }}</td>
                                            <td>{{ $nota->empleado->nombre ?? 'N/A' }}</td>
                                            <td>Bs. {{ number_format($nota->monto_total, 2) }}</td>
                                            <td><span class="badge badge-success">{{ $nota->estado }}</span></td>
                                            <td><button class="btn btn-sm btn-info" onclick="verDetalleNota({{ $nota->id_nota_venta }})"><i class="fas fa-eye"></i></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="detallesVenta">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead><tr><th>Nota</th><th>Almacén</th><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead>
                                    <tbody>
                                        @foreach($detallesVenta as $detalle)
                                        <tr>
                                            <td>{{ $detalle->id_nota_venta }}</td>
                                            <td>{{ $detalle->almacen_nombre }}</td>
                                            <td>{{ $detalle->producto_nombre }}</td>
                                            <td>{{ $detalle->cantidad }}</td>
                                            <td>Bs. {{ number_format($detalle->precio, 2) }}</td>
                                            <td>Bs. {{ number_format($detalle->cantidad * $detalle->precio, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Selección de Producto por Almacén --}}
    <div class="modal fade" id="seleccionProductoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-boxes"></i> Seleccionar Producto por Almacén
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Filtrar por Almacén</label>
                                <select id="filtroAlmacenModal" class="form-control">
                                    <option value="">Todos los almacenes</option>
                                    @foreach($almacenes as $almacen)
                                        <option value="{{ $almacen->id_almacen }}">{{ $almacen->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Buscar Producto</label>
                                <input type="text" id="buscarProductoModal" class="form-control" placeholder="Nombre del producto...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="button" class="btn btn-secondary btn-block" id="btnLimpiarFiltros">
                                    <i class="fas fa-eraser"></i> Limpiar Filtros
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" id="productosGrid" style="max-height: 400px; overflow-y: auto;">
                        <!-- Los productos se cargarán aquí dinámicamente -->
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p>Cargando productos...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODALES REUTILIZADOS --}}
@include('usuarios.partials.modal-create-cliente')
@include('modulo-almacen.partials.modal-almacen')
@include('modulo-almacen.partials.modal-categoria-producto')
@include('modulo-almacen.partials.modal-producto', ['categorias' => $categoriasProducto])

{{-- Modal Detalle Nota Venta (estilo recibo) --}}
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
                {{-- Encabezado del recibo --}}
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

                {{-- Información del cliente y empleado --}}
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

                {{-- Tabla de productos --}}
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
                                {{-- Se llena con JavaScript --}}
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

                {{-- Pie del recibo --}}
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

{{-- Modal para ingresar correo de envío --}}
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

@endsection

@push('scripts')
<script>

    $(document).ready(function() {
        // Variables globales
        let productosData = [];
        
        // Cargar productos al abrir el modal
        $('#btnSeleccionarProducto').on('click', function() {
            cargarProductosParaModal();
            $('#seleccionProductoModal').modal('show');
        });
        
        // Función para cargar productos
        function cargarProductosParaModal() {
            $('#productosGrid').html('<div class="col-12 text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Cargando productos...</p></div>');
            
            $.ajax({
                url: '{{ route("ventas.getProductosConStock") }}',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        productosData = response.productos;
                        renderProductosGrid(productosData);
                    } else {
                        $('#productosGrid').html('<div class="col-12 text-center text-danger">Error al cargar productos</div>');
                    }
                },
                error: function() {
                    $('#productosGrid').html('<div class="col-12 text-center text-danger">Error al cargar productos</div>');
                }
            });
        }
        
        // Renderizar productos en grid
        function renderProductosGrid(productos) {
            if (productos.length === 0) {
                $('#productosGrid').html('<div class="col-12 text-center text-muted py-5">No hay productos disponibles</div>');
                return;
            }
            
            let html = '';
            productos.forEach(function(producto) {
                const stockClass = producto.stock > 10 ? 'stock-suficiente' : (producto.stock > 0 ? 'stock-bajo' : 'stock-agotado');
                const stockText = producto.stock > 0 ? `${producto.stock} unidades` : 'Agotado';
                
                // Usar imagen o placeholder
                const imagenUrl = producto.imagen && producto.imagen !== '' 
                    ? producto.imagen 
                    : 'https://placehold.co/80x80/8B4513/white?text=Producto';
                
                html += `
                    <div class="col-md-6 col-lg-4">
                        <div class="producto-card-modal p-2" 
                            data-almacen-id="${producto.id_almacen}"
                            data-almacen-nombre="${producto.almacen_nombre}"
                            data-item-id="${producto.id_item}"
                            data-producto-nombre="${producto.producto_nombre}"
                            data-stock="${producto.stock}"
                            data-precio="${producto.precio}"
                            data-imagen="${imagenUrl}">
                            <div class="d-flex">
                                <div class="mr-3">
                                    <img src="${imagenUrl}" 
                                        alt="${producto.producto_nombre}" 
                                        class="producto-imagen-modal"
                                        style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;"
                                        onerror="this.src='https://placehold.co/80x80/8B4513/white?text=Producto'">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><strong>${producto.producto_nombre}</strong></h6>
                                    <small class="text-muted d-block">📦 ${producto.almacen_nombre}</small>
                                    <small class="text-muted d-block">💰 Bs. ${parseFloat(producto.precio).toFixed(2)}</small>
                                    <span class="stock-badge ${stockClass} mt-1 d-inline-block">
                                        📊 Stock: ${stockText}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            $('#productosGrid').html(html);
            
            // Agregar evento de clic a las tarjetas
            $('.producto-card-modal').on('click', function() {
                $('.producto-card-modal').removeClass('selected');
                $(this).addClass('selected');
                
                // Guardar selección
                const almacenId = $(this).data('almacen-id');
                const almacenNombre = $(this).data('almacen-nombre');
                const itemId = $(this).data('item-id');
                const productoNombre = $(this).data('producto-nombre');
                const stock = $(this).data('stock');
                const precio = $(this).data('precio');
                const imagen = $(this).data('imagen');
                
                // Actualizar campos ocultos
                $('#selectedAlmacenId').val(almacenId);
                $('#selectedItemId').val(itemId);
                $('#selectedStock').val(stock);
                
                // Mostrar información seleccionada
                $('#productoSeleccionadoImg').attr('src', imagen || '/images/default-product.png');
                $('#productoSeleccionadoNombre').text(productoNombre);
                $('#productoSeleccionadoAlmacen').text(almacenNombre);
                $('#productoSeleccionadoStock').text(stock + ' unidades');
                
                // Setear precio
                $('#itemPrecio').val(precio);
                
                // Habilitar cantidad y botón
                $('#itemCantidad').prop('disabled', false);
                $('#itemCantidad').attr('max', stock);
                $('#maxStockMsg').text(`Máximo disponible: ${stock} unidades`);
                
                // Mostrar la información y habilitar botón
                $('#productoSeleccionadoInfo').show();
                $('#btnAgregarCarrito').prop('disabled', false);
                
                // Cerrar modal después de seleccionar
                $('#seleccionProductoModal').modal('hide');
            });
        }
        
        // Filtros
        $('#filtroAlmacenModal').on('change', aplicarFiltros);
        $('#buscarProductoModal').on('keyup', aplicarFiltros);
        $('#btnLimpiarFiltros').on('click', function() {
            $('#filtroAlmacenModal').val('');
            $('#buscarProductoModal').val('');
            aplicarFiltros();
        });
        
        function aplicarFiltros() {
            const almacenFiltro = $('#filtroAlmacenModal').val();
            const busqueda = $('#buscarProductoModal').val().toLowerCase();
            
            let productosFiltrados = productosData;
            
            if (almacenFiltro) {
                productosFiltrados = productosFiltrados.filter(p => p.id_almacen == almacenFiltro);
            }
            
            if (busqueda) {
                productosFiltrados = productosFiltrados.filter(p => 
                    p.producto_nombre.toLowerCase().includes(busqueda) ||
                    p.almacen_nombre.toLowerCase().includes(busqueda)
                );
            }
            
            renderProductosGrid(productosFiltrados);
        }
        
        // Validar cantidad
        $('#itemCantidad').on('input', function() {
            const cantidad = parseInt($(this).val());
            const stock = parseInt($('#selectedStock').val());
            const maxStockMsg = $('#maxStockMsg');
            
            if (cantidad > stock) {
                $(this).addClass('is-invalid');
                maxStockMsg.html(`<span class="text-danger">⚠️ La cantidad excede el stock disponible (${stock} unidades)</span>`);
                $('#btnAgregarCarrito').prop('disabled', true);
            } else if (cantidad <= 0 || isNaN(cantidad)) {
                $(this).addClass('is-invalid');
                maxStockMsg.html(`<span class="text-danger">⚠️ Ingrese una cantidad válida</span>`);
                $('#btnAgregarCarrito').prop('disabled', true);
            } else {
                $(this).removeClass('is-invalid');
                maxStockMsg.html(`✅ Stock disponible: ${stock} unidades`);
                $('#btnAgregarCarrito').prop('disabled', false);
            }
        });
        
        // Limpiar selección
        $('#btnLimpiarSeleccion').on('click', function() {
            $('#selectedAlmacenId').val('');
            $('#selectedItemId').val('');
            $('#selectedStock').val('');
            $('#itemCantidad').val('').prop('disabled', true);
            $('#itemPrecio').val('');
            $('#productoSeleccionadoInfo').hide();
            $('#btnAgregarCarrito').prop('disabled', true);
            $('#maxStockMsg').empty();
        });
    });

    window.routes = {
        ventasStore: '{{ route("ventas.store") }}',
        ventasClientes: '{{ route("ventas.clientes") }}',
        ventasEnviarCorreo: '{{ route("ventas.enviar-correo") }}'
    };
</script>
<script src="{{ asset('js/gestion-comercial.js') }}"></script>
@endpush