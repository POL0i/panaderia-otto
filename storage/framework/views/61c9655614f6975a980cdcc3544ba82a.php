<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Panadería Otto - Productos Artesanales</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    
    
    
    <?php
        $theme = session('theme', 'jovenes');
        $mode  = session('mode', 'auto');
    ?>

    <link rel="stylesheet" href="<?php echo e(asset("css/themes/{$theme}/light.css")); ?>">
    <link rel="stylesheet" href="<?php echo e(asset("css/themes/{$theme}/dark.css")); ?>">

    <style>
        /* ==========================================
           SOLO VARIABLES ESTRUCTURALES
           (los colores vienen de los temas CSS)
           ========================================== */
        :root {
            --color-whatsapp: #25D366;
            --border-radius-sm: 15px;
            --border-radius-md: 20px;
            --border-radius-lg: 30px;
            --border-radius-xl: 50px;
            --transition-speed: 0.3s;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--color-bg-light, #FFF9F0);
            color: var(--text-primary, #333);
        }

        /* ========== NAVBAR ========== */
        .navbar-panaderia {
            background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
            padding: 0.5rem 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-size: 1.6rem;
            font-weight: 700;
            color: #fff !important;
        }
        .navbar-brand i {
            color: var(--color-accent, #D2B48C);
            margin-right: 10px;
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all var(--transition-speed) ease;
            padding: 0.5rem 0.8rem !important;
        }
        .nav-link:hover {
            color: var(--color-accent, #D2B48C) !important;
        }

        /* Botones */
        .btn-login {
            background: var(--color-accent, #D2B48C);
            color: var(--color-primary-dark, #5D3A1A) !important;
            border-radius: var(--border-radius-xl);
            padding: 8px 20px !important;
            font-weight: 600;
            transition: all var(--transition-speed) ease;
        }
        .btn-login:hover {
            background: #fff;
            transform: translateY(-2px);
        }

        .user-menu-wrapper {
            display: flex;
            align-items: center;
            gap: 2px;
        }
        .btn-user-info {
            background: var(--color-accent, #D2B48C);
            color: var(--color-primary-dark, #5D3A1A) !important;
            border-radius: var(--border-radius-xl) 0 0 var(--border-radius-xl);
            padding: 8px 15px !important;
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: default;
            border: none;
            white-space: nowrap;
        }
        .btn-user-info i {
            font-size: 1rem;
            color: var(--color-primary-dark, #5D3A1A);
        }

        .btn-sistema {
            background: #fff;
            color: var(--color-primary-dark, #5D3A1A) !important;
            padding: 8px 12px !important;
            font-weight: 600;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            white-space: nowrap;
        }
        .btn-sistema:hover {
            background: var(--color-bg-lighter, #FFF5E6);
        }

        .btn-logout {
            background: #c0392b;
            color: #fff !important;
            border-radius: 0 var(--border-radius-xl) var(--border-radius-xl) 0;
            padding: 8px 12px !important;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
        }
        .btn-logout:hover {
            background: #e74c3c;
            transform: translateY(-2px);
        }

        .btn-cart {
            background: var(--color-accent, #D2B48C);
            color: var(--color-primary-dark, #5D3A1A) !important;
            border-radius: var(--border-radius-xl);
            padding: 8px 20px !important;
            font-weight: 600;
            transition: all var(--transition-speed) ease;
            position: relative;
            border: none;
            font-size: 0.9rem;
        }
        .btn-cart:hover {
            background: #fff;
            transform: translateY(-2px);
        }
        .cart-badge {
            background: #ff4444;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 11px;
            margin-left: 5px;
        }

        /* ========== SELECTOR DE TEMA ========== */
        .theme-selector {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .btn-theme {
            background: rgba(255,255,255,0.15);
            color: #fff !important;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: var(--border-radius-xl);
            padding: 6px 10px !important;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all var(--transition-speed) ease;
            cursor: pointer;
            white-space: nowrap;
            text-decoration: none;
        }
        .btn-theme:hover {
            background: var(--color-accent, #D2B48C);
            color: var(--color-primary-dark, #5D3A1A) !important;
            border-color: var(--color-accent, #D2B48C);
        }
        .btn-mode {
            background: rgba(255,255,255,0.1);
            color: #fff !important;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: var(--border-radius-xl);
            padding: 6px 8px !important;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            text-decoration: none;
            width: 32px;
            text-align: center;
        }
        .btn-mode:hover {
            background: var(--color-accent, #D2B48C);
            color: var(--color-primary-dark, #5D3A1A) !important;
        }

        /* ========== HERO ========== */
        .hero {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary, #A0522D) 100%);
            padding: 80px 0;
            margin-bottom: 50px;
        }
        .hero h1 { font-size: 3rem; font-weight: 700; margin-bottom: 20px; }
        .hero p { font-size: 1.2rem; opacity: 0.95; }

        /* ========== PRODUCT CARDS ========== */
        .product-card {
            background: var(--bg-card, #fff);
            border-radius: var(--border-radius-md);
            overflow: hidden;
            transition: all var(--transition-speed) ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        .product-img { height: 220px; background-size: cover; background-position: center; position: relative; }
        .product-badge {
            position: absolute; top: 15px; right: 15px;
            background: var(--color-primary, #8B4513);
            color: #fff;
            padding: 5px 12px;
            border-radius: var(--border-radius-xl);
            font-size: 0.8rem; font-weight: 500;
        }
        .product-body { padding: 20px; }
        .product-title { font-size: 1.3rem; font-weight: 600; color: var(--color-primary-dark, #5D3A1A); margin-bottom: 10px; }
        .product-desc { color: var(--text-muted, #666); font-size: 0.9rem; margin-bottom: 15px; }
        .product-price { font-size: 1.5rem; font-weight: 700; color: var(--color-primary, #8B4513); margin-bottom: 0; }

        /* ========== ANUNCIOS ========== */
        .anuncio {
            background: linear-gradient(135deg, var(--color-accent, #D2B48C) 0%, var(--color-accent-dark, #c9a871) 100%);
            border-radius: var(--border-radius-md);
            padding: 30px; margin-bottom: 30px;
            transition: all var(--transition-speed) ease;
        }
        .anuncio:hover { transform: scale(1.02); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .anuncio h3 { color: var(--color-primary-dark, #5D3A1A); font-weight: 700; margin-bottom: 15px; }
        .anuncio p { color: var(--color-primary-dark, #3E2510); margin-bottom: 0; }
        .anuncio i { font-size: 2.5rem; color: var(--color-primary-dark, #5D3A1A); margin-bottom: 15px; }

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
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        /* ========== FOOTER ========== */
        .footer {
            background: linear-gradient(135deg, var(--color-primary-dark, #5D3A1A) 0%, #3E2510 100%);
            color: #fff;
            padding: 50px 0 20px; margin-top: 50px;
        }
        .footer h5 { color: var(--color-accent, #D2B48C); margin-bottom: 20px; }
        .footer a { color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: color var(--transition-speed) ease; }
        .footer a:hover { color: var(--color-accent, #D2B48C); }
        .social-icons i { font-size: 1.5rem; margin-right: 15px; transition: all var(--transition-speed) ease; }
        .social-icons i:hover { transform: translateY(-3px); color: var(--color-accent, #D2B48C) !important; }

        /* ========== WHATSAPP ========== */
        .whatsapp-float {
            position: fixed; bottom: 30px; right: 30px;
            background: var(--color-whatsapp); color: #fff;
            width: 60px; height: 60px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all var(--transition-speed) ease;
            z-index: 1000;
        }
        .whatsapp-float:hover { transform: scale(1.1); }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2rem; }
            .hero { padding: 50px 0; }
            .product-img { height: 180px; }
            .user-menu-wrapper { flex-wrap: wrap; }
            .theme-selector { margin-top: 0.5rem; }
        }
    </style>
</head>
<body data-theme="<?php echo e($theme); ?>" data-mode="<?php echo e($mode); ?>">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-panaderia sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
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

                    
                    <li class="nav-item ms-2">
                        <div class="theme-selector">
                            <a href="#" class="btn-theme" onclick="cambiarTema('ninos')" title="Tema Niños">
                                <i class="fas fa-child"></i>
                            </a>
                            <a href="#" class="btn-theme" onclick="cambiarTema('jovenes')" title="Tema Jóvenes">
                                <i class="fas fa-user"></i>
                            </a>
                            <a href="#" class="btn-theme" onclick="cambiarTema('adultos')" title="Tema Adultos">
                                <i class="fas fa-user-tie"></i>
                            </a>
                        </div>
                    </li>

                    <li class="nav-item ms-2">
                        <button class="btn-cart" onclick="verCarrito()">
                            <i class="fas fa-shopping-cart"></i> <span id="cartCount" class="cart-badge">0</span>
                        </button>
                    </li>

                    <?php if(auth()->guard()->check()): ?>
                        <li class="nav-item ms-2">
                            <div class="user-menu-wrapper">
                                <span class="btn-user-info">
                                    <i class="fas fa-user-circle"></i>
                                    <?php echo e(Auth::user()->name ?? explode('@', Auth::user()->correo)[0]); ?>

                                </span>
                                <?php if(Auth::user()->tipo_usuario === 'empleado' || (method_exists(Auth::user(), 'esAdmin') && Auth::user()->esAdmin())): ?>
                                <a href="<?php echo e(url('/home')); ?>" class="btn-sistema" title="Ir al sistema de gestión">
                                    <i class="fas fa-desktop"></i>
                                </a>
                                <?php endif; ?>
                                <form action="<?php echo e(route('logout')); ?>" method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-logout" title="Cerrar sesión">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-2">
                            <a class="nav-link btn-login" href="<?php echo e(route('login')); ?>">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    
    
    
    <section class="hero text-white text-center">
        <div class="container">
            <h1>Pan Artesanal Hecho con Amor</h1>
            <p class="lead">Descubre nuestra selección de productos frescos, horneados diariamente con ingredientes de la mejor calidad</p>
            <a href="#productos" class="btn btn-light btn-lg mt-3" style="border-radius: var(--border-radius-xl); color: var(--color-primary, #8B4513); font-weight: 600;">
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
            <?php $__empty_1 = true; $__currentLoopData = $productosConStock ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-lg-3 col-md-6">
                    <div class="product-card">
                        <div class="product-img" style="background-image: url('<?php echo e($producto->imagen ? $producto->imagen : 'https://placehold.co/300x220/8B4513/white?text=Pan+Otto'); ?>');">
                            <span class="product-badge"><?php echo e($producto->categoria ?? 'Producto'); ?></span>
                        </div>
                        <div class="product-body">
                            <h3 class="product-title"><?php echo e($producto->nombre); ?></h3>
                            <p class="product-desc"><?php echo e(Str::limit($producto->descripcion ?? 'Delicioso producto artesanal', 80)); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="product-price">Bs. <?php echo e(number_format($producto->precio, 2)); ?></div>
                                <button class="btn btn-sm" style="background: var(--color-accent, #D2B48C); color: var(--color-primary-dark, #5D3A1A); border-radius: var(--border-radius-xl);"
                                        onclick='agregarAlCarrito(<?php echo json_encode($producto); ?>)'>
                                    <i class="fas fa-shopping-cart"></i> Agregar
                                </button>
                            </div>
                            <small class="text-muted d-block mt-2">Stock: <?php echo e($producto->stock); ?> unidades</small>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5>No hay productos disponibles</h5>
                    <p class="text-muted">Vuelve pronto para ver nuestras delicias.</p>
                </div>
            <?php endif; ?>
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
                    <p>Pan artesanal hecho con amor y tradición familiar desde hace más de 20 años.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Horario de Atención</h5>
                    <p><i class="fas fa-clock"></i> Lunes a Sábado: 7:00 AM - 9:00 PM</p>
                    <p><i class="fas fa-clock"></i> Domingos: 8:00 AM - 2:00 PM</p>
                    <p><i class="fas fa-phone"></i> Tel: (591) 123-45678</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Ubicación</h5>
                    <p><i class="fas fa-map-marker-alt"></i> Av. Principal #123, Centro</p>
                    <p><i class="fas fa-envelope"></i> contacto@panaderiaotto.com</p>
                    <p><i class="fas fa-globe"></i> www.panaderiaotto.com</p>
                </div>
            </div>
            <div class="text-center pt-4 mt-3 border-top border-secondary">
                <p class="mb-0">&copy; <?php echo e(date('Y')); ?> Panadería Otto. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Botón flotante de WhatsApp -->
    <a href="https://wa.me/59112345678" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>
    </div>

    
    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Detección automática de modo día/noche
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

        // Cambio de tema y modo
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
                window.location.href = '<?php echo e(route("procesar.pedido")); ?>';
            });
        });

        function agregarAlCarrito(producto) {
            const cantidad = prompt('¿Cuántas unidades deseas?', 1);
            if (!cantidad || isNaN(cantidad) || cantidad <= 0) {
                toastr.warning('Por favor ingresa una cantidad válida');
                return;
            }
            if (cantidad > producto.stock) {
                toastr.error(`Stock insuficiente. Solo hay ${producto.stock} unidades disponibles`);
                return;
            }
            $.ajax({
                url: '<?php echo e(route("carrito.agregar")); ?>',
                method: 'POST',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>',
                    id_almacen: producto.id_almacen,
                    id_item: producto.id_item,
                    nombre: producto.nombre,
                    precio: producto.precio,
                    cantidad: parseInt(cantidad),
                    almacen_nombre: producto.almacen_nombre,
                    imagen: producto.imagen
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

        function verCarrito() {
            $.ajax({
                url: '<?php echo e(route("carrito.ver")); ?>',
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
                url: '<?php echo e(route("carrito.actualizar")); ?>',
                method: 'POST',
                data: { _token: '<?php echo e(csrf_token()); ?>', key: key, cantidad: nuevaCantidad },
                success: function(response) {
                    if (response.success) { toastr.success(response.message); verCarrito(); actualizarContadorCarrito(); }
                },
                error: function() { toastr.error('Error al actualizar cantidad'); }
            });
        }

        function eliminarProducto(key) {
            if (!confirm('¿Eliminar este producto del carrito?')) return;
            $.ajax({
                url: '<?php echo e(route("carrito.eliminar")); ?>',
                method: 'POST',
                data: { _token: '<?php echo e(csrf_token()); ?>', key: key },
                success: function(response) {
                    if (response.success) { toastr.success(response.message); verCarrito(); actualizarContadorCarrito(); }
                },
                error: function() { toastr.error('Error al eliminar producto'); }
            });
        }

        function actualizarContadorCarrito() {
            $.ajax({
                url: '<?php echo e(route("carrito.count")); ?>',
                method: 'GET',
                success: function(response) { $('#cartCount').text(response.count); }
            });
        }
    </script>
</body>
</html><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/PanaderiaOtto.blade.php ENDPATH**/ ?>