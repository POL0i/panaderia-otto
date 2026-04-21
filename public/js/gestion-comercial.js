// =====================================================
// FUNCIONES COMUNES (AMBAS PÁGINAS)
// =====================================================

function closeModal(modalId) {
    const modalElement = document.getElementById(modalId);
    if (!modalElement) return;
    
    if (typeof $ !== 'undefined') {
        $(modalElement).modal('hide');
        return;
    }
    
    try {
        if (bootstrap.Modal.getInstance) {
            let modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
                return;
            }
        }
    } catch (e) {}
    
    try {
        const modal = new bootstrap.Modal(modalElement);
        modal.hide();
    } catch (e2) {
        modalElement.classList.remove('show');
        modalElement.style.display = 'none';
        document.body.classList.remove('modal-open');
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.remove();
    }
}

function openModal(modalId) {
    const modalElement = document.getElementById(modalId);
    if (!modalElement) return;
    
    if (typeof $ !== 'undefined') {
        $(modalElement).modal('show');
        return;
    }
    
    try {
        let modal;
        if (bootstrap.Modal.getInstance) {
            modal = bootstrap.Modal.getInstance(modalElement);
        }
        if (!modal) {
            modal = new bootstrap.Modal(modalElement);
        }
        modal.show();
    } catch (e) {
        modalElement.classList.add('show');
        modalElement.style.display = 'block';
        document.body.classList.add('modal-open');
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        document.body.appendChild(backdrop);
    }
}

// =====================================================
// FUNCIONES PARA COMPRAS
// =====================================================

let cart = [];
let selectedProveedorId = null;
let currentNotaCompra = null;

function mostrarProveedorSeleccionado(nombre) {
    const divInfo = document.getElementById('proveedorSeleccionadoInfo');
    const spanNombre = document.getElementById('proveedorSeleccionadoNombre');
    if (divInfo && spanNombre) {
        if (nombre) {
            spanNombre.textContent = nombre;
            divInfo.style.display = 'block';
        } else {
            divInfo.style.display = 'none';
        }
    }
}

function addItemToCart() {
    const almacenId = document.getElementById('itemAlmacen')?.value;
    const itemId = document.getElementById('itemSelect')?.value;
    const cantidad = parseInt(document.getElementById('itemCantidad')?.value);
    const precio = parseFloat(document.getElementById('itemPrecio')?.value);
    const itemSelect = document.getElementById('itemSelect');
    const itemNombre = itemSelect?.options[itemSelect.selectedIndex]?.getAttribute('data-nombre') || '';
    const almacenNombre = document.getElementById('itemAlmacen')?.options[document.getElementById('itemAlmacen')?.selectedIndex]?.text || '';
    
    if (!almacenId || !itemId || !cantidad || !precio) {
        toastr.warning('Complete todos los campos');
        return;
    }
    if (cantidad <= 0 || precio <= 0) {
        toastr.warning('Cantidad y precio deben ser mayores a 0');
        return;
    }
    
    cart.push({
        id_almacen: parseInt(almacenId),
        id_item: parseInt(itemId),
        cantidad: cantidad,
        precio: precio,
        nombre: itemNombre,
        almacen_nombre: almacenNombre
    });
    
    updateCartDisplay();
    document.getElementById('itemCantidad').value = '';
    document.getElementById('itemPrecio').value = '';
    document.getElementById('itemSelect').value = '';
    toastr.success('Item agregado');
}

function updateCartDisplay() {
    const cartDiv = document.getElementById('cartItems');
    if (!cartDiv) return;
    
    let total = 0;
    
    if (cart.length === 0) {
        cartDiv.innerHTML = '<p class="text-muted text-center">No hay items agregados</p>';
        document.getElementById('cartCount').textContent = '0';
        document.getElementById('cartTotal').textContent = 'Bs. 0.00';
        checkConfirmButton();
        return;
    }
    
    let html = '';
    cart.forEach((item, index) => {
        const subtotal = item.cantidad * item.precio;
        total += subtotal;
        html += `<div class="cart-item">
            <div class="d-flex justify-content-between">
                <div>
                    <strong>${item.nombre}</strong><br>
                    <small>${item.almacen_nombre}</small><br>
                    <small>${item.cantidad} x Bs. ${item.precio.toFixed(2)}</small>
                </div>
                <div class="text-right">
                    <strong>Bs. ${subtotal.toFixed(2)}</strong><br>
                    <button class="btn btn-sm btn-danger mt-2" onclick="removeFromCart(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>`;
    });
    
    cartDiv.innerHTML = html;
    document.getElementById('cartCount').textContent = cart.length;
    document.getElementById('cartTotal').textContent = `Bs. ${total.toFixed(2)}`;
    checkConfirmButton();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartDisplay();
    toastr.info('Item eliminado');
}

function checkConfirmButton() {
    const btn = document.getElementById('btnConfirmarCompra');
    if (btn) {
        btn.disabled = !(selectedProveedorId && cart.length > 0);
    }
}

function confirmPurchase() {
    if (!selectedProveedorId) {
        toastr.warning('Seleccione un proveedor');
        return;
    }
    if (!cart.length) {
        toastr.warning('Agregue items al carrito');
        return;
    }
    
    const requestData = {
        id_proveedor: selectedProveedorId,
        detalles: cart.map(item => ({
            id_almacen: item.id_almacen,
            id_item: item.id_item,
            cantidad: item.cantidad,
            precio: item.precio
        }))
    };
    
    fetch(window.routes?.comprasStore || '/compras/store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            toastr.success(response.message);
            cart = [];
            selectedProveedorId = null;
            updateCartDisplay();
            document.querySelectorAll('.proveedor-card').forEach(c => c.classList.remove('selected'));
            document.getElementById('selectedProveedor').value = '';
            mostrarProveedorSeleccionado(null);
            setTimeout(() => location.reload(), 1500);
        } else {
            toastr.error(response.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('Error al registrar la compra');
    });
}

function verDetalleNota(id) {
    fetch(`/compras/nota/${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Error al cargar');
            return response.json();
        })
        .then(response => {
            const nota = response.nota_compra;
            if (!nota) throw new Error('No se encontró la nota');
            
            currentNotaCompra = nota;
            
            const modalLabel = document.getElementById('modalDetalleNotaLabel');
            if (modalLabel) modalLabel.innerHTML = `<i class="fas fa-receipt"></i> Nota de Compra #${nota.id_nota_compra}`;
            
            document.getElementById('reciboNumero').textContent = `#${nota.id_nota_compra}`;
            document.getElementById('reciboFecha').textContent = `Fecha: ${new Date(nota.fecha_compra).toLocaleString()}`;
            
            const proveedorNombre = nota.proveedor?.persona?.nombre || nota.proveedor?.empresa?.razon_social || 'N/A';
            const proveedorTelefono = nota.proveedor?.telefono || 'N/A';
            const proveedorCorreo = nota.proveedor?.correo || 'N/A';
            document.getElementById('reciboProveedorNombre').textContent = proveedorNombre;
            document.getElementById('reciboProveedorTelefono').textContent = `Tel: ${proveedorTelefono}`;
            document.getElementById('reciboProveedorCorreo').textContent = `Email: ${proveedorCorreo}`;
            
            document.getElementById('reciboEmpleadoNombre').textContent = nota.empleado?.nombre || 'N/A';
            document.getElementById('reciboEmpleadoId').textContent = nota.empleado?.id_empleado || '1';
            
            let itemsHtml = '';
            let total = 0;
            
            if (nota.detalles && nota.detalles.length) {
                nota.detalles.forEach(d => {
                    const nombreItem = d.item?.insumo?.nombre || d.item?.nombre || 'Item';
                    const almacenNombre = d.almacen?.nombre || 'N/A';
                    const subtotal = d.cantidad * d.precio;
                    total += subtotal;
                    
                    itemsHtml += `<tr>
                        <td>${d.cantidad}</td>
                        <td>${nombreItem}</td>
                        <td>${almacenNombre}</td>
                        <td class="text-right">Bs. ${parseFloat(d.precio).toFixed(2)}</td>
                        <td class="text-right">Bs. ${subtotal.toFixed(2)}</td>
                    </tr>`;
                });
            }
            
            document.getElementById('reciboItemsBody').innerHTML = itemsHtml || '<tr><td colspan="5" class="text-center">No hay detalles</td></tr>';
            document.getElementById('reciboTotal').textContent = `Bs. ${total.toFixed(2)}`;
            document.getElementById('idNotaCompraEnvio').value = nota.id_nota_compra;
            document.getElementById('correoDestino').value = nota.proveedor?.correo || '';
            
            openModal('modalDetalleNota');
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Error al cargar el detalle');
        });
}

function imprimirRecibo() {
    const modalContent = document.querySelector('#modalDetalleNota .modal-content')?.cloneNode(true);
    if (!modalContent) return;
    
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    printWindow.document.write(`
        <html>
            <head>
                <title>Comprobante de Compra</title>
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                <style>
                    body { padding: 20px; }
                    .modal-footer, .modal-header .close { display: none !important; }
                    .badge-success { background-color: #28a745 !important; }
                </style>
            </head>
            <body>
                ${modalContent.outerHTML}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

function refreshProveedores() {
    const url = window.routes?.comprasProveedores || '/compras/proveedores';
    fetch(url)
        .then(r => r.json())
        .then(r => {
            let html = '';
            r.proveedores.forEach(p => {
                html += `<div class="col-md-6 mb-3">
                    <div class="card proveedor-card" data-id="${p.id_proveedor}" data-nombre="${p.nombre}">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-building fa-2x mr-3" style="color:#8B4513;"></i>
                                <div>
                                    <h6 class="mb-0">${p.nombre}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-phone"></i> ${p.telefono || 'N/A'}<br>
                                        <span class="badge badge-info">${p.tipo_proveedor === 'persona' ? 'Persona' : 'Empresa'}</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            });
            const list = document.getElementById('proveedoresList');
            if (list) list.innerHTML = html;
            attachProveedorEvents();
        });
}

function handleProveedorClick() {
    document.querySelectorAll('.proveedor-card').forEach(c => c.classList.remove('selected'));
    this.classList.add('selected');
    selectedProveedorId = parseInt(this.dataset.id);
    const nombre = this.dataset.nombre;
    document.getElementById('selectedProveedor').value = selectedProveedorId;
    mostrarProveedorSeleccionado(nombre);
    checkConfirmButton();
}

function attachProveedorEvents() {
    document.querySelectorAll('.proveedor-card').forEach(card => {
        card.removeEventListener('click', handleProveedorClick);
        card.addEventListener('click', handleProveedorClick);
    });
}

// =====================================================
// FUNCIONES PARA VENTAS
// =====================================================

let cartVenta = [];
let selectedClienteId = null;
let currentNotaVenta = null;

function mostrarClienteSeleccionado(nombre) {
    const divInfo = document.getElementById('clienteSeleccionadoInfo');
    const spanNombre = document.getElementById('clienteSeleccionadoNombre');
    if (divInfo && spanNombre) {
        if (nombre) {
            spanNombre.textContent = nombre;
            divInfo.style.display = 'block';
        } else {
            divInfo.style.display = 'none';
        }
    }
}

async function addItemToCartVenta() {
    const almacenId = document.getElementById('itemAlmacen')?.value;
    const itemId = document.getElementById('itemSelect')?.value;
    const cantidad = parseInt(document.getElementById('itemCantidad')?.value);
    const precio = parseFloat(document.getElementById('itemPrecio')?.value);
    const itemSelect = document.getElementById('itemSelect');
    const itemNombre = itemSelect?.options[itemSelect.selectedIndex]?.getAttribute('data-nombre') || '';
    const almacenNombre = document.getElementById('itemAlmacen')?.options[document.getElementById('itemAlmacen')?.selectedIndex]?.text || '';
    
    if (!almacenId || !itemId || !cantidad || !precio) {
        toastr.warning('Complete todos los campos');
        return;
    }
    if (cantidad <= 0 || precio <= 0) {
        toastr.warning('Cantidad y precio deben ser mayores a 0');
        return;
    }
    
    try {
        const response = await fetch(`/ventas/stock/${almacenId}/${itemId}`);
        const data = await response.json();
        const stockDisponible = data.stock || 0;
        
        if (cantidad > stockDisponible) {
            toastr.warning(`Stock insuficiente en ${almacenNombre}. Disponible: ${stockDisponible}`);
            return;
        }
        
        cartVenta.push({
            id_almacen: parseInt(almacenId),
            id_item: parseInt(itemId),
            cantidad: cantidad,
            precio: precio,
            nombre: itemNombre,
            almacen_nombre: almacenNombre
        });
        
        updateCartVentaDisplay();
        document.getElementById('itemCantidad').value = '';
        document.getElementById('itemPrecio').value = '';
        document.getElementById('itemSelect').value = '';
        toastr.success('Producto agregado');
        
    } catch (error) {
        toastr.error('Error al verificar stock');
    }
}

function updateCartVentaDisplay() {
    const cartDiv = document.getElementById('cartItems');
    if (!cartDiv) return;
    
    let total = 0;
    
    if (cartVenta.length === 0) {
        cartDiv.innerHTML = '<p class="text-muted text-center">No hay productos agregados</p>';
        document.getElementById('cartCount').textContent = '0';
        document.getElementById('cartTotal').textContent = 'Bs. 0.00';
        return;
    }
    
    let html = '';
    cartVenta.forEach((item, index) => {
        const subtotal = item.cantidad * item.precio;
        total += subtotal;
        html += `<div class="cart-item">
            <div class="d-flex justify-content-between">
                <div>
                    <strong>${item.nombre}</strong><br>
                    <small>${item.almacen_nombre}</small><br>
                    <small>${item.cantidad} x Bs. ${item.precio.toFixed(2)}</small>
                </div>
                <div class="text-right">
                    <strong>Bs. ${subtotal.toFixed(2)}</strong><br>
                    <button class="btn btn-sm btn-danger mt-2" onclick="removeFromCartVenta(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>`;
    });
    
    cartDiv.innerHTML = html;
    document.getElementById('cartCount').textContent = cartVenta.length;
    document.getElementById('cartTotal').textContent = `Bs. ${total.toFixed(2)}`;
    checkConfirmButtonVenta();
}

function removeFromCartVenta(index) {
    cartVenta.splice(index, 1);
    updateCartVentaDisplay();
    toastr.info('Producto eliminado');
}

function checkConfirmButtonVenta() {
    const btn = document.getElementById('btnConfirmarVenta');
    if (btn) {
        btn.disabled = !(selectedClienteId && cartVenta.length > 0);
    }
}

function confirmSale() {
    if (!selectedClienteId) {
        toastr.warning('Seleccione un cliente');
        return;
    }
    if (!cartVenta.length) {
        toastr.warning('Agregue productos al carrito');
        return;
    }
    
    const requestData = {
        id_cliente: selectedClienteId,
        detalles: cartVenta.map(item => ({
            id_almacen: item.id_almacen,
            id_item: item.id_item,
            cantidad: item.cantidad,
            precio: item.precio
        }))
    };
    
    fetch(window.routes?.ventasStore || '/ventas/store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            toastr.success(response.message);
            cartVenta = [];
            selectedClienteId = null;
            updateCartVentaDisplay();
            document.querySelectorAll('.cliente-card').forEach(c => c.classList.remove('selected'));
            document.getElementById('selectedCliente').value = '';
            mostrarClienteSeleccionado(null);
            setTimeout(() => location.reload(), 1500);
        } else {
            toastr.error(response.message);
        }
    })
    .catch(error => toastr.error('Error al registrar venta'));
}

function verDetalleNotaVenta(id) {
    fetch(`/ventas/nota/${id}`)
        .then(r => r.json())
        .then(response => {
            const nota = response.nota_venta;
            currentNotaVenta = nota;
            
            document.getElementById('modalDetalleNotaVentaLabel').innerHTML = `<i class="fas fa-receipt"></i> Nota de Venta #${nota.id_nota_venta}`;
            document.getElementById('reciboVentaNumero').textContent = `#${nota.id_nota_venta}`;
            document.getElementById('reciboVentaFecha').textContent = `Fecha: ${new Date(nota.fecha_venta).toLocaleString()}`;
            
            const clienteNombre = nota.cliente?.nombre || 'N/A';
            const clienteTelefono = nota.cliente?.telefono || 'N/A';
            document.getElementById('reciboVentaClienteNombre').textContent = clienteNombre;
            document.getElementById('reciboVentaClienteTelefono').textContent = `Tel: ${clienteTelefono}`;
            
            document.getElementById('reciboVentaEmpleadoNombre').textContent = nota.empleado?.nombre || 'N/A';
            document.getElementById('reciboVentaEmpleadoId').textContent = nota.empleado?.id_empleado || '1';
            
            let itemsHtml = '';
            let total = 0;
            
            const detalles = response.detalles || nota.detalles || [];
            
            detalles.forEach(d => {
                const nombreProducto = d.producto_nombre || d.item?.producto?.nombre || 'Producto';
                const almacenNombre = d.almacen_nombre || d.almacen?.nombre || 'N/A';
                const subtotal = d.cantidad * d.precio;
                total += subtotal;
                
                itemsHtml += `<tr>
                    <td>${d.cantidad}</td>
                    <td>${nombreProducto}</td>
                    <td>${almacenNombre}</td>
                    <td class="text-right">Bs. ${parseFloat(d.precio).toFixed(2)}</td>
                    <td class="text-right">Bs. ${subtotal.toFixed(2)}</td>
                </tr>`;
            });
            
            document.getElementById('reciboVentaItemsBody').innerHTML = itemsHtml || '<tr><td colspan="5" class="text-center">No hay detalles</td></tr>';
            document.getElementById('reciboVentaTotal').textContent = `Bs. ${total.toFixed(2)}`;
            document.getElementById('idNotaVentaEnvio').value = nota.id_nota_venta;
            
            const correoCliente = nota.cliente?.usuario?.correo || '';
            document.getElementById('correoDestinoVenta').value = correoCliente;
            
            openModal('modalDetalleNotaVenta');
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Error al cargar el detalle');
        });
}

function imprimirReciboVenta() {
    const modalContent = document.querySelector('#modalDetalleNotaVenta .modal-content')?.cloneNode(true);
    if (!modalContent) return;
    
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    printWindow.document.write(`
        <html>
            <head>
                <title>Comprobante de Venta</title>
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                <style>
                    body { padding: 20px; }
                    .modal-footer, .modal-header .close { display: none !important; }
                    .badge-success { background-color: #28a745 !important; }
                </style>
            </head>
            <body>
                ${modalContent.outerHTML}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

function refreshClientes() {
    fetch(window.routes?.ventasClientes || '/ventas/clientes')
        .then(r => r.json())
        .then(r => {
            let html = '';
            r.clientes.forEach(c => {
                html += `<div class="col-md-6 mb-3">
                    <div class="card cliente-card" data-id="${c.id_cliente}" data-nombre="${c.nombre}">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle fa-2x mr-3" style="color:#8B4513;"></i>
                                <div>
                                    <h6 class="mb-0">${c.nombre}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-phone"></i> ${c.telefono || 'N/A'}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            });
            const list = document.getElementById('clientesList');
            if (list) list.innerHTML = html;
            attachClienteEvents();
        });
}

function handleClienteClick() {
    document.querySelectorAll('.cliente-card').forEach(c => c.classList.remove('selected'));
    this.classList.add('selected');
    selectedClienteId = parseInt(this.dataset.id);
    const nombre = this.dataset.nombre;
    document.getElementById('selectedCliente').value = selectedClienteId;
    mostrarClienteSeleccionado(nombre);
    checkConfirmButtonVenta();
}

function attachClienteEvents() {
    document.querySelectorAll('.cliente-card').forEach(card => {
        card.removeEventListener('click', handleClienteClick);
        card.addEventListener('click', handleClienteClick);
    });
}

// =====================================================
// INICIALIZACIÓN POR PÁGINA
// =====================================================

document.addEventListener('DOMContentLoaded', function() {
    // Detectar si estamos en la página de COMPRAS
    const isComprasPage = document.getElementById('proveedoresList') !== null;
    
    // Detectar si estamos en la página de VENTAS
    const isVentasPage = document.getElementById('clientesList') !== null;
    
    if (isComprasPage) {
        console.log('Inicializando página de COMPRAS');
        
        attachProveedorEvents();
        
        const tipoSelect = document.getElementById('tipoProveedorSelect');
        if (tipoSelect) {
            tipoSelect.addEventListener('change', function() {
                document.getElementById('camposPersona').style.display = this.value === 'persona' ? 'block' : 'none';
                document.getElementById('camposEmpresa').style.display = this.value === 'empresa' ? 'block' : 'none';
            });
        }
        
        const formProveedor = document.getElementById('formCreateProveedor');
        if (formProveedor) {
            formProveedor.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(r => r.json())
                .then(r => {
                    if (r.success) {
                        closeModal('modalProveedor');
                        toastr.success(r.message);
                        refreshProveedores();
                        this.reset();
                        document.getElementById('camposPersona').style.display = 'block';
                        document.getElementById('camposEmpresa').style.display = 'none';
                    } else {
                        toastr.error(r.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error al crear proveedor');
                });
            });
        }
        
        const modalProveedor = document.getElementById('modalProveedor');
        if (modalProveedor) {
            modalProveedor.addEventListener('hidden.bs.modal', function() {
                refreshProveedores();
            });
        }
        
        const modalInsumo = document.getElementById('createInsumoModal');
        if (modalInsumo) {
            modalInsumo.addEventListener('hidden.bs.modal', function() {
                location.reload();
            });
        }

        const btnEnviarCorreo = document.getElementById('btnEnviarCorreo');
        if (btnEnviarCorreo) {
            btnEnviarCorreo.addEventListener('click', function() {
                closeModal('modalDetalleNota');
                setTimeout(() => openModal('modalEnvioCorreo'), 300);
            });
        }

        const btnConfirmarEnvio = document.getElementById('btnConfirmarEnvio');
        if (btnConfirmarEnvio) {
            btnConfirmarEnvio.addEventListener('click', function() {
                const correo = document.getElementById('correoDestino').value;
                const idCompra = document.getElementById('idNotaCompraEnvio').value;
                
                if (!correo || !correo.includes('@')) {
                    toastr.error('Ingrese un correo electrónico válido');
                    return;
                }
                
                btnConfirmarEnvio.disabled = true;
                btnConfirmarEnvio.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
                
                fetch(window.routes?.comprasEnviarCorreo || '/compras/enviar-correo', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ id_compra: idCompra, correo: correo })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message || 'Correo enviado exitosamente');
                        closeModal('modalEnvioCorreo');
                        document.getElementById('correoDestino').value = '';
                    } else {
                        toastr.error(data.message || 'Error al enviar el correo');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error al enviar el correo');
                })
                .finally(() => {
                    btnConfirmarEnvio.disabled = false;
                    btnConfirmarEnvio.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar';
                });
            });
        }
    }
    
    if (isVentasPage) {
        console.log('Inicializando página de VENTAS');
        
        attachClienteEvents();
        
        const clienteModal = document.getElementById('createClienteModal');
        if (clienteModal) {
            clienteModal.addEventListener('hidden.bs.modal', function() {
                refreshClientes();
            });
        }
        
        const productoModal = document.getElementById('createProductoModal');
        if (productoModal) {
            productoModal.addEventListener('hidden.bs.modal', function() {
                location.reload();
            });
        }
        
        const btnEnviarCorreoVenta = document.getElementById('btnEnviarCorreoVenta');
        if (btnEnviarCorreoVenta) {
            btnEnviarCorreoVenta.addEventListener('click', function() {
                closeModal('modalDetalleNotaVenta');
                setTimeout(() => openModal('modalEnvioCorreoVenta'), 300);
            });
        }
        
        const btnConfirmarEnvioVenta = document.getElementById('btnConfirmarEnvioVenta');
        if (btnConfirmarEnvioVenta) {
            btnConfirmarEnvioVenta.addEventListener('click', function() {
                const correo = document.getElementById('correoDestinoVenta').value;
                const idVenta = document.getElementById('idNotaVentaEnvio').value;
                
                if (!correo || !correo.includes('@')) {
                    toastr.error('Ingrese un correo electrónico válido');
                    return;
                }
                
                btnConfirmarEnvioVenta.disabled = true;
                btnConfirmarEnvioVenta.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
                
                fetch(window.routes?.ventasEnviarCorreo || '/ventas/enviar-correo', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ id_venta: idVenta, correo: correo })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message || 'Correo enviado exitosamente');
                        closeModal('modalEnvioCorreoVenta');
                        document.getElementById('correoDestinoVenta').value = '';
                    } else {
                        toastr.error(data.message || 'Error al enviar el correo');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error al enviar el correo');
                })
                .finally(() => {
                    btnConfirmarEnvioVenta.disabled = false;
                    btnConfirmarEnvioVenta.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar';
                });
            });
        }
        
        // Sobrescribir la función addItemToCart global con la versión de ventas
        window.addItemToCart = addItemToCartVenta;
    }
});