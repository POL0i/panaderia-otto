// =====================================================
// FUNCIONES COMUNES
// =====================================================

function openModal(modalId) {
    // AdminLTE usa Bootstrap 4 + jQuery
    if (typeof $ !== 'undefined') {
        $('#' + modalId).modal('show');
        return;
    }
    // Fallback vanilla JS
    const modalElement = document.getElementById(modalId);
    if (!modalElement) return;
    modalElement.classList.add('show');
    modalElement.style.display = 'block';
    document.body.classList.add('modal-open');
    if (!document.querySelector('.modal-backdrop')) {
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        document.body.appendChild(backdrop);
    }
}

function closeModal(modalId) {
    if (typeof $ !== 'undefined') {
        $('#' + modalId).modal('hide');
        return;
    }
    const modalElement = document.getElementById(modalId);
    if (!modalElement) return;
    modalElement.classList.remove('show');
    modalElement.style.display = 'none';
    document.body.classList.remove('modal-open');
    document.querySelector('.modal-backdrop')?.remove();
}

// =====================================================
// FUNCIONES PARA COMPRAS
// =====================================================

let cart = [];
let selectedProveedorId = null;

function addItemToCart() {
    const pageType = document.getElementById('clientesList') ? 'ventas' : 'compras';
    if (pageType === 'ventas') {
        addItemToCartVenta();
        return;
    }
    addItemToCartCompra();
}

function addItemToCartCompra() {
    const almacenId = document.getElementById('itemAlmacen')?.value;
    const itemId = document.getElementById('itemSelect')?.value;
    const cantidad = parseInt(document.getElementById('itemCantidad')?.value);
    const precio = parseFloat(document.getElementById('itemPrecio')?.value);
    const fechaVenc = document.getElementById('itemFechaVencimiento')?.value || null;
    
    if (!almacenId || !itemId || !cantidad || !precio) {
        toastr.warning('Complete todos los campos');
        return;
    }
    
    const itemSelect = document.getElementById('itemSelect');
    const itemNombre = itemSelect?.options[itemSelect.selectedIndex]?.getAttribute('data-nombre') || '';
    const almacenSelect = document.getElementById('itemAlmacen');
    const almacenNombre = almacenSelect?.options[almacenSelect.selectedIndex]?.text || '';
    
    cart.push({
        id_almacen: parseInt(almacenId),
        id_item: parseInt(itemId),
        cantidad, precio,
        fecha_vencimiento: fechaVenc,
        nombre: itemNombre,
        almacen_nombre: almacenNombre
    });
    
    updateCartDisplay();
    document.getElementById('itemCantidad').value = '';
    document.getElementById('itemPrecio').value = '';
    document.getElementById('itemSelect').value = '';
    document.getElementById('itemFechaVencimiento').value = '';
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
    const btn = document.getElementById('btnConfirmarCompra') || document.getElementById('btnConfirmarVenta');
    if (!btn) return;
    const isVentas = !!document.getElementById('clientesList');
    btn.disabled = !((isVentas ? selectedClienteId : selectedProveedorId) && (isVentas ? cartVenta.length : cart.length) > 0);
}

function confirmPurchase() {
    if (!selectedProveedorId) { toastr.warning('Seleccione un proveedor'); return; }
    if (!cart.length) { toastr.warning('Agregue items al carrito'); return; }
    
    fetch(window.routes?.comprasStore || '/compras/store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            id_proveedor: selectedProveedorId,
            detalles: cart.map(item => ({
                id_almacen: item.id_almacen,
                id_item: item.id_item,
                cantidad: item.cantidad,
                precio: item.precio,
                fecha_vencimiento: item.fecha_vencimiento || null
            }))
        })
    })
    .then(r => r.json())
    .then(r => {
        if (r.success) {
            toastr.success(r.message);
            cart = [];
            selectedProveedorId = null;
            updateCartDisplay();
            document.querySelectorAll('.proveedor-card').forEach(c => c.classList.remove('selected'));
            document.getElementById('selectedProveedor').value = '';
            setTimeout(() => location.reload(), 1500);
        } else {
            toastr.error(r.message);
        }
    })
    .catch(e => toastr.error('Error al registrar la compra'));
}

function verDetalleNota(id) {
    fetch(`/compras/nota/${id}`)
        .then(r => { if (!r.ok) throw new Error('Error al cargar'); return r.json(); })
        .then(r => {
            const nota = r.nota_compra;
            if (!nota) throw new Error('No se encontró la nota');
            
            document.getElementById('reciboNumero').textContent = `#${nota.id_nota_compra}`;
            document.getElementById('reciboFecha').textContent = `Fecha: ${new Date(nota.fecha_compra).toLocaleString()}`;
            document.getElementById('reciboProveedorNombre').textContent = nota.proveedor?.persona?.nombre || nota.proveedor?.empresa?.razon_social || 'N/A';
            document.getElementById('reciboProveedorTelefono').textContent = `Tel: ${nota.proveedor?.telefono || 'N/A'}`;
            document.getElementById('reciboProveedorCorreo').textContent = `Email: ${nota.proveedor?.correo || 'N/A'}`;
            document.getElementById('reciboEmpleadoNombre').textContent = nota.empleado?.nombre || 'N/A';
            document.getElementById('reciboEmpleadoId').textContent = nota.empleado?.id_empleado || '1';
            
            let itemsHtml = '', total = 0;
            (nota.detalles || []).forEach(d => {
                const subtotal = d.cantidad * d.precio;
                total += subtotal;
                itemsHtml += `<tr>
                    <td>${d.cantidad}</td><td>${d.item?.nombre || 'Item'}</td>
                    <td>${d.almacen?.nombre || 'N/A'}</td>
                    <td class="text-right">Bs. ${parseFloat(d.precio).toFixed(2)}</td>
                    <td class="text-right">Bs. ${subtotal.toFixed(2)}</td></tr>`;
            });
            
            document.getElementById('reciboItemsBody').innerHTML = itemsHtml || '<tr><td colspan="5">No hay detalles</td></tr>';
            document.getElementById('reciboTotal').textContent = `Bs. ${total.toFixed(2)}`;
            document.getElementById('idNotaCompraEnvio').value = nota.id_nota_compra;
            openModal('modalDetalleNota');
        })
        .catch(e => { console.error(e); toastr.error('Error al cargar el detalle'); });
}

// =====================================================
// FUNCIONES PARA VENTAS
// =====================================================

let cartVenta = [];
let selectedClienteId = null;

function addItemToCartVenta() {
    const almacenId = document.getElementById('selectedAlmacenId')?.value;
    const itemId = document.getElementById('selectedItemId')?.value;
    const cantidad = parseInt(document.getElementById('itemCantidad')?.value);
    const precio = parseFloat(document.getElementById('itemPrecio')?.value);
    
    if (!almacenId || !itemId) { toastr.warning('Seleccione un producto'); return; }
    if (!cantidad || cantidad <= 0) { toastr.warning('Ingrese una cantidad válida'); return; }
    if (!precio || precio <= 0) { toastr.warning('Ingrese un precio válido'); return; }
    
    const stock = parseInt(document.getElementById('selectedStock')?.value || 0);
    if (cantidad > stock) { toastr.warning(`Stock insuficiente. Solo hay ${stock} unidades`); return; }
    
    const nombre = document.getElementById('productoSeleccionadoNombre')?.innerText || 'Producto';
    const almacen = document.getElementById('productoSeleccionadoAlmacen')?.innerText || 'Almacén';
    
    cartVenta.push({ id_almacen: parseInt(almacenId), id_item: parseInt(itemId), cantidad, precio, nombre, almacen_nombre: almacen });
    updateCartVentaDisplay();
    document.getElementById('btnLimpiarSeleccion')?.click();
    toastr.success('Producto agregado');
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
                <div><strong>${item.nombre}</strong><br><small>${item.almacen_nombre}</small><br>
                <small>${item.cantidad} x Bs. ${item.precio.toFixed(2)}</small></div>
                <div class="text-right"><strong>Bs. ${subtotal.toFixed(2)}</strong><br>
                <button class="btn btn-sm btn-danger mt-2" onclick="removeFromCartVenta(${index})"><i class="fas fa-trash"></i></button></div>
            </div></div>`;
    });
    
    cartDiv.innerHTML = html;
    document.getElementById('cartCount').textContent = cartVenta.length;
    document.getElementById('cartTotal').textContent = `Bs. ${total.toFixed(2)}`;
    checkConfirmButton();
}

function removeFromCartVenta(index) { cartVenta.splice(index, 1); updateCartVentaDisplay(); toastr.info('Producto eliminado'); }

function confirmSale() {
    if (!selectedClienteId) { toastr.warning('Seleccione un cliente'); return; }
    if (!cartVenta.length) { toastr.warning('Agregue productos'); return; }
    
    fetch(window.routes?.ventasStore || '/ventas/store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json', 'Accept': 'application/json'
        },
        body: JSON.stringify({ id_cliente: selectedClienteId, detalles: cartVenta.map(i => ({ id_almacen: i.id_almacen, id_item: i.id_item, cantidad: i.cantidad, precio: i.precio })) })
    })
    .then(r => r.json())
    .then(r => {
        if (r.success) {
            toastr.success(r.message);
            cartVenta = []; selectedClienteId = null;
            updateCartVentaDisplay();
            document.querySelectorAll('.cliente-card').forEach(c => c.classList.remove('selected'));
            document.getElementById('selectedCliente').value = '';
            setTimeout(() => location.reload(), 1500);
        } else { toastr.error(r.message); }
    })
    .catch(() => toastr.error('Error al registrar venta'));
}

function verDetalleNotaVenta(id) {
    fetch(`/ventas/nota/${id}`)
        .then(r => { if (!r.ok) throw new Error('Error al cargar'); return r.json(); })
        .then(r => {
            const nota = r.nota_venta;
            if (!nota) throw new Error('No se encontró la nota');
            
            document.getElementById('reciboVentaNumero').textContent = `#${nota.id_nota_venta}`;
            document.getElementById('reciboVentaFecha').textContent = `Fecha: ${new Date(nota.fecha_venta).toLocaleString()}`;
            document.getElementById('reciboVentaClienteNombre').textContent = nota.cliente?.nombre || 'N/A';
            document.getElementById('reciboVentaClienteTelefono').textContent = `Tel: ${nota.cliente?.telefono || 'N/A'}`;
            document.getElementById('reciboVentaEmpleadoNombre').textContent = nota.empleado?.nombre || 'N/A';
            document.getElementById('reciboVentaEmpleadoId').textContent = nota.empleado?.id_empleado || '1';
            
            let itemsHtml = '', total = 0;
            const detalles = r.detalles || nota.detalles || [];
            detalles.forEach(d => {
                const subtotal = d.cantidad * d.precio;
                total += subtotal;
                itemsHtml += `<tr>
                    <td>${d.cantidad}</td><td>${d.producto_nombre || d.item?.nombre || 'Producto'}</td>
                    <td>${d.almacen_nombre || d.almacen?.nombre || 'N/A'}</td>
                    <td class="text-right">Bs. ${parseFloat(d.precio).toFixed(2)}</td>
                    <td class="text-right">Bs. ${subtotal.toFixed(2)}</td></tr>`;
            });
            
            document.getElementById('reciboVentaItemsBody').innerHTML = itemsHtml || '<tr><td colspan="5">No hay detalles</td></tr>';
            document.getElementById('reciboVentaTotal').textContent = `Bs. ${total.toFixed(2)}`;
            document.getElementById('idNotaVentaEnvio').value = nota.id_nota_venta;
            openModal('modalDetalleNotaVenta');
        })
        .catch(e => { console.error(e); toastr.error('Error al cargar el detalle'); });
}

function imprimirReciboVenta() {
    const modalContent = document.querySelector('#modalDetalleNotaVenta .modal-content');
    if (!modalContent) return;
    
    const contenido = modalContent.cloneNode(true);
    // Eliminar botones del clon
    contenido.querySelector('.modal-footer')?.remove();
    contenido.querySelector('.modal-header .close')?.remove();
    
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    printWindow.document.write(`
        <html>
            <head>
                <title>Comprobante de Venta</title>
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                <style>
                    body { padding: 20px; font-family: 'Poppins', sans-serif; }
                    @media print { body { padding: 0; } }
                </style>
            </head>
            <body>${contenido.outerHTML}</body>
        </html>
    `);
    printWindow.document.close();
    setTimeout(() => printWindow.print(), 500);
}

// =====================================================
// INICIALIZACIÓN
// =====================================================

document.addEventListener('DOMContentLoaded', function() {
    const isCompras = !!document.getElementById('proveedoresList');
    const isVentas = !!document.getElementById('clientesList');
    
    if (isCompras) {
        document.querySelectorAll('.proveedor-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.proveedor-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                selectedProveedorId = parseInt(this.dataset.id);
                document.getElementById('selectedProveedor').value = selectedProveedorId;
                document.getElementById('proveedorSeleccionadoInfo').style.display = 'block';
                document.getElementById('proveedorSeleccionadoNombre').textContent = this.dataset.nombre;
                checkConfirmButton();
            });
        });
    }
    
    if (isVentas) {
        document.querySelectorAll('.cliente-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.cliente-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                selectedClienteId = parseInt(this.dataset.id);
                document.getElementById('selectedCliente').value = selectedClienteId;
                document.getElementById('clienteSeleccionadoInfo').style.display = 'block';
                document.getElementById('clienteSeleccionadoNombre').textContent = this.dataset.nombre;
                checkConfirmButton();
            });
        });
        window.addItemToCart = addItemToCartVenta;
    }
});