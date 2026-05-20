<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panadería Otto - Sistema de Gestión')</title>

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
    <link rel="stylesheet" href="{{ asset('css/panaderia-theme.css') }}">

    {{-- Determinar tema activo --}}
    @php
        $theme = session('theme', 'jovenes');
        $mode  = session('mode', 'auto');
    @endphp

    {{-- Cargar hojas de tema --}}
    @if($mode === 'auto')
        <link rel="stylesheet" href="{{ asset("css/themes/{$theme}/light.css") }}">
        <link rel="stylesheet" href="{{ asset("css/themes/{$theme}/dark.css") }}">
    @else
        <link rel="stylesheet" href="{{ asset("css/themes/{$theme}/{$mode}.css") }}">
    @endif

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed"
      data-theme="{{ $theme }}"
      data-mode="{{ $mode }}">

<div class="wrapper">

    {{-- ============================================ --}}
    {{-- NAVBAR SUPERIOR                              --}}
    {{-- ============================================ --}}
        <nav class="main-header navbar navbar-expand">
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

        <ul class="navbar-nav ml-auto">

            {{-- Usuario --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user"></i> Mi Perfil
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-cog"></i> Configuración
                    </a>

                    {{-- Selector de Tema --}}
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

                    {{-- Selector de Modo --}}
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
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    {{-- ============================================ --}}
    {{-- SIDEBAR PRINCIPAL                           --}}
    {{-- ============================================ --}}
    <aside class="main-sidebar elevation-4">
        <a href="{{ route('landing') }}" class="brand-link d-flex align-items-center justify-content-center">
            <i class="fas fa-bread-slice fa-2x mr-2 brand-icon"></i>
            <span class="brand-text font-weight-bold">Panadería Otto</span>
        </a>

        <div class="sidebar">
            {{-- User Panel --}}
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <i class="fas fa-user-circle fa-2x"></i>
                </div>
                <div class="info">
                    <a href="#" class="d-block">
                        {{ Auth::user()->name ?? 'Usuario' }}
                        <span class="user-status"></span>
                    </a>
                    <span class="user-role-badge">
                        @php
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
                        @endphp
                    </span>
                </div>
            </div>

            <div class="text-center mb-3">
                <small class="sidebar-section-title">
                    <i class="fas fa-bread-slice"></i> Menú Principal
                </small>
            </div>

            {{-- Menú de navegación --}}
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                    @php
                        $user = Auth::user();
                        $isAdmin = $user ? $user->esAdmin() : false;
                        $userPermissions = $user ? $user->obtenerPermisos() : [];
                    @endphp

                    {{-- Dashboard --}}
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    {{-- MÓDULO: USUARIOS --}}
                    @if($isAdmin)
                    <li class="nav-item has-treeview {{ 
                        Request::routeIs('usuarios.*') || 
                        Request::routeIs('personas.*') || 
                        Request::routeIs('roles.*') || 
                        Request::routeIs('permisos.*') || 
                        Request::routeIs('rol_permisos.*') || 
                        Request::routeIs('rol-permiso-usuarios.*') 
                        ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ 
                            Request::routeIs('usuarios.*') || 
                            Request::routeIs('personas.*') || 
                            Request::routeIs('roles.*') || 
                            Request::routeIs('permisos.*') || 
                            Request::routeIs('rol_permisos.*') || 
                            Request::routeIs('rol-permiso-usuarios.*') 
                            ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>Usuarios <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('usuarios.create-access') }}" 
                                   class="nav-link nav-link-acceso {{ Request::routeIs('usuarios.create-access') ? 'active' : '' }}">
                                    <i class="fas fa-lock-open nav-icon"></i>
                                    <p>Módulo de Acceso</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('personas.index') }}" 
                                   class="nav-link {{ Request::routeIs('personas.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Personas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('rol_permisos.index') }}" 
                                   class="nav-link {{ Request::routeIs('rol_permisos.*') || Request::routeIs('roles.*') || Request::routeIs('permisos.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Roles y Permisos</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    {{-- MÓDULO: GESTIÓN COMERCIAL --}}
                    @if(in_array('gestion_comercial_ver', $userPermissions) || $isAdmin)
                    <li class="nav-item has-treeview {{ Request::routeIs('notas-venta.*') || Request::routeIs('detalles-venta.*') || Request::routeIs('notas-compra.*') || Request::routeIs('detalles-compra.*') || Request::routeIs('proveedores.*') || Request::routeIs('ppersona.*') || Request::routeIs('pempresa.*') || Request::routeIs('compras.*') || Request::routeIs('ventas.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-exchange-alt"></i>
                            <p>Gestión Comercial <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('compras.index') }}" class="nav-link nav-link-modulo-destacado {{ Request::routeIs('compras.index') ? 'active' : '' }}">
                                    <i class="fas fa-shopping-cart nav-icon"></i>
                                    <p>Panel de Compras</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ventas.index') }}" class="nav-link nav-link-modulo-destacado {{ Request::routeIs('ventas.index') ? 'active' : '' }}">
                                    <i class="fas fa-cart-shopping nav-icon"></i>
                                    <p>Panel de Ventas</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('detalles-venta.index') }}" class="nav-link {{ Request::routeIs('detalles-venta.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Registros de Venta</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('detalles-compra.index') }}" class="nav-link {{ Request::routeIs('detalles-compra.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Registros de Compra</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('proveedores.index') }}" class="nav-link {{ Request::routeIs('proveedores.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Proveedores</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    {{-- MÓDULO: ALMACÉN --}}
                    @if(in_array('almacen_ver', $userPermissions) || $isAdmin)
                    <li class="nav-item has-treeview {{ Request::routeIs('modulo-almacen.*') || Request::routeIs('almacenes.*') || Request::routeIs('productos.*') || Request::routeIs('items.*') || Request::routeIs('insumos.*') || Request::routeIs('almacen-items.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>Almacén <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(in_array('panel_almacen_ver', $userPermissions) || $isAdmin)
                            <li class="nav-item">
                                <a href="{{ route('modulo-almacen.index') }}" class="nav-link nav-link-modulo-destacado {{ Request::routeIs('modulo-almacen.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-warehouse"></i>
                                    <p>Panel de Almacén</p>
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{ route('items.index') }}" class="nav-link {{ Request::routeIs('items.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Items</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('almacen-items.index') }}" class="nav-link {{ Request::routeIs('almacen-items.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Almacén-Items</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    {{-- MÓDULO: REPORTES --}}
                    @if(in_array('reportes_ver', $userPermissions) || $isAdmin)
                    <li class="nav-item">
                        <a href="{{ route('reportes.index') }}" class="nav-link {{ Request::routeIs('reportes.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Reportes</p>
                        </a>
                    </li>
                    @endif

                    {{-- MÓDULO: INVENTARIO --}}
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

                    {{-- MÓDULO: PRODUCCIÓN --}}
                    @if(in_array('produccion_ver', $userPermissions) || in_array('inventario_ver', $userPermissions) || $isAdmin)
                    <li class="nav-item has-treeview {{ Request::routeIs('produccion.*') || Request::routeIs('recetas.*') || Request::routeIs('detalles-receta.*') || Request::routeIs('producciones.*') || Request::routeIs('produccion-items.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-industry"></i>
                            <p>Producción <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if(in_array('panel_produccion_ver', $userPermissions) || $isAdmin)
                            <li class="nav-item">
                                <a href="{{ route('produccion.index') }}" class="nav-link nav-link-modulo-destacado {{ Request::routeIs('produccion.index') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-industry"></i>
                                    <p>Panel de Producción</p>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route('detalles-receta.index') }}" class="nav-link {{ Request::routeIs('detalles-receta.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Detalles Receta</p>
                                </a>
                            </li>
                            
                            @endif

                            <li class="nav-item">
                                <a href="{{ route('producciones.index') }}" class="nav-link {{ Request::routeIs('producciones.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i><p>Producciones</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                </ul>
            </nav>

            {{-- Footer del sidebar --}}
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

    {{-- ============================================ --}}
    {{-- CONTENT WRAPPER                             --}}
    {{-- ============================================ --}}
    <div class="content-wrapper bg-panaderia-light">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        @hasSection('page-title')
                            <h1 class="m-0">@yield('page-title')</h1>
                            @hasSection('page-description')
                                <small class="text-muted">@yield('page-description')</small>
                            @endif
                        @endif
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    {{-- ============================================ --}}
    {{-- FOOTER                                      --}}
    {{-- ============================================ --}}
    <footer class="main-footer">
        <div class="float-right d-none d-sm-inline-block">
            <i class="fas fa-bread-slice"></i> Hecho con amor
        </div>
        <strong>
            <i class="fas fa-copyright"></i> {{ date('Y') }} Panadería Otto.
        </strong>
        Todos los derechos reservados.
        
        {{-- Contador de visitas --}}
        <div class="float-right d-none d-sm-inline-block mr-3">
            <small class="text-muted">
                <i class="fas fa-eye"></i> Esta página ha sido visitada {{ $pageVisitCount ?? 0 }} veces
            </small>
        </div>
    </footer>
</div>

{{-- ============================================ --}}
{{-- MODAL ACERCA DE                             --}}
{{-- ============================================ --}}
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
                    <i class="fas fa-copyright"></i> {{ date('Y') }} Panadería Otto.<br>
                    Todos los derechos reservados.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- SCRIPTS                                     --}}
{{-- ============================================ --}}
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

        // ============================================
        // MANEJO GLOBAL DE FORMULARIOS MODALES POR AJAX
        // ============================================
        var isSubmittingModal = false;

        function manejarFormularioModal(formId, modalId, loadingText, successMessage, errorMessage, onSuccess) {
            $(document).on('submit', formId, function(e) {
                e.preventDefault();
                if (isSubmittingModal) return false;
                
                var $form = $(this);
                var $btn = $form.find('button[type="submit"]');
                var originalText = $btn.html();
                
                // Si el formulario tiene archivos, usar FormData
                var hasFile = $form.attr('enctype') === 'multipart/form-data' || $form.find('input[type="file"]').length > 0;
                
                $btn.html('<i class="fas fa-spinner fa-spin"></i> ' + loadingText).prop('disabled', true);
                isSubmittingModal = true;
                
                var ajaxOptions = {
                    url: $form.attr('action'),
                    method: 'POST',
                    data: hasFile ? new FormData($form[0]) : $form.serialize(),
                    processData: !hasFile,
                    contentType: hasFile ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: function(response) {
                        if (response.success) {
                            $(modalId).modal('hide');
                            $form[0].reset();
                            
                            toastr.success(response.message || successMessage);
                            
                            if (typeof onSuccess === 'function') {
                                onSuccess(response);
                            }
                            
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            toastr.error(response.message || 'Error al procesar');
                            $btn.html(originalText).prop('disabled', false);
                            isSubmittingModal = false;
                        }
                    },
                    error: function(xhr) {
                        var message = errorMessage;
                        if (xhr.responseJSON?.errors) {
                            message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                        } else if (xhr.responseJSON?.message) {
                            message = xhr.responseJSON.message;
                        }
                        toastr.error(message);
                        $btn.html(originalText).prop('disabled', false);
                        isSubmittingModal = false;
                    }
                };
                
                $.ajax(ajaxOptions);
            });
        }

        // Inicializar todos los formularios modales del sistema
        $(document).ready(function() {
            // Módulo Almacén
            manejarFormularioModal('#formCreateAlmacen', '#createAlmacenModal', 'Creando...', 'Almacén creado', 'Error al crear almacén');
            manejarFormularioModal('#formCreateInsumo', '#createInsumoModal', 'Creando...', 'Insumo creado', 'Error al crear insumo');
            manejarFormularioModal('#formCreateCategoriaInsumo', '#createCategoriaInsumoModal', 'Creando...', 'Categoría creada', 'Error al crear categoría');
            manejarFormularioModal('#formCreateCategoriaProducto', '#createCategoriaProductoModal', 'Creando...', 'Categoría creada', 'Error al crear categoría');
            manejarFormularioModal('#formManageStock', '#manageStockModal', 'Procesando...', 'Stock actualizado', 'Error al gestionar stock');
            manejarFormularioModal('#formCreateProducto', '#createProductoModal', 'Creando...', 'Producto creado', 'Error al crear producto');
            
            // Proveedores
            manejarFormularioModal('#formCreateProveedor', '#modalProveedor', 'Creando...', 'Proveedor creado', 'Error al crear proveedor');
            
            // Empleados y clientes
            manejarFormularioModal('#formCrearEmpleado', '#createEmpleadoModal', 'Creando...', 'Empleado creado', 'Error al crear empleado');
            manejarFormularioModal('#formCrearCliente', '#createClienteModal', 'Creando...', 'Cliente creado', 'Error al crear cliente');
            
            // Usuarios
            manejarFormularioModal('#formCrearUsuario', '#createUsuarioModal', 'Creando...', 'Usuario creado', 'Error al crear usuario');
            
            // Reset al cerrar modales
            $('.modal').on('hidden.bs.modal', function() {
                isSubmittingModal = false;
                $(this).find('button[type="submit"]').prop('disabled', false);
            });
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

{{-- Control de temas --}}
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
})();

// Cambiar tema
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

// Cambiar modo (guarda en sesión y recarga)
function cambiarModo(nuevoModo) {
    fetch('/theme/change', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ mode: nuevoModo })
    }).then(() => window.location.reload());
}

$(document).ready(function() {

    // ==========================================
    // CREAR EMPLEADO POR AJAX (Global)
    // ==========================================
    var isSubmittingEmpleado = false;
    
    $(document).on('submit', '#formCrearEmpleado', function(e) {
        e.preventDefault();
        
        if (isSubmittingEmpleado) return false;
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Creando...').prop('disabled', true);
        isSubmittingEmpleado = true;
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createEmpleadoModal').modal('hide');
                    form[0].reset();
                    
                    // Notificación con Swal o toastr
                    Swal.fire({
                        icon: 'success',
                        title: '¡Empleado creado!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Agregar a selects si existen
                    if (response.empleado) {
                        var newOption = new Option(
                            response.empleado.nombre + ' ' + (response.empleado.apellido || ''),
                            response.empleado.id_empleado,
                            true,
                            true
                        );
                        $('#id_empleado, #edit_id_empleado').append(newOption);
                        $('#id_empleado').val(response.empleado.id_empleado).trigger('change');
                    }
                    
                    // Recargar después de un momento
                    setTimeout(() => location.reload(), 1500);
                }
            },
            error: function(xhr) {
                var message = 'Error al crear empleado';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire('Error', message, 'error');
            },
            complete: function() {
                submitBtn.html(originalText).prop('disabled', false);
                isSubmittingEmpleado = false;
            }
        });
    });

    // ==========================================
    // CREAR CLIENTE POR AJAX (Global)
    // ==========================================
    var isSubmittingCliente = false;
    
    $(document).on('submit', '#formCrearCliente', function(e) {
        e.preventDefault();
        
        if (isSubmittingCliente) return false;
        
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Creando...').prop('disabled', true);
        isSubmittingCliente = true;
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createClienteModal').modal('hide');
                    form[0].reset();
                    
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cliente creado!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    if (response.cliente) {
                        var newOption = new Option(
                            response.cliente.nombre + ' ' + (response.cliente.apellido || ''),
                            response.cliente.id_cliente,
                            true,
                            true
                        );
                        $('#id_cliente, #edit_id_cliente').append(newOption);
                        $('#id_cliente').val(response.cliente.id_cliente).trigger('change');
                    }
                    
                    setTimeout(() => location.reload(), 1500);
                }
            },
            error: function(xhr) {
                var message = 'Error al crear cliente';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire('Error', message, 'error');
            },
            complete: function() {
                submitBtn.html(originalText).prop('disabled', false);
                isSubmittingCliente = false;
            }
        });
    });

});
</script>

@stack('scripts')
</body>
</html>