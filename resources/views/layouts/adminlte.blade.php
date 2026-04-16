<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panadería Otto - Sistema de Gestión')</title>
    
    <!-- Google Font: Poppins (igual que el login) -->
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
    <link rel="stylesheet" href="{{ asset('css/panaderia-theme.css') }}">
    
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
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: #3E2510;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: #D2B48C;
            border-radius: 3px;
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
    
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Botón colapsar sidebar -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="fas fa-home"></i> Inicio
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-line"></i> Panel
                </a>
            </li>
        </ul>

        <!-- Navbar derecha -->
        <ul class="navbar-nav ml-auto">
            <!-- Notificaciones (opcional) -->
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
            
            <!-- Usuario -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ Auth::user()->name ?? 'Usuario' }}</span>
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
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('home') }}" class="brand-link d-flex align-items-center justify-content-center">
            <i class="fas fa-bread-slice fa-2x mr-2" style="color: #D2B48C;"></i>
            <span class="brand-text font-weight-bold" style="font-size: 1.2rem;">Panadería Otto</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    
                    @php
                        $user = Auth::user();
                        $isAdmin = $user ? $user->esAdmin() : false;
                        $userPermissions = $user ? $user->obtenerPermisos() : [];
                    @endphp

                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- ============================================ -->
                    <!-- MÓDULO: USUARIOS (Solo Admin) -->
                    <!-- ============================================ -->
                    @if($isAdmin)
                    <li class="nav-item has-treeview {{ Request::routeIs('usuarios.*') || Request::routeIs('empleados.*') || Request::routeIs('roles.*') || Request::routeIs('permisos.*') || Request::routeIs('rol-permisos.*') || Request::routeIs('rol-permiso-usuarios.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>Usuarios <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            {{-- Módulo de Acceso (Destacado) --}}
                            <li class="nav-item">
                                <a href="{{ route('usuarios.create-access') }}" class="nav-link nav-link-acceso {{ Request::routeIs('usuarios.create-access') ? 'active' : '' }}">
                                    <i class="fas fa-lock-open nav-icon" style="color: #4caf50;"></i>
                                    <p style="font-weight: 600;">
                                        <i class="fas fa-star-of-life" style="font-size: 10px;"></i> Módulo de Acceso
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('empleados.index') }}" class="nav-link {{ Request::routeIs('empleados.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Empleados</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('roles.index') }}" class="nav-link {{ Request::routeIs('roles.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Roles</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('permisos.index') }}" class="nav-link {{ Request::routeIs('permisos.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Permisos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('rol-permisos.index') }}" class="nav-link {{ Request::routeIs('rol-permisos.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Rol Permisos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('rol-permiso-usuarios.index') }}" class="nav-link {{ Request::routeIs('rol-permiso-usuarios.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Rol Permiso Usuarios</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    <!-- Clientes -->
                    <li class="nav-item">
                        <a href="{{ route('clientes.index') }}" class="nav-link {{ Request::routeIs('clientes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Clientes</p>
                        </a>
                    </li>

                    <!-- ============================================ -->
                    <!-- MÓDULO: GESTIÓN COMERCIAL -->
                    <!-- ============================================ -->
                    @if(in_array('gestion_comercial_ver', $userPermissions) || $isAdmin)
                    <li class="nav-item has-treeview {{ Request::routeIs('notas-venta.*') || Request::routeIs('detalles-venta.*') || Request::routeIs('notas-compra.*') || Request::routeIs('detalles-compra.*') || Request::routeIs('proveedores.*') || Request::routeIs('ppersona.*') || Request::routeIs('pempresa.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-exchange-alt"></i>
                            <p>Gestión Comercial <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('notas-venta.index') }}" class="nav-link {{ Request::routeIs('notas-venta.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Notas de Venta</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('notas-compra.index') }}" class="nav-link {{ Request::routeIs('notas-compra.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Notas de Compra</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('detalles-venta.index') }}" class="nav-link {{ Request::routeIs('detalles-venta.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Detalles de Venta</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('detalles-compra.index') }}" class="nav-link {{ Request::routeIs('detalles-compra.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Detalles de Compra</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('proveedores.index') }}" class="nav-link {{ Request::routeIs('proveedores.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Proveedores</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ppersona.index') }}" class="nav-link {{ Request::routeIs('ppersona.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Personas Proveedores</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pempresa.index') }}" class="nav-link {{ Request::routeIs('pempresa.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Empresas Proveedoras</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    <!-- ============================================ -->
                    <!-- MÓDULO: ALMACÉN -->
                    <!-- ============================================ -->
                    @if(in_array('almacen_ver', $userPermissions) || $isAdmin)
                    <li class="nav-item has-treeview {{ Request::routeIs('modulo-almacen.*') || Request::routeIs('almacenes.*') || Request::routeIs('productos.*') || Request::routeIs('items.*') || Request::routeIs('insumos.*') || Request::routeIs('almacen-items.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>Almacén <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            {{-- Panel de Almacén (Destacado) --}}
                            @if(in_array('panel_almacen_ver', $userPermissions) || $isAdmin)
                            <li class="nav-item">
                                <a href="{{ route('modulo-almacen.index') }}" class="nav-link nav-link-modulo-destacado {{ Request::routeIs('modulo-almacen.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-warehouse"></i>
                                    <p style="font-weight: 600;"><i class="fas fa-star-of-life" style="font-size: 10px;"></i> Panel de Almacén</p>
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ route('almacenes.index') }}" class="nav-link {{ Request::routeIs('almacenes.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Almacenes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('productos.index') }}" class="nav-link {{ Request::routeIs('productos.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Productos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('items.index') }}" class="nav-link {{ Request::routeIs('items.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Items</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('insumos.index') }}" class="nav-link {{ Request::routeIs('insumos.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Insumos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('almacen-items.index') }}" class="nav-link {{ Request::routeIs('almacen-items.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Inventario (Stock)</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    <!-- Reportes -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Reportes</p>
                        </a>
                    </li>

                    <!-- ============================================ -->
                    <!-- MÓDULO: INVENTARIO -->
                    <!-- ============================================ -->
                    @if(in_array('inventario_ver', $userPermissions) || $isAdmin)
                    <li class="nav-item has-treeview {{ Request::routeIs('movimientos.*') || Request::routeIs('traspasos.*') || Request::routeIs('lotes.*') || Request::routeIs('configuracion.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>Inventario <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('movimientos.index') }}" class="nav-link {{ Request::routeIs('movimientos.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Movimientos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('traspasos.index') }}" class="nav-link {{ Request::routeIs('traspasos.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Traspasos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('lotes.index') }}" class="nav-link {{ Request::routeIs('lotes.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Lotes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('configuracion.edit') }}" class="nav-link {{ Request::routeIs('configuracion.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Configuración</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    <!-- ============================================ -->
                    <!-- MÓDULO: PRODUCCIÓN -->
                    <!-- ============================================ -->
                    @if(in_array('produccion_ver', $userPermissions) || $isAdmin)
                    <li class="nav-item has-treeview {{ Request::routeIs('produccion.*') || Request::routeIs('recetas.*') || Request::routeIs('detalles-receta.*') || Request::routeIs('producciones.*') || Request::routeIs('produccion-items.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-industry"></i>
                            <p>Producción <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            {{-- Panel de Producción (Destacado) --}}
                            @if(in_array('panel_produccion_ver', $userPermissions) || $isAdmin)
                            <li class="nav-item">
                                <a href="{{ route('produccion.index') }}" class="nav-link nav-link-modulo-destacado {{ Request::routeIs('produccion.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-industry"></i>
                                    <p style="font-weight: 600;"><i class="fas fa-star-of-life" style="font-size: 10px;"></i> Panel de Producción</p>
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ route('recetas.index') }}" class="nav-link {{ Request::routeIs('recetas.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Recetas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('detalles-receta.index') }}" class="nav-link {{ Request::routeIs('detalles-receta.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Detalles Receta</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('producciones.index') }}" class="nav-link {{ Request::routeIs('producciones.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Producciones</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('produccion-items.index') }}" class="nav-link {{ Request::routeIs('produccion-items.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Items Almacén</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper" style="background-color: #FFF9F0;">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0" style="color: #5D3A1A;">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline-block">
            <i class="fas fa-bread-slice"></i> Hecho con amor
        </div>
        <strong>
            <i class="fas fa-copyright"></i> {{ date('Y') }} Panadería Otto.
        </strong>
        Todos los derechos reservados.
    </footer>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
    // Activar el treeview automáticamente para los menús activos
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

@stack('scripts')
</body>
</html>