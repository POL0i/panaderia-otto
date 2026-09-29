{{-- resources/views/reportes/index.blade.php --}}
@extends('layouts.adminlte')

@section('title', 'Reportes - Panadería Otto')
@section('page-title', 'Reportes')
@section('page-description', 'Análisis de gestión comercial, inventario y producción')

@push('styles')
<style>
    /* ==========================================
       ESTILOS ESPECÍFICOS - REPORTES INDEX
       ========================================== */
    
    /* Cards de acceso a reportes */
    .report-card {
        transition: all 0.3s ease;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        height: 100%;
        cursor: pointer;
        border: none;
    }
    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    .report-card .card-body {
        padding: 2.5rem 1.5rem;
        color: var(--text-on-primary);
    }
    .report-card .report-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.8;
        display: block;
    }
    
    /* Variantes de report cards */
    .report-card-comercial .card-body {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
    }
    .report-card-inventario .card-body {
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-secondary) 100%);
        color: var(--color-primary-dark);
    }
    .report-card-produccion .card-body {
        background: linear-gradient(135deg, var(--color-primary-light) 0%, var(--color-primary-dark) 100%);
    }
    
    /* Mini estadísticas */
    .mini-stat {
        border-radius: var(--border-radius-sm);
        padding: 12px;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        transition: background 0.2s ease;
    }
    .mini-stat:hover {
        filter: brightness(0.95);
    }
    .mini-stat-ingreso { background: rgba(0, 123, 255, 0.06); }
    .mini-stat-egreso  { background: rgba(220, 53, 69, 0.06); }
    .mini-stat-venta   { background: rgba(40, 167, 69, 0.06); }
    .mini-stat-produccion { background: rgba(160, 82, 45, 0.06); }
    
    /* Badges de movimiento */
    .badge-movimiento-ingreso { background: var(--badge-info); color: white; }
    .badge-movimiento-egreso  { background: var(--badge-danger); color: white; }
    
    /* Card header para secciones */
    .card-header-report {
        color: var(--text-on-primary);
    }
    .card-header-report-comercial {
        background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
    }
    .card-header-report-ventas {
        background: linear-gradient(135deg, var(--badge-success) 0%, color-mix(in srgb, var(--badge-success) 70%, black) 100%);
    }
    .card-header-report-produccion {
        background: linear-gradient(135deg, var(--color-primary-light) 0%, var(--color-primary) 100%);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 text-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalEnviarReporte">
                <i class="fas fa-envelope mr-1"></i> Enviar Reporte PDF
            </button>
        </div>
    </div>
    
    {{-- Cards de acceso a reportes --}}
    <div class="row">
        {{-- Gestión Comercial --}}
        <div class="col-md-4 mb-4">
            <div class="card report-card report-card-comercial" onclick="window.location='{{ route('reportes.comercial') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-chart-bar report-icon"></i>
                    <h5><i class="fas fa-shopping-cart mr-2"></i>Gestión Comercial</h5>
                    <p class="mb-0 small">Ventas y compras por fechas</p>
                </div>
            </div>
        </div>

        {{-- Inventario --}}
        <div class="col-md-4 mb-4">
            <div class="card report-card report-card-inventario" onclick="window.location='{{ route('reportes.inventario') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-boxes report-icon"></i>
                    <h5><i class="fas fa-warehouse mr-2"></i>Inventario</h5>
                    <p class="mb-0 small">Stock, caducidad y movimientos</p>
                </div>
            </div>
        </div>

        {{-- Producción --}}
        <div class="col-md-4 mb-4">
            <div class="card report-card report-card-produccion" onclick="window.location='{{ route('reportes.produccion') }}'">
                <div class="card-body text-center">
                    <i class="fas fa-industry report-icon"></i>
                    <h5><i class="fas fa-cogs mr-2"></i>Producción</h5>
                    <p class="mb-0 small">Órdenes por fechas y totales</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Resúmenes rápidos --}}
    <div class="row">
        {{-- Últimos movimientos de inventario --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header card-header-report card-header-report-comercial">
                    <h6 class="mb-0"><i class="fas fa-exchange-alt mr-2"></i> Últimos Movimientos</h6>
                </div>
                <div class="card-body p-2">
                    @php
                        $ultimosMovimientos = \App\Models\MovimientoInventario::orderBy('id_movimiento', 'desc')
                            ->limit(7)
                            ->get();
                    @endphp
                    @forelse($ultimosMovimientos as $mov)
                        <div class="mini-stat {{ $mov->tipo_movimiento == 'ingreso' ? 'mini-stat-ingreso' : 'mini-stat-egreso' }}">
                            <div>
                                <strong>{{ \App\Models\Item::find($mov->id_item)->nombre ?? 'Item #'.$mov->id_item }}</strong>
                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($mov->fecha_movimiento)->format('d/m H:i') }}</small>
                            </div>
                            <span class="badge {{ $mov->tipo_movimiento == 'ingreso' ? 'badge-movimiento-ingreso' : 'badge-movimiento-egreso' }}">
                                {{ $mov->tipo_movimiento == 'ingreso' ? '+' : '-' }}{{ $mov->cantidad }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">Sin movimientos</p>
                    @endforelse
                    <a href="{{ route('reportes.inventario') }}" class="btn btn-sm btn-outline-primary btn-block mt-2">
                        <i class="fas fa-arrow-right mr-1"></i> Ver inventario completo
                    </a>
                </div>
            </div>
        </div>

        {{-- Ventas últimos 7 días --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header card-header-report card-header-report-ventas">
                    <h6 class="mb-0"><i class="fas fa-calendar-week mr-2"></i> Ventas Últimos 7 Días</h6>
                </div>
                <div class="card-body p-2">
                    @php
                        $ventas7dias = \App\Models\NotaVenta::where('estado', 'completado')
                            ->whereDate('fecha_venta', '>=', now()->subDays(7))
                            ->orderBy('fecha_venta', 'desc')
                            ->limit(7)
                            ->get();
                    @endphp
                    @forelse($ventas7dias as $venta)
                        <div class="mini-stat mini-stat-venta">
                            <div>
                                <strong>{{ $venta->cliente->nombre ?? 'Cliente' }}</strong>
                                <br><small class="text-muted">{{ $venta->fecha_venta->format('d/m/Y') }}</small>
                            </div>
                            <span class="text-precio-venta font-weight-bold">Bs. {{ number_format($venta->monto_total, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">Sin ventas recientes</p>
                    @endforelse
                    <a href="{{ route('reportes.comercial') }}" class="btn btn-sm btn-outline-success btn-block mt-2">
                        <i class="fas fa-arrow-right mr-1"></i> Ver reporte comercial
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Producciones recientes --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header card-header-report card-header-report-produccion">
                    <h6 class="mb-0"><i class="fas fa-industry mr-2"></i> Últimas Producciones</h6>
                </div>
                <div class="card-body p-2">
                    @php
                        $ultimasProducciones = \App\Models\Produccion::with('detalles.item', 'empleadoSolicita')
                            ->orderBy('id_produccion', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    @forelse($ultimasProducciones as $prod)
                        <div class="mini-stat mini-stat-produccion">
                            <div>
                                <strong>#{{ $prod->id_produccion }} - {{ $prod->cantidad_producida }} unidades</strong>
                                <br><small class="text-muted">
                                    {{ $prod->fecha_produccion->format('d/m/Y') }} | 
                                    {{ $prod->empleadoSolicita->nombre ?? 'N/A' }} |
                                    <span class="badge {{ $prod->estado == 'aprobado' ? 'badge-tipo-producto' : ($prod->estado == 'pendiente' ? 'badge badge-warning' : 'badge badge-secondary') }}">
                                        {{ $prod->estado }}
                                    </span>
                                </small>
                            </div>
                            <small class="text-muted">
                                @foreach($prod->detalles->where('tipo_movimiento', 'egreso')->take(2) as $d)
                                    {{ $d->item->nombre ?? 'Item' }}: {{ $d->cantidad }}
                                @endforeach
                            </small>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">Sin producciones recientes</p>
                    @endforelse
                    <a href="{{ route('reportes.produccion') }}" class="btn btn-sm btn-outline-secondary btn-block mt-2">
                        <i class="fas fa-arrow-right mr-1"></i> Ver producción completa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Enviar Reporte -->
<div class="modal fade" id="modalEnviarReporte" tabindex="-1" role="dialog" aria-labelledby="modalEnviarReporteTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEnviarReporteTitle"><i class="fas fa-paper-plane mr-2"></i>Enviar Reporte por Correo</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEnviarReporte" action="{{ route('reportes.enviar-pdf') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tipo de Reporte <span class="text-danger">*</span></label>
                        <select class="form-control" name="tipo" required>
                            <option value="comercial">Comercial</option>
                            <option value="inventario">Inventario</option>
                            <option value="produccion">Producción</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio" value="{{ now()->subDays(30)->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha Fin</label>
                                <input type="date" class="form-control" name="fecha_fin" value="{{ now()->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Destinatarios (Usuarios) <span class="text-danger">*</span></label>
                        <select class="form-control select2" name="correos[]" multiple="multiple" required style="width: 100%;">
                            @foreach($usuarios as $user)
                                <option value="{{ $user->correo }}">{{ $user->nombre }} ({{ $user->correo }})</option>
                            @endforeach
                            <!-- Permite ingresar correos libres no registrados -->
                        </select>
                        <small class="form-text text-muted">Selecciona los usuarios a los que quieres enviar el PDF.</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Mensaje opcional</label>
                        <textarea class="form-control" name="mensaje" rows="3" placeholder="Ej: Adjunto los reportes del mes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnEnviar">
                        <i class="fas fa-paper-plane mr-1"></i> Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Inicializar Select2
        if($.fn.select2) {
            $('.select2').select2({
                placeholder: "Selecciona usuarios o escribe un correo",
                tags: true,
                tokenSeparators: [',', ' ']
            });
        }
        
        // Manejar el envío AJAX
        $('#formEnviarReporte').on('submit', function(e) {
            e.preventDefault();
            let $btn = $('#btnEnviar');
            let $form = $(this);
            
            $btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Enviando...').prop('disabled', true);
            
            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.message);
                        $('#modalEnviarReporte').modal('hide');
                        $form[0].reset();
                        if($.fn.select2) {
                            $('.select2').val(null).trigger('change');
                        }
                    } else {
                        toastr.error('Error al enviar el correo');
                    }
                },
                error: function(err) {
                    toastr.error('Ocurrió un error al intentar enviar el reporte.');
                    console.error(err);
                },
                complete: function() {
                    $btn.html('<i class="fas fa-paper-plane mr-1"></i> Enviar').prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush

@endsection