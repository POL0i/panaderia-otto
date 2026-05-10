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

    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Panadería Theme -->
    <link rel="stylesheet" href="<?php echo e(asset('css/panaderia-theme.css')); ?>">

    <style>
        /* Estilo para módulos destacados */
        .nav-link-modulo-destacado {
            background: linear-gradient(90deg, rgba(46, 93, 58, 0.15) 0%, rgba(26, 61, 42, 0.1) 100%) !important;
            border-left: 4px solid #2E5D3A !important;
            margin: 2px 0;
        }

        .nav-link-modulo-destacado:hover {
            background: linear-gradient(90deg, rgba(46, 93, 58, 0.25) 0%, rgba(26, 61, 42, 0.2) 100%) !important;
        }

        .nav-link-modulo-destacado .nav-icon {
            color: #2E5D3A !important;
        }

        .nav-link-modulo-destacado.active {
            background: linear-gradient(90deg, #2E5D3A 0%, #1A3D2A 100%) !important;
            color: white !important;
        }

        .nav-link-modulo-destacado.active .nav-icon {
            color: white !important;
        }

        /* Asegurar que Poppins sea la fuente principal */
        body, .main-header .navbar, .brand-link, .nav-sidebar .nav-link {
            font-family: 'Poppins', sans-serif;
        }

        /* Sidebar personalizado - tonos panadería */
        .main-sidebar {
            background: linear-gradient(180deg, #5D3A1A 0%, #3E2510 100%);
        }

        .main-sidebar .brand-link {
            background: linear-gradient(135deg, #8B4513 0%, #5D3A1A 100%);
            border-bottom: 2px solid #D2B48C;
        }

        .brand-link .brand-text {
            font-weight: 600;
            letter-spacing: 1px;
            color: white;
        }

        .main-sidebar .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link {
            color: rgba(255, 255, 255, 0.85);
        }

        .main-sidebar .nav-sidebar .nav-link.active {
            background: linear-gradient(90deg, #8B4513 0%, #A0522D 100%);
            color: white;
            border-left: 4px solid #D2B48C;
        }

        .main-sidebar .nav-sidebar .nav-link:hover {
            background: rgba(139, 69, 19, 0.6);
            color: white;
        }

        .main-sidebar .nav-treeview .nav-link {
            color: rgba(255, 255, 255, 0.75);
        }

        .main-sidebar .nav-treeview .nav-link.active {
            background: rgba(210, 180, 140, 0.2);
            color: #D2B48C;
            border-left: 3px solid #D2B48C;
        }

        .main-sidebar .nav-treeview .nav-link:hover {
            background: rgba(210, 180, 140, 0.15);
            color: #D2B48C;
        }

        /* User Panel */
        .user-panel {
            border-bottom: 1px solid rgba(210, 180, 140, 0.3);
            padding: 1rem 0.8rem !important;
            margin-bottom: 0.5rem;
        }

        .user-panel .image i {
            color: #D2B48C;
        }

        .user-panel .info a {
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
        }

        .user-panel .info a:hover {
            color: #D2B48C;
        }

        .user-role-badge {
            display: inline-block;
            background: rgba(210, 180, 140, 0.2);
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
            color: #D2B48C;
        }

        .user-status {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #4caf50;
            margin-left: 5px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7); }
            70% { box-shadow: 0 0 0 6px rgba(76, 175, 80, 0); }
            100% { box-shadow: 0 0 0 0 rgba(76, 175, 80, 0); }
        }

                /* Footer del sidebar - CORREGIDO para no solaparse */
        .sidebar {
            display: flex !important;
            flex-direction: column !important;
            height: calc(100vh - 57px) !important; /* 57px es la altura del brand-link */
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding-right: 0 !important;
        }

        .sidebar nav {
            flex: 1 0 auto !important;
        }

        .sidebar-footer {
            position: sticky !important;
            bottom: 0 !important;
            left: 0 !important;
            width: 100% !important;
            padding: 0.8rem 0.5rem !important;
            margin-top: auto !important;
            background: rgba(0, 0, 0, 0.35) !important;
            backdrop-filter: blur(5px) !important;
            -webkit-backdrop-filter: blur(5px) !important;
            border-top: 1px solid rgba(210, 180, 140, 0.3) !important;
            font-size: 0.7rem !important;
            text-align: center !important;
            color: rgba(255, 255, 255, 0.7) !important;
            flex-shrink: 0 !important;
            z-index: 10 !important;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2) !important;
        }

        .sidebar-footer a {
            color: #D2B48C !important;
            text-decoration: none !important;
            transition: all 0.3s ease !important;
        }

        .sidebar-footer a:hover {
            color: white !important;
            text-shadow: 0 0 5px rgba(210, 180, 140, 0.5) !important;
        }

        /* Cuando el sidebar está colapsado */
        .sidebar-mini.sidebar-collapse .sidebar {
            overflow-y: auto !important;
        }

        .sidebar-mini.sidebar-collapse .sidebar-footer {
            width: 100% !important;
            padding: 0.5rem 0.2rem !important;
        }

        .sidebar-mini.sidebar-collapse .sidebar-footer br {
            display: none !important;
        }

        .sidebar-mini.sidebar-collapse .sidebar-footer {
            font-size: 0.55rem !important;
            line-height: 1.4 !important;
        }

        .sidebar-mini.sidebar-collapse .sidebar-footer .fa-store,
        .sidebar-mini.sidebar-collapse .sidebar-footer .fa-clock,
        .sidebar-mini.sidebar-collapse .sidebar-footer .fa-info-circle {
            font-size: 0.8rem !important;
            display: block !important;
            margin: 2px 0 !important;
        }

        /* Ajuste para navegadores que no soportan backdrop-filter */
        @supports not (backdrop-filter: blur(5px)) {
            .sidebar-footer {
                background: rgba(0, 0, 0, 0.5) !important;
            }
        }

        /* Navbar superior */
        .main-header.navbar {
            background: white;
            border-bottom: 2px solid #D2B48C;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .main-header .navbar-nav .nav-link {
            color: #5D3A1A;
        }

        .main-header .navbar-nav .nav-link:hover {
            color: #8B4513;
        }

        /* Dropdown menu */
        .dropdown-menu {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-top: 3px solid #8B4513;
        }

        .dropdown-item {
            color: #5D3A1A;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: #FFF5E6;
            color: #8B4513;
        }

        /* Content Header */
        .content-header h1 {
            color: #5D3A1A;
            font-weight: 600;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
        }

        .breadcrumb-item a {
            color: #8B4513;
        }

        .breadcrumb-item.active {
            color: #5D3A1A;
            font-weight: 500;
        }

        /* Footer */
        .main-footer {
            background: #FFF5E6;
            color: #5D3A1A;
            border-top: 1px solid #D2B48C;
        }

        /* Animación para el sidebar */
        .main-sidebar {
            transition: all 0.3s ease;
        }

        /* Scrollbar personalizada para el sidebar */
        .sidebar::-webkit-scrollbar {
            width: 5px !important;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #3E2510 !important;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #D2B48C !important;
            border-radius: 3px !important;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #E8C9A0 !important;
        }

        /* Estilo para el módulo de acceso destacado */
        .nav-link-acceso {
            background-color: rgba(76, 175, 80, 0.15) !important;
            border-left: 3px solid #4caf50 !important;
        }

        .nav-link-acceso:hover {
            background-color: rgba(76, 175, 80, 0.25) !important;
        }

        /* Iconos del sidebar */
        .nav-sidebar .nav-icon {
            color: #D2B48C;
        }

        .nav-sidebar .nav-link.active .nav-icon {
            color: white;
        }

        .nav-sidebar .nav-link:hover .nav-icon {
            color: #D2B48C;
        }

        /* Estilo para el módulo de acceso destacado */
        .nav-link-acceso {
            background-color: rgba(76, 175, 80, 0.15) !important;
            border-left: 3px solid #4caf50 !important;
        }

        .nav-link-acceso:hover {
            background-color: rgba(76, 175, 80, 0.25) !important;
        }

        /* Iconos del sidebar */
        .nav-sidebar .nav-icon {
            color: #D2B48C;
        }

        .nav-sidebar .nav-link.active .nav-icon {
            color: white;
        }

        .nav-sidebar .nav-link:hover .nav-icon {
            color: #D2B48C;
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
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
                    <span class="badge badge-warning navbar-badge" style="background-color: #8B4513;">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">3 Notificaciones</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-utensils mr-2" style="color: #8B4513;"></i> Nueva receta creada
                        <span class="float-right text-muted text-sm">hace 2 min</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-box mr-2" style="color: #8B4513;"></i> Inventario bajo
                        <span class="float-right text-muted text-sm">hace 1 hora</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2" style="color: #8B4513;"></i> Nuevo cliente registrado
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

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="<?php echo e(route('home')); ?>" class="brand-link d-flex align-items-center justify-content-center">
            <i class="fas fa-bread-slice fa-2x mr-2" style="color: #D2B48C;"></i>
            <span class="brand-text font-weight-bold" style="font-size: 1.2rem;">Panadería Otto</span>
        </a>

        <div class="sidebar">
            <!-- User Panel -->
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
                <small style="color: rgba(255,255,255,0.5);">
                    <i class="fas fa-bread-slice"></i> Menú Principal
                </small>
            </div>

            <!-- Sidebar Menu COMPLETO -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                    <?php
                        $user = Auth::user();
                        $isAdmin = $user ? $user->esAdmin() : false;
                        $userPermissions = $user ? $user->obtenerPermisos() : [];
                    ?>

                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="<?php echo e(route('home')); ?>" class="nav-link <?php echo e(Request::routeIs('home') ? 'active' : ''); ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- MÓDULO: USUARIOS (Solo Admin) -->
                    <?php if($isAdmin): ?>
                    <!-- Clientes -->
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
                                    class="nav-link <?php echo e(Request::routeIs('usuarios.create-access') ? 'active' : ''); ?>">
                                        <i class="fas fa-lock-open nav-icon" style="color: #4caf50;"></i>
                                        <p><i class="fas fa-star-of-life" style="font-size: 10px;"></i> Módulo de Acceso</p>
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


                    <!-- MÓDULO: GESTIÓN COMERCIAL -->
                    <?php if(in_array('gestion_comercial_ver', $userPermissions) || $isAdmin): ?>
                    <li class="nav-item has-treeview <?php echo e(Request::routeIs('notas-venta.*') || Request::routeIs('detalles-venta.*') || Request::routeIs('notas-compra.*') || Request::routeIs('detalles-compra.*') || Request::routeIs('proveedores.*') || Request::routeIs('ppersona.*') || Request::routeIs('pempresa.*') || Request::routeIs('compras.*') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-exchange-alt"></i>
                            <p>Gestión Comercial <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <!-- NUEVA SECCIÓN DE COMPRAS - Panel Principal -->
                            <li class="nav-item">
                                <a href="<?php echo e(route('compras.index')); ?>" class="nav-link nav-link-modulo-destacado <?php echo e(Request::routeIs('compras.index') ? 'active' : ''); ?>">
                                    <i class="fas fa-shopping-cart nav-icon"></i>
                                    <p><i class="fas fa-star-of-life" style="font-size: 10px;"></i> Panel de Compras</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('ventas.index')); ?>" class="nav-link nav-link-modulo-destacado <?php echo e(Request::routeIs('ventas.index') ? 'active' : ''); ?>">
                                    <i class="fas fa-cart-shopping nav-icon"></i>
                                    <p><i class="fas fa-star-of-life" style="font-size: 10px;"></i> Panel de Ventas</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo e(route('notas-venta.index')); ?>" class="nav-link <?php echo e(Request::routeIs('notas-venta.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Notas de Venta</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('notas-compra.index')); ?>" class="nav-link <?php echo e(Request::routeIs('notas-compra.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Notas de Compra</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('detalles-venta.index')); ?>" class="nav-link <?php echo e(Request::routeIs('detalles-venta.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Detalles de Venta</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('detalles-compra.index')); ?>" class="nav-link <?php echo e(Request::routeIs('detalles-compra.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Detalles de Compra</p>
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

                    <!-- MÓDULO: ALMACÉN -->
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
                                    <p><i class="fas fa-star-of-life" style="font-size: 10px;"></i> Panel de Almacén</p>
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('almacenes.index')); ?>" class="nav-link <?php echo e(Request::routeIs('almacenes.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Almacenes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('items.index')); ?>" class="nav-link <?php echo e(Request::routeIs('items.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Items</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo e(route('almacen-items.index')); ?>" class="nav-link <?php echo e(Request::routeIs('almacen-items.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Inventario (Stock)</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if(in_array('reportes_ver', $userPermissions) || $isAdmin): ?>
                    <!-- Reportes -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Reportes</p>
                        </a>
                    </li>
                    <?php endif; ?>

                    <!-- MÓDULO: INVENTARIO -->
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

                    <!-- MÓDULO: PRODUCCIÓN -->
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
                                    <p><i class="fas fa-star-of-life" style="font-size: 10px;"></i> Panel de Producción</p>
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('recetas.index')); ?>" class="nav-link <?php echo e(Request::routeIs('recetas.*') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i><p>Recetas</p>
                                </a>
                            </li>
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

            <!-- Footer del sidebar - CORREGIDO -->
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

    <!-- Content Wrapper -->
    <div class="content-wrapper" style="background-color: #FFF9F0;">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">

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

<!-- Modal Acerca de -->
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
                <i class="fas fa-bread-slice fa-3x" style="color: #8B4513;"></i>
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

<!-- Scripts -->
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

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/layouts/adminlte.blade.php ENDPATH**/ ?>