@extends('layouts.adminlte')

@section('title', 'Sección de Compras - Panadería Otto')
@section('page-title', 'Compras')
@section('page-description', 'Registro de compras a proveedores')

@section('content')
<div class="container-fluid">
    
    <div class="row">
        <div class="col-md-7">
            <!-- Selección de Proveedor -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-truck"></i> Seleccionar Proveedor
                            <button type="button" class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#modalProveedor">
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
                                <div class="card proveedor-card" data-id="{{ $proveedor->id_proveedor }}" data-nombre="{{ $nombre }}">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-building fa-2x mr-3" style="color: #8B4513;"></i>
                                            <div>
                                                <h6 class="mb-0">{{ $nombre }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone"></i> {{ $proveedor->telefono ?? 'N/A' }}
                                                    <br>
                                                    <span class="badge badge-info">{{ $proveedor->tipo_proveedor === 'persona' ? 'Persona Natural' : 'Empresa' }}</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" id="selectedProveedor" value="">
                    <div id="proveedorSeleccionadoInfo" class="mt-2" style="display: none;">
                        <div class="alert alert-info">
                            <i class="fas fa-check-circle"></i> Proveedor seleccionado: <strong id="proveedorSeleccionadoNombre"></strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agregar Items -->
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
                            <button type="button" class="btn btn-sm btn-link mt-1" data-toggle="modal" data-target="#createAlmacenModal">
                                <i class="fas fa-plus"></i> Nuevo Almacén
                            </button>
                        </div>
                        <div class="col-md-5">
                            <label>Insumo / Item</label>
                            <select class="form-control" id="itemSelect" required>
                                <option value="">Seleccionar Item</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id_item }}" data-nombre="{{ $item->insumo->nombre ?? 'Item' }}">
                                        {{ $item->insumo->nombre ?? 'Item' }} ({{ $item->insumo->unidad_medida ?? 'unidad' }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-sm btn-link mt-1" data-toggle="modal" data-target="#createInsumoModal">
                                <i class="fas fa-plus"></i> Nuevo Insumo
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
                                <i class="fas fa-plus-circle"></i> Agregar a la Compra
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
                        <i class="fas fa-shopping-cart"></i> Carrito de Compras
                        <span class="badge badge-primary float-right" id="cartCount">0</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div id="cartItems" class="table-detalles">
                        <p class="text-muted text-center">No hay items agregados</p>
                    </div>
                    <div class="total-card mt-3">
                        <div class="row">
                            <div class="col-6"><strong>Total:</strong></div>
                            <div class="col-6 text-right"><strong id="cartTotal">Bs. 0.00</strong></div>
                        </div>
                    </div>
                    <button class="btn btn-success btn-block mt-3" onclick="confirmPurchase()" id="btnConfirmarCompra" disabled>
                        <i class="fas fa-check-circle"></i> Confirmar Compra
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
                    <h5 class="mb-0"><i class="fas fa-history"></i> Historial de Compras Recientes</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#notasCompra">Notas de Compra</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#detallesCompra">Detalles de Compra</a></li>
                    </ul>
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="notasCompra">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead><tr><th>ID</th><th>Fecha</th><th>Proveedor</th><th>Empleado</th><th>Total</th><th>Estado</th><th>Acciones</th></tr></thead>
                                    <tbody>
                                        @foreach($notasCompra as $nota)
                                        <tr>
                                            <td>{{ $nota->id_nota_compra }}</td>
                                            <td>{{ \Carbon\Carbon::parse($nota->fecha_compra)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($nota->proveedor)
                                                    {{ $nota->proveedor->persona->nombre ?? $nota->proveedor->empresa->razon_social ?? 'N/A' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $nota->empleado->nombre ?? 'N/A' }}</td>
                                            <td>Bs. {{ number_format($nota->monto_total, 2) }}</td>
                                            <td><span class="badge badge-success">{{ $nota->estado }}</span></td>
                                            <td><button class="btn btn-sm btn-info" onclick="verDetalleNota({{ $nota->id_nota_compra }})"><i class="fas fa-eye"></i></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="detallesCompra">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead><tr><th>Nota</th><th>Almacén</th><th>Item</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead>
                                    <tbody>
                                        @foreach($detallesCompra as $detalle)
                                        <tr>
                                            <td>{{ $detalle->id_nota_compra }}</td>
                                            <td>{{ $detalle->almacen->nombre ?? 'N/A' }}</td>
                                            <td>{{ $detalle->item->insumo->nombre ?? $detalle->item->nombre ?? 'Item' }}</td>
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

{{-- MODALES --}}
<div class="modal fade" id="modalProveedor" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-truck"></i> Nuevo Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateProveedor" action="{{ route('compras.proveedor.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tipo</label>
                        <select name="tipo_proveedor" class="form-control" id="tipoProveedorSelect" required>
                            <option value="persona">Persona Natural</option>
                            <option value="empresa">Empresa</option>
                        </select>
                    </div>
                    <div id="camposPersona">
                        <div class="form-group">
                            <label>Nombre Completo</label>
                            <input type="text" name="nombre_persona" class="form-control">
                        </div>
                    </div>
                    <div id="camposEmpresa" style="display:none">
                        <div class="form-group">
                            <label>Razón Social</label>
                            <input type="text" name="razon_social" class="form-control">
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
                        <label>Correo</label>
                        <input type="email" name="correo" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Detalle Nota Compra (estilo recibo) --}}
<div class="modal fade" id="modalDetalleNota" tabindex="-1" aria-labelledby="modalDetalleNotaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient-dark text-white" style="background: linear-gradient(135deg, #5D3A1A 0%, #8B4513 100%);">
                <h5 class="modal-title" id="modalDetalleNotaLabel">
                    <i class="fas fa-receipt"></i> Comprobante de Compra
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
                            <h5><strong>NOTA DE COMPRA</strong></h5>
                            <h6><span class="badge badge-success" id="reciboNumero">#001</span></h6>
                            <small id="reciboFecha">Fecha: 01/01/2026</small>
                        </div>
                    </div>
                </div>

                {{-- Información del proveedor y empleado --}}
                <div class="p-3 border-bottom">
                    <div class="row">
                        <div class="col-6">
                            <strong>Proveedor:</strong><br>
                            <span id="reciboProveedorNombre">Nombre Proveedor</span><br>
                            <small id="reciboProveedorTelefono">Tel: N/A</small><br>
                            <small id="reciboProveedorCorreo">Email: N/A</small>
                        </div>
                        <div class="col-6 text-right">
                            <strong>Atendido por:</strong><br>
                            <span id="reciboEmpleadoNombre">Nombre Empleado</span><br>
                            <small>ID: <span id="reciboEmpleadoId">1</span></small>
                        </div>
                    </div>
                </div>

                {{-- Tabla de items --}}
                <div class="p-3">
                    <h6><i class="fas fa-boxes"></i> Detalle de Items</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Cant.</th>
                                    <th>Descripción</th>
                                    <th>Almacén</th>
                                    <th class="text-right">P. Unit.</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="reciboItemsBody">
                                {{-- Se llena con JavaScript --}}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                                    <td class="text-right"><strong id="reciboTotal">Bs. 0.00</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Pie del recibo --}}
                <div class="p-3 bg-light border-top">
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
            <div class="modal-footer justify-content-between">
                <div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" onclick="imprimirRecibo()">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                    <button type="button" class="btn btn-success" id="btnEnviarCorreo">
                        <i class="fas fa-envelope"></i> Enviar por Correo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal para ingresar correo de envío --}}
<div class="modal fade" id="modalEnvioCorreo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-envelope"></i> Enviar Comprobante</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" class="form-control" id="correoDestino" placeholder="ejemplo@correo.com" required>
                    <small class="form-text text-muted">Se enviará una copia del comprobante a esta dirección.</small>
                </div>
                <input type="hidden" id="idNotaCompraEnvio">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarEnvio">
                    <i class="fas fa-paper-plane"></i> Enviar
                </button>
            </div>
        </div>
    </div>
</div>  

@include('modulo-almacen.partials.modal-almacen')
@include('modulo-almacen.partials.modal-insumo', ['categorias' => $categoriasInsumo ?? []])
@include('modulo-almacen.partials.modal-categoria-insumo')

@endsection
@push('scripts')
<script>
    // Definir rutas para el archivo JS externo
    window.routes = {
        comprasStore: '{{ route("compras.store") }}',
        comprasProveedores: '{{ route("compras.proveedores") }}',
        comprasEnviarCorreo: '{{ route("compras.enviar-correo") }}'
    };
</script>
<script src="{{ asset('js/gestion-comercial.js') }}"></script>
@endpush