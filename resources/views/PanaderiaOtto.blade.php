<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panadería Otto - Productos Artesanales</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @php
        $theme = session('theme', 'jovenes');
        $mode  = session('mode', 'auto');
    @endphp

    <link rel="stylesheet" href="{{ asset("css/themes/{$theme}/light.css") }}">
    <link rel="stylesheet" href="{{ asset("css/themes/{$theme}/dark.css") }}">

    <style>
        :root {
            --color-whatsapp: #25D366;
            --border-radius-sm: 15px;
            --border-radius-md: 20px;
            --border-radius-lg: 30px;
            --border-radius-xl: 50px;
            --transition-speed: 0.3s;
        }

        * { font-family: 'Poppins', sans-serif; }

        body {
            background-color: var(--color-bg-light, #FFF9F0);
            color: var(--text-primary, #333);
        }

        /* ========== NAVBAR ========== */
        .navbar-panaderia {
            background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
            padding: 0.5rem 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .navbar-brand { font-size: 1.5rem; font-weight: 700; color: #fff !important; }
        .navbar-brand i { color: var(--color-accent, #D2B48C); margin-right: 8px; }
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.5rem 0.6rem !important;
            font-size: 0.9rem;
            transition: all var(--transition-speed) ease;
        }
        .nav-link:hover { color: var(--color-accent, #D2B48C) !important; }

        /* Botón carrito compacto */
        .btn-cart {
            background: var(--color-accent, #D2B48C);
            color: var(--color-primary-dark, #5D3A1A) !important;
            border-radius: 50%;
            width: 38px;
            height: 38px;
            padding: 0 !important;
            font-weight: 600;
            transition: all var(--transition-speed) ease;
            position: relative;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-cart:hover { background: #fff; transform: scale(1.1); }
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .btn-login {
            background: var(--color-accent, #D2B48C);
            color: var(--color-primary-dark, #5D3A1A) !important;
            border-radius: var(--border-radius-xl);
            padding: 6px 16px !important;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all var(--transition-speed) ease;
        }
        .btn-login:hover { background: #fff; transform: translateY(-2px); }

        .user-menu-wrapper { display: flex; align-items: center; gap: 2px; }
        .btn-user-info {
            background: var(--color-accent, #D2B48C);
            color: var(--color-primary-dark, #5D3A1A) !important;
            border-radius: var(--border-radius-xl) 0 0 var(--border-radius-xl);
            padding: 6px 12px !important;
            font-weight: 600;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 6px;
            border: none;
            white-space: nowrap;
        }
        .btn-sistema {
            background: #fff;
            color: var(--color-primary-dark, #5D3A1A) !important;
            padding: 6px 10px !important;
            font-weight: 600;
            font-size: 0.8rem;
            border: none;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            text-decoration: none;
        }
        .btn-sistema:hover { background: var(--color-bg-lighter, #FFF5E6); }
        .btn-logout {
            background: #c0392b;
            color: #fff !important;
            border-radius: 0 var(--border-radius-xl) var(--border-radius-xl) 0;
            padding: 6px 10px !important;
            border: none;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
        }
        .btn-logout:hover { background: #e74c3c; }

        /* Dropdown temas */
        .dropdown-menu {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            background: var(--dropdown-bg, #fff);
        }
        .dropdown-item {
            font-size: 0.85rem;
            padding: 8px 16px;
            color: var(--dropdown-item-text, #333);
        }
        .dropdown-item:hover {
            background: var(--dropdown-item-hover-bg, #FFF5E6);
            color: var(--dropdown-item-hover-text, #5D3A1A);
        }
        .dropdown-header { color: var(--text-muted, #6c757d); font-size: 0.75rem; }

        /* ========== HERO ========== */
        .hero {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary, #A0522D) 100%);
            padding: 80px 0;
            margin-bottom: 50px;
        }
        .hero h1 { font-size: 3rem; font-weight: 700; }
        .hero p { font-size: 1.2rem; opacity: 0.95; }

        /* ========== PRODUCT CARDS ========== */
        .product-card {
            background: var(--bg-card, #fff);
            border-radius: var(--border-radius-md);
            overflow: hidden;
            transition: all var(--transition-speed) ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            height: 100%;
        }
        .product-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.15); }
        .product-img { height: 220px; background-size: cover; background-position: center; position: relative; }
        .product-badge {
            position: absolute; top: 15px; right: 15px;
            background: var(--color-primary, #8B4513);
            color: #fff;
            padding: 5px 12px;
            border-radius: var(--border-radius-xl);
            font-size: 0.8rem;
        }
        .product-body { padding: 20px; }
        .product-title { font-size: 1.3rem; font-weight: 600; color: var(--color-primary-dark, #5D3A1A); margin-bottom: 10px; }
        .product-desc { color: var(--text-muted, #666); font-size: 0.9rem; margin-bottom: 15px; }
        .product-price { font-size: 1.5rem; font-weight: 700; color: var(--color-primary, #8B4513); }

        /* ========== ANUNCIOS ========== */
        .anuncio {
            background: linear-gradient(135deg, var(--color-accent, #D2B48C) 0%, var(--color-accent-dark, #c9a871) 100%);
            border-radius: var(--border-radius-md);
            padding: 30px; margin-bottom: 30px;
            transition: all var(--transition-speed) ease;
        }
        .anuncio h3 { color: var(--color-primary-dark, #5D3A1A); font-weight: 700; }
        .anuncio p { color: var(--color-primary-dark, #3E2510); }
        .anuncio i { font-size: 2.5rem; color: var(--color-primary-dark, #5D3A1A); }

        /* ========== PROMOS ========== */
        .promo-section {
            background: linear-gradient(135deg, var(--color-bg-lighter, #FFF5E6) 0%, var(--color-bg-light, #FFF9F0) 100%);
            padding: 60px 0; margin: 50px 0;
            border-radius: var(--border-radius-lg);
        }
        .promo-card {
            background: var(--bg-card, #fff);
            border-radius: var(--border-radius-sm);
            padding: 25px; text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            height: 100%;
        }

        /* ========== FOOTER ========== */
        .footer {
            background: linear-gradient(135deg, var(--color-primary-dark, #5D3A1A) 0%, #3E2510 100%);
            color: #fff;
            padding: 50px 0 20px; margin-top: 50px;
        }
        .footer h5 { color: var(--color-accent, #D2B48C); }
        .footer a { color: rgba(255,255,255,0.8); text-decoration: none; transition: color var(--transition-speed) ease; }
        .footer a:hover { color: var(--color-accent, #D2B48C); }
        .social-icons i { font-size: 1.5rem; margin-right: 15px; transition: all var(--transition-speed) ease; }
        .social-icons i:hover { transform: translateY(-3px); color: var(--color-accent, #D2B48C) !important; }
        .page-visit-counter {
            display: inline-block;
            margin-top: 10px;
            font-size: 0.75rem;
            opacity: 0.7;
        }

        /* ========== WHATSAPP ========== */
        .whatsapp-float {
            position: fixed; bottom: 30px; right: 30px;
            background: var(--color-whatsapp); color: #fff;
            width: 60px; height: 60px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transition: all var(--transition-speed) ease;
            z-index: 1000;
        }
        .whatsapp-float:hover { transform: scale(1.1); }

        /* ========== MODAL ADAPTADO A MODO OSCURO ========== */
        .modal-content {
            background: var(--bg-card, #fff);
            color: var(--text-primary, #333);
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2rem; }
            .hero { padding: 50px 0; }
            .product-img { height: 180px; }
        }
    </style>
</head>
<body data-theme="{{ $theme }}" data-mode="{{ $mode }}">

    <!-- Navbar compacto -->
    <nav class="navbar navbar-expand-lg navbar-panaderia sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-bread-slice"></i> Panadería Otto
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#productos">Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#ofertas">Ofertas</a></li>
                    <li class="nav-item"><a class="nav-link" href="#nosotros">Nosotros</a></li>

                    {{-- Búsqueda --}}
                    <li class="nav-item mx-2">
                        <form action="{{ route('buscar') }}" method="GET" class="d-flex">
                            <div class="input-group input-group-sm" style="width: 180px;">
                                <input type="text" name="q" class="form-control" 
                                       placeholder="Buscar..." value="{{ request('q') }}" 
                                       style="border-radius: 50px 0 0 50px;">
                                <button type="submit" class="btn btn-outline-light" style="border-radius: 0 50px 50px 0;">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </li>

                    {{-- Tema --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" style="font-size:0.85rem;">
                            <i class="fas fa-palette"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Tema</h6></li>
                            <li><a class="dropdown-item" href="#" onclick="cambiarTema('ninos')"><i class="fas fa-child me-2"></i>Niños</a></li>
                            <li><a class="dropdown-item" href="#" onclick="cambiarTema('jovenes')"><i class="fas fa-user me-2"></i>Jóvenes</a></li>
                            <li><a class="dropdown-item" href="#" onclick="cambiarTema('adultos')"><i class="fas fa-user-tie me-2"></i>Adultos</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Modo</h6></li>
                            <li><a class="dropdown-item" href="#" onclick="cambiarModo('light')"><i class="fas fa-sun me-2"></i>Día</a></li>
                            <li><a class="dropdown-item" href="#" onclick="cambiarModo('dark')"><i class="fas fa-moon me-2"></i>Noche</a></li>
                            <li><a class="dropdown-item" href="#" onclick="cambiarModo('auto')"><i class="fas fa-magic me-2"></i>Auto</a></li>
                        </ul>
                    </li>

                    {{-- Carrito compacto --}}
                    <li class="nav-item ms-2">
                        <button class="btn-cart" onclick="verCarrito()" title="Ver carrito">
                            <i class="fas fa-shopping-cart"></i>
                            <span id="cartCount" class="cart-badge">0</span>
                        </button>
                    </li>

                    {{-- Usuario --}}
                    @auth
                        <li class="nav-item dropdown ms-1">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i>
                                {{ Str::limit(Auth::user()->name ?? explode('@', Auth::user()->correo)[0], 12) }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                {{-- Mis Pedidos --}}
                                <a class="dropdown-item" href="{{ route('mis-pedidos') }}">
                                    <i class="fas fa-shopping-bag"></i> Mis Pedidos
                                </a>
                                
                                {{-- Acceso al sistema (solo empleados/admin) --}}
                                @if(Auth::user()->tipo_usuario === 'empleado' || (method_exists(Auth::user(), 'esAdmin') && Auth::user()->esAdmin()))
                                    <a class="dropdown-item" href="{{ url('/home') }}">
                                        <i class="fas fa-desktop"></i> Panel de Control
                                    </a>
                                @endif
                                
                                <hr class="dropdown-divider">
                                
                                {{-- Cerrar Sesión --}}
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </li>
                    @else
                        <li class="nav-item ms-1">
                            <a class="nav-link btn-login" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Ingresar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="hero text-white text-center">
        <div class="container">
            <h1>Pan Artesanal Hecho con Amor</h1>
            <p class="lead">Descubre nuestra selección de productos frescos, horneados diariamente</p>
            <a href="#productos" class="btn btn-light btn-lg mt-3" style="border-radius:50px; color:var(--color-primary); font-weight:600;">
                Ver Productos <i class="fas fa-arrow-down"></i>
            </a>
        </div>
    </section>

    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <div class="anuncio text-center">
                    <i class="fas fa-gift"></i>
                    <h3>¡Lleva 3 panes y paga 2!</h3>
                    <p>Válido todos los lunes y martes. Aplica para pan francés y pan integral.</p>
                </div>
            </div>
        </div>

        <div class="text-center mb-5" id="productos">
            <h2 style="color: var(--color-primary-dark, #5D3A1A); font-weight: 700;">Nuestros Productos</h2>
            <p style="color: var(--text-muted, #A0522D);">Los más deliciosos productos recién horneados</p>
            <div class="divider" style="width: 80px; height: 3px; background: var(--color-accent, #D2B48C); margin: 15px auto;"></div>
        </div>

        <div class="row">
            @forelse($productosConStock ?? [] as $producto)
                <div class="col-lg-3 col-md-6">
                    <div class="product-card">
                        @php
                            $imagenUrl = $producto->imagen ?: 'https://placehold.co/300x220/8B4513/white?text=Pan+Otto';
                            $nivel = $producto->nivel_stock;
                        @endphp

                        <div class="product-img" style="background-image: url('{{ $imagenUrl }}');">
                            <span class="product-badge">{{ $producto->categoria }}</span>
                            @if($nivel['disabled'])
                                <span class="product-badge" style="top: 50px; background: #dc3545;">Agotado</span>
                            @endif
                        </div>
                        <div class="product-body">
                            <h3 class="product-title">{{ $producto->nombre }}</h3>
                            <p class="product-desc">{{ Str::limit($producto->descripcion ?: 'Delicioso producto artesanal', 80) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="product-price">Bs. {{ number_format($producto->precio, 2) }}</div>
                                <button class="btn btn-sm agregar-carrito" 
                                        style="background: var(--color-accent, #D2B48C); color: var(--color-primary-dark, #5D3A1A); border-radius: var(--border-radius-xl); padding: 8px 16px; font-weight: 600;"
                                        data-id-almacen="{{ $producto->id_almacen }}"
                                        data-id-item="{{ $producto->id_item }}"
                                        data-nombre="{{ $producto->nombre }}"
                                        data-precio="{{ $producto->precio }}"
                                        data-stock="{{ $producto->stock_total }}"
                                        data-imagen="{{ $producto->imagen }}"
                                        {{ $nivel['disabled'] ? 'disabled' : '' }}>
                                    <i class="fas fa-shopping-cart"></i> Agregar
                                </button>
                            </div>
                            
                            {{-- Indicador de stock visual (sin números) --}}
                            <div class="stock-indicator">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="fas {{ $nivel['icono'] }} text-{{ $nivel['clase'] }}"></i>
                                    <small class="text-{{ $nivel['clase'] }} fw-bold">
                                        {{ $nivel['texto'] }}
                                    </small>
                                    <small class="text-muted ms-2">{{ $nivel['mensaje'] }}</small>
                                </div>
                                
                                {{-- Barra de disponibilidad --}}
                                <div class="progress mt-2" style="height: 6px; border-radius: 3px; background: #e9ecef;">
                                    <div class="progress-bar bg-{{ $nivel['clase'] }}" 
                                        style="width: {{ $nivel['barra_width'] }}%; transition: width 0.5s ease;"
                                        role="progressbar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5>No hay productos disponibles</h5>
                    <p class="text-muted">Vuelve pronto para ver nuestras delicias.</p>
                </div>
            @endforelse
        </div>

                <!-- Segundo Anuncio -->
        <div class="row mt-4 mb-5">
            <div class="col-md-6">
                <div class="anuncio text-center h-100 d-flex flex-column justify-content-center">
                    <i class="fas fa-truck"></i>
                    <h3>Envíos a Domicilio</h3>
                    <p>Pedidos mínimos de Bs. 150. Entrega en menos de 1 hora en zona centro.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="anuncio text-center h-100 d-flex flex-column justify-content-center" 
                     style="background: linear-gradient(135deg, var(--color-primary, #8B4513) 0%, var(--color-secondary, #A0522D) 100%);">
                    <i class="fas fa-percent" style="color: #fff;"></i>
                    <h3 style="color: #fff;">Descuento por Volumen</h3>
                    <p style="color: var(--color-accent, #D2B48C);">Compra al mayoreo y obtén hasta 20% de descuento.</p>
                </div>
            </div>
        </div>

        <!-- Modal del Carrito -->
        <div class="modal fade" id="cartModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background: linear-gradient(135deg, var(--color-primary-dark, #5D3A1A) 0%, var(--color-primary, #8B4513) 100%);">
                        <h5 class="modal-title text-white"><i class="fas fa-shopping-cart"></i> Mi Carrito</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="cartContent">
                        <div class="text-center py-5">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p class="mt-2">Cargando carrito...</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Seguir Comprando</button>
                        <button type="button" class="btn" id="btnProcesarPedido" 
                                style="background: var(--color-primary, #8B4513); color: #fff;">
                            <i class="fas fa-check-circle"></i> Procesar Pedido
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Promociones -->
        <div class="promo-section" id="ofertas">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 style="color: var(--color-primary-dark, #5D3A1A); font-weight: 700;">Promociones Especiales</h2>
                    <p style="color: var(--text-muted, #A0522D);">Aprovecha nuestras ofertas por tiempo limitado</p>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="promo-card">
                            <i class="fas fa-coffee fa-3x" style="color: var(--color-primary, #8B4513);"></i>
                            <h4 class="mt-3" style="color: var(--color-primary-dark, #5D3A1A);">Café + Pan</h4>
                            <p>Combo de café americano + pan francés</p>
                            <h3 style="color: var(--color-primary, #8B4513); font-weight: 700;">Bs. 45.00</h3>
                            <small class="text-muted">Precio regular: Bs. 60</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="promo-card">
                            <i class="fas fa-birthday-cake fa-3x" style="color: var(--color-primary, #8B4513);"></i>
                            <h4 class="mt-3" style="color: var(--color-primary-dark, #5D3A1A);">Pastel Personalizado</h4>
                            <p>10% de descuento en tu pastel de cumpleaños</p>
                            <h3 style="color: var(--color-primary, #8B4513); font-weight: 700;">-10% OFF</h3>
                            <small class="text-muted">Presenta tu carnet</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="promo-card">
                            <i class="fas fa-gift fa-3x" style="color: var(--color-primary, #8B4513);"></i>
                            <h4 class="mt-3" style="color: var(--color-primary-dark, #5D3A1A);">Lunes de Descuento</h4>
                            <p>15% en toda la panadería los lunes</p>
                            <h3 style="color: var(--color-primary, #8B4513); font-weight: 700;">15% OFF</h3>
                            <small class="text-muted">Aplican términos y condiciones</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección Nosotros -->
        <div class="row align-items-center mt-5" id="nosotros">
            <div class="col-md-6">
                <img src="https://images.unsplash.com/photo-1586444248902-2f64eddc13df?w=600&h=400&fit=crop" 
                     alt="Panadería Otto" class="img-fluid shadow" style="border-radius: var(--border-radius-md);">
            </div>
            <div class="col-md-6">
                <h2 style="color: var(--color-primary-dark, #5D3A1A); font-weight: 700;">Más de 20 años de tradición</h2>
                <p style="color: var(--text-muted, #666); line-height: 1.8;">En Panadería Otto nos dedicamos a elaborar productos de panadería y repostería con ingredientes de la más alta calidad. Nuestra tradición familiar y el amor por lo que hacemos nos permiten ofrecerte el mejor sabor en cada bocado.</p>
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-2x" style="color: var(--color-primary, #8B4513);"></i>
                            <div class="ms-3">
                                <h5 class="mb-0" style="color: var(--color-primary-dark, #5D3A1A);">100% Artesanal</h5>
                                <small class="text-muted">Elaboración tradicional</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-leaf fa-2x" style="color: var(--color-primary, #8B4513);"></i>
                            <div class="ms-3">
                                <h5 class="mb-0" style="color: var(--color-primary-dark, #5D3A1A);">Ingredientes Naturales</h5>
                                <small class="text-muted">Sin conservadores</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-bread-slice"></i> Panadería Otto</h5>
                    <p>Pan artesanal hecho con amor y tradición familiar.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Horario</h5>
                    <p><i class="fas fa-clock"></i> Lun-Sáb: 7AM-9PM</p>
                    <p><i class="fas fa-clock"></i> Dom: 8AM-2PM</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contacto</h5>
                    <p><i class="fas fa-map-marker-alt"></i> Av. Principal #123</p>
                    <p><i class="fas fa-envelope"></i> contacto@panaderiaotto.com</p>
                </div>
            </div>
            <div class="text-center pt-3 mt-3 border-top border-secondary">
                <p class="mb-0">&copy; {{ date('Y') }} Panadería Otto</p>
                <span class="page-visit-counter">
                    <i class="fas fa-eye"></i> {{ $pageVisitCount ?? 0 }} visitas
                </span>
            </div>
        </div>
    </footer>

    <a href="https://wa.me/59112345678" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        (function() {
            const body = document.body;
            let currentMode = body.getAttribute('data-mode');
            if (currentMode === 'auto') {
                const hour = new Date().getHours();
                const isDay = hour >= 6 && hour < 18;
                body.setAttribute('data-mode', isDay ? 'light' : 'dark');
            }
        })();

        // Cambiar tema (recarga la página con el nuevo tema)
        function cambiarTema(t) {
            fetch('/theme/change', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ theme: t })
            }).then(() => location.reload());
        }

        // Cambiar modo (guarda en sesión y recarga)
        function cambiarModo(m) {
            fetch('/theme/change', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ mode: m })
            }).then(() => location.reload());
        }

        let cartModal = null;

        $(document).ready(function() {
            cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
            actualizarContadorCarrito();

            toastr.options = {
                "positionClass": "toast-bottom-right",
                "closeButton": true,
                "progressBar": true,
                "timeOut": "3000"
            };

            $('#btnProcesarPedido').on('click', function() {
                window.location.href = '{{ route("procesar.pedido") }}';
            });
        });

        function agregarAlCarrito(boton) {
            // Obtener datos del botón
            const idAlmacen = $(boton).data('id-almacen');
            const idItem = $(boton).data('id-item');
            const nombre = $(boton).data('nombre');
            const precio = $(boton).data('precio');
            const stockTotal = $(boton).data('stock');
            const imagen = $(boton).data('imagen');
            
            // Solicitar cantidad
            const cantidad = prompt('¿Cuántas unidades deseas?', 1);
            if (!cantidad || isNaN(cantidad) || cantidad <= 0) {
                toastr.warning('Por favor ingresa una cantidad válida');
                return;
            }
            
            // Validar cantidad máxima (sin mostrar números de stock en el mensaje)
            if (cantidad > stockTotal) {
                toastr.warning('La cantidad solicitada supera el stock disponible');
                return;
            }
            
            // Agregar al carrito
            $.ajax({
                url: '{{ route("carrito.agregar") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id_almacen: idAlmacen,
                    id_item: idItem,
                    nombre: nombre,
                    precio: precio,
                    cantidad: parseInt(cantidad),
                    almacen_nombre: '{{ $almacenPorDefecto ?? "Panadería" }}',
                    imagen: imagen
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        actualizarContadorCarrito();
                    }
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Error al agregar al carrito');
                }
            });
        }

        // Actualizar evento click para los botones
        $(document).ready(function() {
            $('.agregar-carrito').on('click', function() {
                agregarAlCarrito(this);
            });
        });

        function verCarrito() {
            $.ajax({
                url: '{{ route("carrito.ver") }}',
                method: 'GET',
                success: function(html) {
                    $('#cartContent').html(html);
                    cartModal.show();
                },
                error: function() { toastr.error('Error al cargar el carrito'); }
            });
        }

        function actualizarCantidad(key, nuevaCantidad) {
            if (nuevaCantidad <= 0) { eliminarProducto(key); return; }
            $.ajax({
                url: '{{ route("carrito.actualizar") }}',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', key: key, cantidad: nuevaCantidad },
                success: function(response) {
                    if (response.success) { toastr.success(response.message); verCarrito(); actualizarContadorCarrito(); }
                },
                error: function() { toastr.error('Error al actualizar cantidad'); }
            });
        }

        function eliminarProducto(key) {
            if (!confirm('¿Eliminar este producto del carrito?')) return;
            $.ajax({
                url: '{{ route("carrito.eliminar") }}',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}', key: key },
                success: function(response) {
                    if (response.success) { toastr.success(response.message); verCarrito(); actualizarContadorCarrito(); }
                },
                error: function() { toastr.error('Error al eliminar producto'); }
            });
        }

        function actualizarContadorCarrito() {
            $.ajax({
                url: '{{ route("carrito.count") }}',
                method: 'GET',
                success: function(response) { $('#cartCount').text(response.count); }
            });
        }
    </script>
</body>
</html>