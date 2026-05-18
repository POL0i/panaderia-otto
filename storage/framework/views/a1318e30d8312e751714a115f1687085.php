<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Panadería Otto - Sistema de Gestión'); ?></title>

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Tema base estructural -->
    <link rel="stylesheet" href="<?php echo e(asset('css/panaderia-theme.css')); ?>">

    
    <?php
        $theme = session('theme', 'jovenes');
        $mode  = session('mode', 'auto');
    ?>

    
    <?php if($mode === 'auto'): ?>
        <link rel="stylesheet" href="<?php echo e(asset("css/themes/{$theme}/light.css")); ?>">
        <link rel="stylesheet" href="<?php echo e(asset("css/themes/{$theme}/dark.css")); ?>">
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo e(asset("css/themes/{$theme}/{$mode}.css")); ?>">
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed"
      data-theme="<?php echo e($theme); ?>"
      data-mode="<?php echo e($mode); ?>">

<div class="wrapper">

    
    
    
        <nav class="main-header navbar navbar-expand">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="<?php echo e(route('home')); ?>" class="nav-link">
                    <i class="fas fa-home"></i> Inicio
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-line"></i> Panel
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge notification-badge">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">3 Notificaciones</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-utensils mr-2 dropdown-icon-accent"></i> Nueva receta creada
                        <span class="float-right text-muted text-sm">hace 2 min</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-box mr-2 dropdown-icon-accent"></i> Inventario bajo
                        <span class="float-right text-muted text-sm">hace 1 hora</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2 dropdown-icon-accent"></i> Nuevo cliente registrado
                        <span class="float-right text-muted text-sm">hace 3 horas</span>
                    </a>
                </div>
            </li>

            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fas fa-user-circle"></i>
                    <span><?php echo e(Auth::user()->name ?? 'Usuario'); ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user"></i> Mi Perfil
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-cog"></i> Configuración
                    </a>

                    
                    <div class="dropdown-divider"></div>
                    <span class="dropdown-header">Tema</span>
                    <a href="#" class="dropdown-item" onclick="cambiarTema('ninos')">
                        <i class="fas fa-child"></i> Niños
                    </a>
                    <a href="#" class="dropdown-item" onclick="cambiarTema('jovenes')">
                        <i class="fas fa-user"></i> Jóvenes
                    </a>
                    <a href="#" class="dropdown-item" onclick="cambiarTema('adultos')">
                        <i class="fas fa-user-tie"></i> Adultos
                    </a>

                    
                    <div class="dropdown-divider"></div>
                    <span class="dropdown-header">Modo</span>
                    <a href="#" class="dropdown-item" onclick="cambiarModo('light')">
                        <i class="fas fa-sun"></i> Modo día
                    </a>
                    <a href="#" class="dropdown-item" onclick="cambiarModo('dark')">
                        <i class="fas fa-moon"></i> Modo noche
                    </a>
                    <a href="#" class="dropdown-item" onclick="cambiarModo('auto')">
                        <i class="fas fa-magic"></i> Automático
                    </a>

                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    
    
    
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="<?php echo e(route('home')); ?>" class="brand-link d-flex align-items-center justify-content-center">
            <i class="fas fa-bread-slice fa-2x mr-2 brand-icon"></i>
            <span class="brand-text font-weight-bold">Panadería Otto</span>
        </a>

        <div class="sidebar">
            
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <i class="fas fa-user-circle fa-2x"></i>
                </div>
                <div class="info">
                    <a href="#" class="d-block">
                        <?php echo e(Auth::user()->name ?? 'Usuario'); ?>

                        <span class="user-status"></span>
                    </a>
                    <span class="user-role-badge">
                        <?php
                            $user = Auth::user();
                            if($user) {
                                if($user->esAdmin()) {
                                    echo '<i class="fas fa-crown"></i> Administrador';
                                } elseif($user->tipo_usuario == 'empleado') {
                                    echo '<i class="fas fa-user-tie"></i> Empleado';
                                } else {
                                    echo '<i class="fas fa-user"></i> Cliente';
                                }
                            }
                        ?>
                    </span>
                </div>
            </div>

            <div class="text-center mb-3">
                <small class="sidebar-section-title">
                    <i class="fas fa-bread-slice"></i> Menú Principal
                </small>
            </div>

            
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                    <?php
                        $user = Auth::user();
                        $isAdmin = $user ? $user->esAdmin() : false;
                        $userPermissions = $user ? $user->obtenerPermisos() : [];
                    ?>

                    
                    <li class="nav-item">
                        <a href="<?php echo e(route('home')); ?>" class="nav-link <?php echo e(Request::routeIs('home') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    
                    <?php if($isAdmin): ?>
                    <li class="nav-item has-treeview <?php echo e(Request::routeIs('usuarios.*') || 
                        Request::routeIs('personas.*') || 
                        Request::routeIs('roles.*') || 
                        Request::routeIs('permisos.*') || 
                        Request::routeIs('rol_permisos.*') || 
                        Request::routeIs('rol-permiso-usuarios.*') 
                        ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link <?php echo e(Request::routeIs('usuarios.*') || 
                            Request::routeIs('personas.*') || 
                            Request::routeIs('roles.*') || 
                            Request::routeIs('permisos.*') || 
                            Request::routeIs('rol_permisos.*') || 
                            Request::routeIs('rol-permiso-usuarios.*') 
                            ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>Usuarios <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo e(route('usuarios.create-access')); ?>" 
                                   class="nav-link nav-link-acceso <?php echo e(Request::routeIs('usuarios.create-access') ? 'active' : ''); ?>">
                                    <i class="fas fa-lock-open nav-icon"></i>
                                    <p>Módulo de Acceso</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('personas.index')); ?>" 
                                   class="nav-link <?php echo e(Request::routeIs('personas.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Personas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('rol_permisos.index')); ?>" 
                                   class="nav-link <?php echo e(Request::routeIs('rol_permisos.*') || Request::routeIs('roles.*') || Request::routeIs('permisos.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Roles y Permisos</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    
                    <?php if(in_array('gestion_comercial_ver', $userPermissions) || $isAdmin): ?>
                    <li class="nav-item has-treeview <?php echo e(Request::routeIs('notas-venta.*') || Request::routeIs('detalles-venta.*') || Request::routeIs('notas-compra.*') || Request::routeIs('detalles-compra.*') || Request::routeIs('proveedores.*') || Request::routeIs('ppersona.*') || Request::routeIs('pempresa.*') || Request::routeIs('compras.*') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-exchange-alt"></i>
                            <p>Gestión Comercial <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo e(route('compras.index')); ?>" class="nav-link nav-link-modulo-destacado <?php echo e(Request::routeIs('compras.index') ? 'active' : ''); ?>">
                                    <i class="fas fa-shopping-cart nav-icon"></i>
                                    <p>Panel de Compras</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('ventas.index')); ?>" class="nav-link nav-link-modulo-destacado <?php echo e(Request::routeIs('ventas.index') ? 'active' : ''); ?>">
                                    <i class="fas fa-cart-shopping nav-icon"></i>
                                    <p>Panel de Ventas</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('detalles-venta.index')); ?>" class="nav-link <?php echo e(Request::routeIs('detalles-venta.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Registros de Venta</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('detalles-compra.index')); ?>" class="nav-link <?php echo e(Request::routeIs('detalles-compra.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Registros de Compra</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('proveedores.index')); ?>" class="nav-link <?php echo e(Request::routeIs('proveedores.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Proveedores</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    
                    <?php if(in_array('almacen_ver', $userPermissions) || $isAdmin): ?>
                    <li class="nav-item has-treeview <?php echo e(Request::routeIs('modulo-almacen.*') || Request::routeIs('almacenes.*') || Request::routeIs('productos.*') || Request::routeIs('items.*') || Request::routeIs('insumos.*') || Request::routeIs('almacen-items.*') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>Almacén <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(in_array('panel_almacen_ver', $userPermissions) || $isAdmin): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('modulo-almacen.index')); ?>" class="nav-link nav-link-modulo-destacado <?php echo e(Request::routeIs('modulo-almacen.index') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-warehouse"></i>
                                    <p>Panel de Almacén</p>
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('items.index')); ?>" class="nav-link <?php echo e(Request::routeIs('items.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Items</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('almacen-items.index')); ?>" class="nav-link <?php echo e(Request::routeIs('almacen-items.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Almacén-Items</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    
                    <?php if(in_array('reportes_ver', $userPermissions) || $isAdmin): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('reportes.index')); ?>" class="nav-link <?php echo e(Request::routeIs('reportes.*') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Reportes</p>
                        </a>
                    </li>
                    <?php endif; ?>

                    
                    <?php if(in_array('inventario_ver', $userPermissions) || $isAdmin): ?>
                    <li class="nav-item has-treeview <?php echo e(Request::routeIs('movimientos.*') || Request::routeIs('traspasos.*') || Request::routeIs('lotes.*') || Request::routeIs('configuracion.*') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>Inventario <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo e(route('movimientos.index')); ?>" class="nav-link <?php echo e(Request::routeIs('movimientos.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Movimientos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('traspasos.index')); ?>" class="nav-link <?php echo e(Request::routeIs('traspasos.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Traspasos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('lotes.index')); ?>" class="nav-link <?php echo e(Request::routeIs('lotes.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Lotes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('configuracion.edit')); ?>" class="nav-link <?php echo e(Request::routeIs('configuracion.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Configuración</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    
                    <?php if(in_array('produccion_ver', $userPermissions) || $isAdmin): ?>
                    <li class="nav-item has-treeview <?php echo e(Request::routeIs('produccion.*') || Request::routeIs('recetas.*') || Request::routeIs('detalles-receta.*') || Request::routeIs('producciones.*') || Request::routeIs('produccion-items.*') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-industry"></i>
                            <p>Producción <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if(in_array('panel_produccion_ver', $userPermissions) || $isAdmin): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('produccion.index')); ?>" class="nav-link nav-link-modulo-destacado <?php echo e(Request::routeIs('produccion.index') ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-industry"></i>
                                    <p>Panel de Producción</p>
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('detalles-receta.index')); ?>" class="nav-link <?php echo e(Request::routeIs('detalles-receta.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Detalles Receta</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('producciones.index')); ?>" class="nav-link <?php echo e(Request::routeIs('producciones.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Producciones</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                </ul>
            </nav>

            
            <div class="sidebar-footer">
                <i class="fas fa-store"></i> Versión 2.0
                <br>
                <i class="far fa-clock"></i>
                <span id="sidebar-clock"></span>
                <br>
                <a href="#" data-toggle="modal" data-target="#aboutModal">
                    <i class="fas fa-info-circle"></i> Acerca de
                </a>
            </div>
        </div>
    </aside>

    
    
    
    <div class="content-wrapper bg-panaderia-light">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <?php if (! empty(trim($__env->yieldContent('page-title')))): ?>
                            <h1 class="m-0"><?php echo $__env->yieldContent('page-title'); ?></h1>
                            <?php if (! empty(trim($__env->yieldContent('page-description')))): ?>
                                <small class="text-muted"><?php echo $__env->yieldContent('page-description'); ?></small>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <?php echo $__env->yieldContent('breadcrumb'); ?>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </section>
    </div>

    
    
    
    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline-block">
            <i class="fas fa-bread-slice"></i> Hecho con amor
        </div>
        <strong>
            <i class="fas fa-copyright"></i> <?php echo e(date('Y')); ?> Panadería Otto.
        </strong>
        Todos los derechos reservados.
    </footer>
</div>




<div class="modal fade" id="aboutModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-bread-slice"></i> Panadería Otto
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-bread-slice fa-3x modal-brand-icon"></i>
                <h4 class="mt-3">Sistema de Gestión</h4>
                <p>Versión 2.0.0</p>
                <hr>
                <p class="text-muted">
                    <i class="fas fa-copyright"></i> <?php echo e(date('Y')); ?> Panadería Otto.<br>
                    Todos los derechos reservados.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Reloj en tiempo real
    function updateClock() {
        const now = new Date();
        const time = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
        const clockElement = document.getElementById('sidebar-clock');
        if (clockElement) {
            clockElement.textContent = time;
        }
    }
    updateClock();
    setInterval(updateClock, 60000);

    // Activar treeview
    $(document).ready(function() {
        $('.has-treeview.menu-open > .nav-link').each(function() {
            $(this).find('.right').addClass('fa-angle-down').removeClass('fa-angle-left');
        });

        $('.has-treeview > .nav-link').on('click', function() {
            const icon = $(this).find('.right');
            if (icon.hasClass('fa-angle-left')) {
                icon.removeClass('fa-angle-left').addClass('fa-angle-down');
            } else {
                icon.removeClass('fa-angle-down').addClass('fa-angle-left');
            }
        });
    });
</script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };
</script>


<script>
(function() {
    const body = document.body;
    let currentMode = body.getAttribute('data-mode');

    if (currentMode === 'auto') {
        const hour = new Date().getHours();
        const isDay = hour >= 6 && hour < 18;
        currentMode = isDay ? 'light' : 'dark';
        body.setAttribute('data-mode', currentMode);
    }

    document.cookie = `mode=${currentMode};path=/;max-age=86400`;

    window.cambiarModo = function(modo) {
        body.setAttribute('data-mode', modo);
        document.cookie = `mode=${modo};path=/;max-age=31536000`;
    };
})();

function cambiarTema(nuevoTema) {
    fetch('/theme/change', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ theme: nuevoTema })
    }).then(() => window.location.reload());
}

function cambiarModo(nuevoModo) {
    window.cambiarModo(nuevoModo);
    window.location.reload();
}
</script>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/layouts/adminlte.blade.php ENDPATH**/ ?>