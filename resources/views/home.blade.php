@extends('layouts.adminlte')

@section('title', 'Dashboard - Panadería Otto')
@section('page-title', 'Panel de Control')
@section('page-description', 'Bienvenido al sistema de gestión de Panadería Otto')

@push('styles')
<style>
    /* ==========================================
       ESTILOS ESPECÍFICOS DEL DASHBOARD
       (solo estructura, los colores van por variables)
       ========================================== */
    .welcome-alert {
        border-radius: var(--border-radius-md);
        border-left: 4px solid var(--color-primary);
        background: linear-gradient(135deg, var(--color-bg-lighter) 0%, var(--color-bg-light) 100%);
    }
    .welcome-alert h5 { color: var(--color-primary-dark); }
    .welcome-alert small { color: var(--color-secondary); }
    .welcome-alert .welcome-icon { color: var(--color-primary); }

    /* Small boxes del dashboard */
    .small-box {
        border-radius: var(--border-radius-md);
        color: var(--text-on-primary);
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }
    .small-box:hover { transform: translateY(-3px); }
    .small-box .inner { padding: 1.5rem; }
    .small-box .inner h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    .small-box .inner p { font-size: 0.9rem; opacity: 0.9; }
    .small-box .icon {
        position: absolute;
        top: -10px;
        right: 10px;
        font-size: 70px;
        opacity: 0.15;
    }
    .small-box .small-box-footer {
        display: block;
        padding: 0.5rem 1rem;
        background: rgba(0,0,0,0.1);
        color: inherit;
        text-decoration: none;
        text-align: center;
        transition: background 0.2s;
    }
    .small-box .small-box-footer:hover {
        background: rgba(0,0,0,0.2);
        color: inherit;
    }

    /* Variantes de color para small boxes */
    .small-box-primary {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
    }
    .small-box-accent {
        background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-dark, #c9a871) 100%);
    }
    .small-box-accent .inner h3,
    .small-box-accent .inner p { color: var(--color-primary-dark); }
    .small-box-accent .icon { color: var(--color-primary-dark); }
    .small-box-secondary {
        background: linear-gradient(135deg, var(--color-secondary) 0%, var(--color-primary-dark) 100%);
    }
    .small-box-tertiary {
        background: linear-gradient(135deg, var(--color-primary-light) 0%, var(--color-primary) 100%);
    }

    /* Top productos */
    .top-product-item {
        transition: background 0.2s;
        padding: 0.5rem;
        border-radius: var(--border-radius-sm);
    }
    .top-product-item:hover { background: var(--color-bg-lighter); }
    .top-product-icon { color: var(--color-primary); }
    .top-product-badge {
        background: var(--color-accent);
        color: var(--color-primary-dark);
        padding: 8px 12px;
        border-radius: 20px;
        font-weight: 500;
    }

    /* Notificaciones */
    .notification-item {
        border-bottom: 1px solid var(--color-border);
    }

    /* Tabla de actividad */
    .activity-time-badge {
        background: var(--color-accent);
        color: var(--color-primary-dark);
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.8rem;
    }
    .activity-user-icon { color: var(--color-primary); }

    /* Gráfico */
    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    {{-- Mensaje de bienvenida --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert welcome-alert">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-bread-slice fa-2x welcome-icon"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">¡Bienvenido de vuelta, {{ Auth::user()->name ?? 'Administrador' }}!</h5>
                        <small>Hoy es {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tarjetas de estadísticas --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box small-box-primary">
                <div class="inner">
                    <h3>{{ $totalProductos }}</h3>
                    <p>Productos Registrados</p>
                </div>
                <div class="icon"><i class="fas fa-bread-slice"></i></div>
                <a href="{{ route('productos.index') }}" class="small-box-footer">
                    Ver detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box small-box-accent">
                <div class="inner">
                    <h3>Bs. {{ number_format($ventasHoy, 2) }}</h3>
                    <p>Ventas de Hoy</p>
                </div>
                <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                <a href="{{ route('detalles-venta.index') }}" class="small-box-footer">
                    Ver detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box small-box-secondary">
                <div class="inner">
                    <h3>{{ $totalClientes }}</h3>
                    <p>Clientes Activos</p>
                </div>
                <div class="icon"><i class="fas fa-users"></i></div>
                <a href="{{ route('personas.index') }}" class="small-box-footer">
                    Ver detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box small-box-tertiary">
                <div class="inner">
                    <h3>{{ $pedidosPendientes }}</h3>
                    <p>Pedidos Pendientes</p>
                </div>
                <div class="icon"><i class="fas fa-chart-pie"></i></div>
                <a href="{{ route('producciones.index') }}" class="small-box-footer">
                    Ver detalles <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Gráfico y Top productos --}}   
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Ventas de los últimos 7 días</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="ventasChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-crown"></i> Top Productos</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($productosTop as $producto)
                        <li class="mb-3 d-flex justify-content-between align-items-center top-product-item">
                            <span>
                                <i class="fas fa-bread-slice top-product-icon"></i> 
                                {{ $producto->nombre }}
                            </span>
                            <span class="top-product-badge">
                                {{ $producto->total_vendido }} ventas
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Notificaciones recientes --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bell"></i> Notificaciones Recientes (últimas 24h)</h5>
                </div>
                <div class="card-body">
                    @if($notificaciones->count())
                        <ul class="list-unstyled">
                            @foreach($notificaciones as $notif)
                                <li class="mb-2 pb-2 notification-item">
                                    <i class="{{ $notif->icono }}" style="color: {{ $notif->color }}; width: 30px;"></i>
                                    {{ $notif->mensaje }}
                                    <small class="text-muted float-right">{{ $notif->fecha->diffForHumans() }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No hay notificaciones recientes.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Actividad reciente --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Actividad Reciente</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha/Hora</th>
                                    <th>Usuario</th>
                                    <th>Acción</th>
                                    <th>Detalle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($actividades as $act)
                                <tr>
                                    <td>
                                        <span class="activity-time-badge">
                                            {{ $act->fecha->format('H:i A') }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-user-circle activity-user-icon"></i> 
                                        {{ $act->usuario }}
                                    </td>
                                    <td>{{ $act->accion }}</td>
                                    <td>{{ $act->descripcion }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No hay actividad reciente.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initChart);
        } else {
            initChart();
        }
        
        function initChart() {
            const canvas = document.getElementById('ventasChart');
            if (!canvas) {
                console.error('Canvas no encontrado');
                return;
            }
            
            // Cargar Chart.js dinámicamente
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js';
            script.onload = function() {
                const datos = {{ json_encode($ventasPorDia) }};
                const ctx = canvas.getContext('2d');
                
                // Obtener colores del tema activo
                const style = getComputedStyle(document.body);
                const primaryColor = style.getPropertyValue('--color-primary').trim() || '#8B4513';
                const accentColor = style.getPropertyValue('--color-accent').trim() || '#D2B48C';
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Hace 6d', 'Hace 5d', 'Hace 4d', 'Hace 3d', 'Hace 2d', 'Ayer', 'Hoy'],
                        datasets: [{
                            label: 'Ventas (Bs.)',
                            data: datos,
                            borderColor: primaryColor,
                            backgroundColor: accentColor + '40',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: primaryColor,
                            pointBorderColor: '#fff',
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: {
                                    color: primaryColor,
                                    font: { family: 'Poppins', size: 12 }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { 
                                    color: primaryColor,
                                    font: { family: 'Poppins' }
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: accentColor + '30' },
                                ticks: {
                                    color: primaryColor,
                                    font: { family: 'Poppins' },
                                    callback: function(value) {
                                        return 'Bs. ' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            };
            document.head.appendChild(script);
        }
    })();
</script>
@endpush