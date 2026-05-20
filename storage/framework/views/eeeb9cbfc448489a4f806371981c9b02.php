<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Buscar: <?php echo e($query); ?> - Panadería Otto</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <?php
        $theme = session('theme', 'jovenes');
        $mode  = session('mode', 'auto');
    ?>
    <link rel="stylesheet" href="<?php echo e(asset("css/themes/{$theme}/light.css")); ?>">
    <link rel="stylesheet" href="<?php echo e(asset("css/themes/{$theme}/dark.css")); ?>">

    <style>
        :root {
            --color-primary: #8B4513;
            --color-primary-dark: #5D3A1A;
            --color-accent: #D2B48C;
            --color-bg-light: #FFF9F0;
            --border-radius-md: 20px;
            --border-radius-xl: 50px;
            --transition-speed: 0.3s;
        }
        * { font-family: 'Poppins', sans-serif; }
        body { background-color: var(--color-bg-light, #FFF9F0); }
        .navbar-panaderia {
            background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
            padding: 0.5rem 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .navbar-brand { color: #fff !important; font-weight: 700; }
        .navbar-brand i { color: var(--color-accent); }
        .product-card {
            background: var(--bg-card, #fff);
            border-radius: var(--border-radius-md);
            overflow: hidden;
            transition: all var(--transition-speed) ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.15); }
        .product-img { height: 200px; background-size: cover; background-position: center; position: relative; }
        .product-badge {
            position: absolute; top: 15px; right: 15px;
            background: var(--color-primary, #8B4513);
            color: #fff; padding: 5px 12px; border-radius: 50px; font-size: 0.8rem;
        }
        .product-body { padding: 20px; }
        .product-title { font-size: 1.2rem; font-weight: 600; color: var(--color-primary-dark, #5D3A1A); }
        .product-price { font-size: 1.3rem; font-weight: 700; color: var(--color-primary, #8B4513); }
        .btn-agregar {
            background: var(--color-accent, #D2B48C);
            color: var(--color-primary-dark, #5D3A1A);
            border-radius: 50px;
            border: none;
            padding: 5px 15px;
            font-size: 0.85rem;
            transition: all var(--transition-speed) ease;
        }
        .btn-agregar:hover { background: #fff; transform: translateY(-2px); }
    </style>
</head>
<body data-theme="<?php echo e($theme); ?>" data-mode="<?php echo e($mode); ?>">
    <script>
        (function() {
            const body = document.body;
            let mode = body.getAttribute('data-mode');
            if (mode === 'auto') {
                const hour = new Date().getHours();
                body.setAttribute('data-mode', hour >= 6 && hour < 18 ? 'light' : 'dark');
            }
        })();
    </script>

    <nav class="navbar navbar-expand-lg navbar-panaderia sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>"><i class="fas fa-bread-slice"></i> Panadería Otto</a>
            <form action="<?php echo e(route('buscar')); ?>" method="GET" class="d-flex ms-auto me-2">
                <div class="input-group input-group-sm">
                    <input type="text" name="q" class="form-control" placeholder="Buscar productos..." value="<?php echo e($query); ?>">
                    <button type="submit" class="btn btn-outline-light"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </nav>

    <div class="container mt-4">
        <h4 class="mb-3">Resultados para "<?php echo e($query); ?>" (<?php echo e($productos->count()); ?>)</h4>
        <?php if($productos->isEmpty()): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-search fa-3x mb-3"></i>
                <p>No se encontraron productos con "<?php echo e($query); ?>".</p>
                <a href="<?php echo e(url('/')); ?>" class="btn btn-outline-primary">Volver a la tienda</a>
            </div>
        <?php else: ?>
            <div class="row">
                <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4 col-lg-3">
                    <div class="product-card">
                        <div class="product-img" style="background-image: url('<?php echo e($producto->imagen ?: 'https://placehold.co/300x200/8B4513/white?text=Pan+Otto'); ?>');">
                            <span class="product-badge"><?php echo e($producto->categoria ?? 'Producto'); ?></span>
                        </div>
                        <div class="product-body">
                            <h5 class="product-title"><?php echo e($producto->nombre); ?></h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="product-price">Bs. <?php echo e(number_format($producto->precio, 2)); ?></span>
                                <button class="btn btn-agregar" onclick='agregarAlCarrito(<?php echo json_encode($producto); ?>)'>
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                            <small class="text-muted d-block mt-2">Stock: <?php echo e($producto->stock); ?> u.</small>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="text-center mt-3">
                <a href="<?php echo e(url('/')); ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Volver a la tienda</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = {"positionClass": "toast-bottom-right", "closeButton": true, "timeOut": "3000"};
        function agregarAlCarrito(producto) {
            const cantidad = prompt('¿Cuántas unidades deseas?', 1);
            if (!cantidad || isNaN(cantidad) || cantidad <= 0) return;
            if (cantidad > producto.stock) { toastr.error('Stock insuficiente'); return; }
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
                success: function(r) { if (r.success) toastr.success(r.message); },
                error: function() { toastr.error('Error al agregar'); }
            });
        }
    </script>
</body>
</html><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/buscar.blade.php ENDPATH**/ ?>