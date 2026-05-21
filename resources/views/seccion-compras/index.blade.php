@extends('layouts.adminlte')

@section('title', 'Sección de Compras - Panadería Otto')
@section('page-title', 'Compras')
@section('page-description', 'Registro de compras a proveedores')

@push('styles')
<style>
    .proveedor-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: var(--border-radius-md);
        border: 2px solid transparent;
    }
    .proveedor-card:hover {
        border-color: var(--color-primary);
        background-color: var(--color-bg-lighter);
        transform: translateY(-2px);
    }
    .proveedor-card.selected {
        border-color: var(--color-primary);
        background-color: var(--color-bg-lighter);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .proveedor-icon { color: var(--color-primary); }

    .btn-add-item {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
        color: var(--text-on-primary);
        border-radius: 25px;
        transition: all 0.3s;
        padding: 0.5rem 1.5rem;
        border: none;
    }
    .btn-add-item:hover {
        filter: brightness(1.1);
        color: var(--text-on-primary);
    }
    .btn-add-item:disabled { opacity: 0.5; cursor: not-allowed; }

    .cart-empty { text-align: center; padding: 2rem; color: var(--text-muted); }
    .cart-item {
        background: var(--color-bg-lighter);
        border-radius: var(--border-radius-sm);
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-left: 3px solid var(--color-primary);
    }
    .cart-item:hover { background: var(--color-bg-lightest); }
    .cart-item-remove { color: var(--badge-danger); cursor: pointer; transition: transform 0.2s; }
    .cart-item-remove:hover { transform: scale(1.2); }
    .cart-total-card {
        background: var(--color-bg-lighter);
        border-radius: var(--border-radius-sm);
        padding: 1rem;
        border: 2px solid var(--color-accent);
    }

    .recibo-header { background-color: var(--color-bg-lighter); }
    .recibo-footer { background-color: var(--color-bg-lighter); border-top: 1px solid var(--color-border); }
    .recibo-empresa { color: var(--color-primary-dark); }

    .btn-link-new {
        color: var(--color-primary);
        font-size: 0.85rem;
        padding: 0;
    }
    .btn-link-new:hover {
        color: var(--color-primary-dark);
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    <div class="row">
        {{-- Columna izquierda: Proveedor + Items --}}
        <div class="col-md-7">
            {{-- Selección de Proveedor --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-truck"></i> Seleccionar Proveedor
                        <button type="button" class="btn btn-sm btn-success float-right" 
                                data-toggle="modal" data-target="#modalProveedor">
                            <i class="fas fa-plus"></i> Nuevo Proveedor
                        </button>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row" id="proveedoresList">
                        @foreach($proveedores as $proveedor)
                            @php
                                $nombre = '';
                                if($proveedor->tipo_proveedor === 'persona' && $proveedor->persona) {
                                    $nombre = $proveedor->persona->nombre;
                                } elseif($proveedor->tipo_proveedor === 'empresa' && $proveedor->empresa) {
                                    $nombre = $proveedor->empresa->razon_social;
                                }
                            @endphp
                            <div class="col-md-6 mb-3">
                                <div class="card proveedor-card p-3" 
                                     data-id="{{ $proveedor->id_proveedor }}" 
                                     data-nombre="{{ $nombre }}">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-building fa-2x mr-3 proveedor-icon"></i>
                                        <div>
                                            <h6 class="mb-0">{{ $nombre }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-phone"></i> {{ $proveedor->telefono ?? 'N/A' }}
                                                <br>
                                                <span class="badge badge-info">
                                                    {{ $proveedor->tipo_proveedor === 'persona' ? 'Persona Natural' : 'Empresa' }}
                                                </span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" id="selectedProveedor" value="">
                    <div id="proveedorSeleccionadoInfo" class="mt-2" style="display: none;">
                        <div class="alert alert-info">
                            <i class="fas fa-check-circle"></i> 
                            Proveedor seleccionado: <strong id="proveedorSeleccionadoNombre"></strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Agregar Items --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cart-plus"></i> Agregar Items a la Compra</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <label>Almacén</label>
                            <select class="form-control" id="itemAlmacen" required>
                                <option value="">Seleccionar Almacén</option>
                                @foreach($almacenes as $almacen)
                                    <option value="{{ $almacen->id_almacen }}" data-tipo="{{ $almacen->tipo_almacen }}">
                                        {{ $almacen->nombre }} 
                                        ({{ $almacen->tipo_almacen === 'insumo' ? 'Solo Insumos' : 'Mixto' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label>Insumo / Item</label>
                            <select class="form-control" id="itemSelect" required>
                                <option value="">Seleccionar Item</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id_item }}" data-nombre="{{ $item->nombre ?? 'Item' }}">
                                        {{ $item->nombre ?? 'Item' }} ({{ $item->unidad_medida ?? 'unidad' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Cantidad</label>
                            <input type="number" class="form-control" id="itemCantidad" placeholder="Cantidad" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <label>Precio Unitario (Bs.)</label>
                            <input type="number" step="0.01" class="form-control" id="itemPrecio" 
                                   placeholder="Precio unitario" required>
                        </div>
                        <div class="col-md-6">
                            <label>Fecha de Vencimiento</label>
                            <input type="date" class="form-control" id="itemFechaVencimiento">
                            <small class="text-muted">Si aplica, dejar en blanco si no caduca</small>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="button" class="btn btn-add-item btn-block" onclick="addItemToCart()">
                                <i class="fas fa-plus-circle"></i> Agregar a la Compra
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna derecha: Carrito --}}
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart"></i> Carrito de Compras
                        <span class="badge badge-primary float-right" id="cartCount">0</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="cartItems">
                        <p class="cart-empty">No hay items agregados</p>
                    </div>
                    <div class="cart-total-card mt-3">
                        <div class="row">
                            <div class="col-6"><strong>Total:</strong></div>
                            <div class="col-6 text-right"><strong id="cartTotal">Bs. 0.00</strong></div>
                        </div>
                    </div>
                    <button class="btn btn-success btn-block mt-3" onclick="confirmPurchase()" 
                            id="btnConfirmarCompra" disabled>
                        <i class="fas fa-check-circle"></i> Confirmar Compra
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Historial de Compras --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Historial de Compras Recientes</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#notasCompra">Notas de Compra</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#detallesCompra">Detalles de Compra</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="notasCompra">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th><th>Fecha</th><th>Proveedor</th>
                                            <th>Empleado</th><th>Total</th><th>Estado</th><th>Ver</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($notasCompra as $nota)
                                        <tr>
                                            <td>{{ $nota->id_nota_compra }}</td>
                                            <td>{{ \Carbon\Carbon::parse($nota->fecha_compra)->format('d/m/Y H:i') }}</td>
                                            <td>{{ $nota->proveedor->persona->nombre ?? $nota->proveedor->empresa->razon_social ?? 'N/A' }}</td>
                                            <td>{{ $nota->empleado->nombre ?? 'N/A' }}</td>
                                            <td>Bs. {{ number_format($nota->monto_total, 2) }}</td>
                                            <td><span class="badge badge-success">{{ $nota->estado }}</span></td>
                                            <td>
                                                <button class="btn btn-sm btn-info" onclick="verDetalleNota({{ $nota->id_nota_compra }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="detallesCompra">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nota</th><th>Almacén</th><th>Item</th>
                                            <th>Cantidad</th><th>Precio</th><th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($detallesCompra as $detalle)
                                        <tr>
                                            <td>{{ $detalle->id_nota_compra }}</td>
                                            <td>{{ $detalle->almacen->nombre ?? 'N/A' }}</td>
                                            <td>{{ $detalle->item->nombre ?? 'Item' }}</td>
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
</div>

{{-- ===================================================== --}}
{{-- MODAL: DETALLE NOTA COMPRA (RECIBO) --}}
{{-- ===================================================== --}}
<div class="modal fade" id="modalDetalleNota" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-panaderia shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-receipt"></i> Comprobante de Compra</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0 bg-panaderia-light">
                
                {{-- Encabezado del recibo --}}
                <div class="p-3 border-bottom" style="border-color: var(--color-border) !important;">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="mb-0" style="color: var(--color-primary-dark);"><strong>PANADERÍA OTTO</strong></h4>
                            <small class="text-muted">NIT: 123456789</small><br>
                            <small class="text-muted">Av. Principal #123, Santa Cruz</small><br>
                            <small class="text-muted">Tel: (591) 123-45678</small>
                        </div>
                        <div class="col-6 text-right">
                            <h5 style="color: var(--text-primary);"><strong>NOTA DE COMPRA</strong></h5>
                            <h6><span class="badge badge-success" id="reciboNumero">#001</span></h6>
                            <small class="text-muted" id="reciboFecha">Fecha: 01/01/2026</small>
                        </div>
                    </div>
                </div>

                {{-- Información del proveedor y empleado --}}
                <div class="p-3 border-bottom" style="border-color: var(--color-border) !important;">
                    <div class="row">
                        <div class="col-6">
                            <strong style="color: var(--color-primary-dark);">Proveedor:</strong><br>
                            <span id="reciboProveedorNombre" style="color: var(--text-primary);">-</span><br>
                            <small class="text-muted" id="reciboProveedorTelefono">Tel: -</small><br>
                            <small class="text-muted" id="reciboProveedorCorreo">Email: -</small>
                        </div>
                        <div class="col-6 text-right">
                            <strong style="color: var(--color-primary-dark);">Atendido por:</strong><br>
                            <span id="reciboEmpleadoNombre" style="color: var(--text-primary);">-</span><br>
                            <small class="text-muted">ID: <span id="reciboEmpleadoId">-</span></small>
                        </div>
                    </div>
                </div>

                {{-- Tabla de items --}}
                <div class="p-3">
                    <h6 style="color: var(--color-primary-dark);"><i class="fas fa-boxes"></i> Detalle de Items</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Cant.</th><th>Descripción</th><th>Almacén</th>
                                    <th class="text-right">P. Unit.</th><th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="reciboItemsBody"></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                    <td class="text-right"><strong id="reciboTotal" style="color: var(--color-primary);">Bs. 0.00</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Pie del recibo --}}
                <div class="p-3 border-top" style="border-color: var(--color-border) !important; background-color: var(--color-bg-lighter);">
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Gracias por su preferencia</small><br>
                            <small class="text-muted">Documento generado electrónicamente</small>
                        </div>
                        <div class="col-6 text-right">
                            <small class="text-muted">_________________________</small><br>
                            <small class="text-muted">Firma y sello</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-panaderia-lighter justify-content-between">
                <button type="button" class="btn btn-cancel" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" onclick="imprimirRecibo()">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                    <button type="button" class="btn btn-success btn-sm" id="btnEnviarCorreo">
                        <i class="fas fa-envelope"></i> Enviar por Correo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===================================================== --}}
{{-- MODAL: ENVÍO DE CORREO --}}
{{-- ===================================================== --}}
<div class="modal fade" id="modalEnvioCorreo" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-panaderia shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-envelope"></i> Enviar Comprobante</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body bg-panaderia-light">
                <div class="form-group">
                    <label class="text-panaderia">Correo electrónico</label>
                    <input type="email" class="form-control" id="correoDestino" 
                           placeholder="ejemplo@correo.com" required>
                    <small class="form-text text-muted">Se enviará una copia del comprobante.</small>
                </div>
                <input type="hidden" id="idNotaCompraEnvio">
            </div>
            <div class="modal-footer bg-panaderia-lighter">
                <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-save" id="btnConfirmarEnvio">
                    <i class="fas fa-paper-plane"></i> Enviar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODALES REUTILIZADOS --}}
@include('modulo-almacen.partials.modal-almacen')
@include('modulo-almacen.partials.modal-insumo', ['categorias' => $categoriasInsumo ?? []])
@include('modulo-almacen.partials.modal-categoria-insumo')
@include('seccion-compras.partials.modal-proveedor')

@endsection

@push('scripts')
<script>
    window.routes = {
        comprasStore: '{{ route("compras.store") }}',
        comprasProveedores: '{{ route("compras.proveedores") }}',
        comprasEnviarCorreo: '{{ route("compras.enviar-correo") }}'
    };

    // ==========================================
    // ABRIR MODAL DE ENVÍO DE CORREO (COMPRAS)
    // ==========================================
    $(document).on('click', '#btnEnviarCorreo', function() {
        $('#modalDetalleNota').modal('hide');
        setTimeout(function() {
            $('#modalEnvioCorreo').modal('show');
        }, 300);
    });

    // ==========================================
    // ENVIAR CORREO (COMPRAS)
    // ==========================================
    $(document).on('click', '#btnConfirmarEnvio', function() {
        const correo = $('#correoDestino').val().trim();
        const idCompra = $('#idNotaCompraEnvio').val();
        
        if (!correo || !correo.includes('@')) {
            toastr.error('Ingrese un correo electrónico válido');
            return;
        }
        
        const btn = $(this);
        btn.html('<i class="fas fa-spinner fa-spin"></i> Enviando...').prop('disabled', true);
        
        $.ajax({
            url: window.routes.comprasEnviarCorreo || '/compras/enviar-correo',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            data: {
                id_compra: idCompra,
                correo: correo
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message || 'Correo enviado exitosamente');
                    $('#modalEnvioCorreo').modal('hide');
                    $('#correoDestino').val('');
                } else {
                    toastr.error(response.message || 'Error al enviar el correo');
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error al enviar');
            },
            complete: function() {
                btn.html('<i class="fas fa-paper-plane"></i> Enviar').prop('disabled', false);
            }
        });
    });

    // ==========================================
    // IMPRIMIR RECIBO (COMPRAS)
    // ==========================================
    window.imprimirRecibo = function() {
        const modalContent = document.querySelector('#modalDetalleNota .modal-content');
        if (!modalContent) return;
        
        const contenido = modalContent.cloneNode(true);
        contenido.querySelector('.modal-footer')?.remove();
        contenido.querySelector('.modal-header .close')?.remove();
        
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Comprobante de Compra</title>
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
    };
</script>
<script src="{{ asset('js/gestion-comercial.js') }}"></script>
@endpush