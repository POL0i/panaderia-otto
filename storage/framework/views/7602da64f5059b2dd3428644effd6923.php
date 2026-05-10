<?php $__env->startSection('title', 'Dashboard - Panadería Otto'); ?>
<?php $__env->startSection('page-title', 'Panel de Control'); ?>
<?php $__env->startSection('page-description', 'Bienvenido al sistema de gestión de Panadería Otto'); ?>

<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>" style="color: #8B4513;">Inicio</a></li>
<li class="breadcrumb-item active" style="color: #5D3A1A;">Dashboard</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert" style="background: linear-gradient(135deg, #FFF5E6 0%, #FFF9F0 100%); border-left: 4px solid #8B4513; border-radius: 12px;">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-bread-slice fa-2x" style="color: #8B4513;"></i>
                    </div>
                    <div>
                        <h5 class="mb-0" style="color: #5D3A1A;">¡Bienvenido de vuelta, <?php echo e(Auth::user()->name ?? 'Administrador'); ?>!</h5>
                        <small style="color: #A0522D;">Hoy es <?php echo e(\Carbon\Carbon::now()->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY')); ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <!-- Productos -->
        <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #8B4513 0%, #6B4226 100%); border-radius: 16px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.2s ease;">
                <div class="inner">
                    <h3 style="color: white; font-weight: 700;"><?php echo e($totalProductos ?? '150'); ?></h3>
                    <p style="color: rgba(255,255,255,0.9); font-weight: 500;">Productos Registrados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-bread-slice" style="color: rgba(255,255,255,0.2); font-size: 70px;"></i>
                </div>
                <a href="<?php echo e(route('productos.index')); ?>" class="small-box-footer" style="background: rgba(0,0,0,0.1); color: white; border-radius: 0 0 16px 16px; padding: 10px;">
                    Ver detalles <i class="fas fa-arrow-circle-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Ventas Hoy -->
        <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #D2B48C 0%, #C4A67A 100%); border-radius: 16px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.2s ease;">
                <div class="inner">
                    <h3 style="color: #5D3A1A; font-weight: 700;"><?php echo e($ventasHoy ?? '53'); ?></h3>
                    <p style="color: #5D3A1A; font-weight: 500;">Ventas de Hoy</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart" style="color: rgba(93,58,26,0.2); font-size: 70px;"></i>
                </div>
                <a href="<?php echo e(route('notas-venta.index')); ?>" class="small-box-footer" style="background: rgba(93,58,26,0.1); color: #5D3A1A; border-radius: 0 0 16px 16px; padding: 10px;">
                    Ver detalles <i class="fas fa-arrow-circle-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Clientes -->
        <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #A0522D 0%, #8B4513 100%); border-radius: 16px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.2s ease;">
                <div class="inner">
                    <h3 style="color: white; font-weight: 700;"><?php echo e($totalClientes ?? '44'); ?></h3>
                    <p style="color: rgba(255,255,255,0.9); font-weight: 500;">Clientes Activos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users" style="color: rgba(255,255,255,0.2); font-size: 70px;"></i>
                </div>
                <a href="<?php echo e(route('clientes.index')); ?>" class="small-box-footer" style="background: rgba(0,0,0,0.1); color: white; border-radius: 0 0 16px 16px; padding: 10px;">
                    Ver detalles <i class="fas fa-arrow-circle-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Pedidos Pendientes -->
        <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #D2691E 0%, #B85C1A 100%); border-radius: 16px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.2s ease;">
                <div class="inner">
                    <h3 style="color: white; font-weight: 700;"><?php echo e($pedidosPendientes ?? '65'); ?></h3>
                    <p style="color: rgba(255,255,255,0.9); font-weight: 500;">Pedidos Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie" style="color: rgba(255,255,255,0.2); font-size: 70px;"></i>
                </div>
                <a href="#" class="small-box-footer" style="background: rgba(0,0,0,0.1); color: white; border-radius: 0 0 16px 16px; padding: 10px;">
                    Ver detalles <i class="fas fa-arrow-circle-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    
    <div class="row mt-4">
        <!-- Gráfico de ventas -->
        <div class="col-md-8">
            <div class="card shadow-sm" style="border-radius: 16px; overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, #5D3A1A 0%, #8B4513 100%); color: white; border: none;">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line"></i> Ventas de los últimos 7 días
                    </h5>
                </div>
                <div class="card-body" style="background-color: #FFF9F0;">
                    <canvas id="ventasChart" style="height: 250px; width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Productos más vendidos -->
        <div class="col-md-4">
            <div class="card shadow-sm" style="border-radius: 16px; overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, #5D3A1A 0%, #8B4513 100%); color: white; border: none;">
                    <h5 class="mb-0">
                        <i class="fas fa-crown"></i> Top Productos
                    </h5>
                </div>
                <div class="card-body" style="background-color: #FFF9F0;">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3 d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-bread-slice" style="color: #8B4513;"></i> Pan Francés</span>
                            <span class="badge" style="background: #D2B48C; color: #5D3A1A; padding: 8px 12px; border-radius: 20px;">156 ventas</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-birthday-cake" style="color: #8B4513;"></i> Pastel de Chocolate</span>
                            <span class="badge" style="background: #D2B48C; color: #5D3A1A; padding: 8px 12px; border-radius: 20px;">89 ventas</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-cookie" style="color: #8B4513;"></i> Galletas de Mantequilla</span>
                            <span class="badge" style="background: #D2B48C; color: #5D3A1A; padding: 8px 12px; border-radius: 20px;">67 ventas</span>
                        </li>
                        <li class="mb-3 d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-mug-hot" style="color: #8B4513;"></i> Pan Integral</span>
                            <span class="badge" style="background: #D2B48C; color: #5D3A1A; padding: 8px 12px; border-radius: 20px;">45 ventas</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-croissant" style="color: #8B4513;"></i> Croissant</span>
                            <span class="badge" style="background: #D2B48C; color: #5D3A1A; padding: 8px 12px; border-radius: 20px;">38 ventas</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm" style="border-radius: 16px; overflow: hidden;">
                <div class="card-header" style="background: linear-gradient(135deg, #5D3A1A 0%, #8B4513 100%); color: white; border: none;">
                    <h5 class="mb-0">
                        <i class="fas fa-history"></i> Actividad Reciente
                    </h5>
                </div>
                <div class="card-body" style="background-color: #FFF9F0;">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="color: #5D3A1A;">Hora</th>
                                    <th style="color: #5D3A1A;">Usuario</th>
                                    <th style="color: #5D3A1A;">Acción</th>
                                    <th style="color: #5D3A1A;">Detalle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge" style="background: #D2B48C; color: #5D3A1A;">10:30 AM</span></td>
                                    <td><i class="fas fa-user-circle" style="color: #8B4513;"></i> Admin</td>
                                    <td>Nueva venta</td>
                                    <td>Venta #0001 - $1,250.00</td>
                                </tr>
                                <tr>
                                    <td><span class="badge" style="background: #D2B48C; color: #5D3A1A;">09:15 AM</span></td>
                                    <td><i class="fas fa-user-circle" style="color: #8B4513;"></i> María López</td>
                                    <td>Producto actualizado</td>
                                    <td>Pan Francés - Stock actualizado</td>
                                </tr>
                                <tr>
                                    <td><span class="badge" style="background: #D2B48C; color: #5D3A1A;">08:45 AM</span></td>
                                    <td><i class="fas fa-user-circle" style="color: #8B4513;"></i> Carlos Ruiz</td>
                                    <td>Nueva receta</td>
                                    <td>Receta "Pan de Masa Madre" creada</td>
                                </tr>
                                <tr>
                                    <td><span class="badge" style="background: #D2B48C; color: #5D3A1A;">08:00 AM</span></td>
                                    <td><i class="fas fa-user-circle" style="color: #8B4513;"></i> Sistema</td>
                                    <td>Inventario actualizado</td>
                                    <td>Insumos bajos: Harina, Levadura</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
    .small-box {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
    
    .small-box .inner h3 {
        font-size: 2.2rem;
    }
    
    .small-box .icon i {
        transition: all 0.2s ease;
    }
    
    .small-box:hover .icon i {
        transform: scale(1.05);
    }
    
    .table tbody tr {
        border-bottom: 1px solid #E6D5B8;
    }
    
    .table tbody tr:last-child {
        border-bottom: none;
    }
    
    /* Efecto hover en filas de tabla */
    .table tbody tr:hover {
        background-color: #FFF5E6 !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de ventas
    const ctx = document.getElementById('ventasChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            datasets: [{
                label: 'Ventas',
                data: [12, 19, 15, 17, 24, 32, 28],
                borderColor: '#8B4513',
                backgroundColor: 'rgba(139, 69, 19, 0.1)',
                borderWidth: 3,
                pointBackgroundColor: '#D2B48C',
                pointBorderColor: '#5D3A1A',
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#5D3A1A',
                        font: { weight: 'bold' }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#E6D5B8' },
                    ticks: { color: '#5D3A1A' }
                },
                x: {
                    grid: { color: '#E6D5B8' },
                    ticks: { color: '#5D3A1A', weight: 'bold' }
                }
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/home.blade.php ENDPATH**/ ?>