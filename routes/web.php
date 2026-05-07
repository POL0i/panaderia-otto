<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\RolPermisoController;
use App\Http\Controllers\RolPermisoUsuarioController;

// Módulo Producción
use App\Http\Controllers\ProduccionModuleController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\DetalleRecetaController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\ProduccionItemAlmacenController;

// Módulo Almacén
use App\Http\Controllers\AlmacenModuleController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\AlmacenItemController;

// Módulo Inventario
use App\Http\Controllers\MovimientoInventarioController;
use App\Http\Controllers\TraspasoInventarioController;
use App\Http\Controllers\LoteInventarioController;
use App\Http\Controllers\ConfiguracionInventarioController;

// Módulo Gestión Comercial
use App\Http\Controllers\NotaVentaController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\NotaCompraController;
use App\Http\Controllers\DetalleCompraController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\PPesonaController;
use App\Http\Controllers\PempresaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\VentaController;

// Landing pública
Route::get('/', [VentaController::class, 'landingPage'])->name('landing');

// Carrito de compras (público)
Route::prefix('carrito')->name('carrito.')->group(function () {
    Route::post('/agregar', [VentaController::class, 'agregarAlCarrito'])->name('agregar');
    Route::post('/actualizar', [VentaController::class, 'actualizarCarrito'])->name('actualizar');
    Route::post('/eliminar', [VentaController::class, 'eliminarDelCarrito'])->name('eliminar');
    Route::get('/', [VentaController::class, 'verCarrito'])->name('ver');
    Route::get('/count', [VentaController::class, 'carritoCount'])->name('count');
});

// Procesar pedido
Route::get('/procesar-pedido', [VentaController::class, 'procesarPedido'])->name('procesar.pedido');

// Webhook de pago
Route::post('/webhook/libelula/pago-exitoso', [VentaController::class, 'webhookPagoExitoso'])
    ->name('webhook.libelula');

// Registro rápido de clientes
Route::post('/registro/cliente/rapido', [UsuarioController::class, 'registroClienteRapido'])
    ->name('registro.cliente.rapido');

// Pago verificación
Route::get('/pago/verificar/{id}', [VentaController::class, 'verificarPago'])->name('pago.verificar');
Route::get('/pago/exito/{id}', [VentaController::class, 'pagoExito'])->name('pago.exito');


// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {

    // Sección de Ventas
    Route::prefix('ventas')->name('ventas.')->group(function () {
        Route::get('/', [VentaController::class, 'index'])->name('index');
        Route::post('/store', [VentaController::class, 'store'])->name('store');
        Route::get('/clientes', [VentaController::class, 'getClientes'])->name('clientes');
        Route::get('/almacenes', [VentaController::class, 'getAlmacenes'])->name('almacenes');
        Route::get('/items', [VentaController::class, 'getItems'])->name('items');
        Route::get('/stock/{idAlmacen}/{idItem}', [VentaController::class, 'getStock'])->name('stock');
        Route::get('/nota/{id}', [VentaController::class, 'getNotaVenta'])->name('nota');
        Route::post('/enviar-correo', [VentaController::class, 'enviarCorreo'])->name('enviar-correo');
        Route::get('/productos-con-stock', [VentaController::class, 'getProductosConStock'])->name('getProductosConStock');
        Route::get('/debug', [VentaController::class, 'debugProductos'])->name('debug');

        // RUTA PARA COMPLETAR VENTA (DEBE ESTAR DENTRO DEL GRUPO)
        Route::post('/{id}/completar', [VentaController::class, 'completarVenta'])->name('completar');
    });

    // Sección de Compras
    Route::prefix('compras')->name('compras.')->group(function () {
        Route::get('/', [CompraController::class, 'index'])->name('index');
        Route::post('/store', [CompraController::class, 'store'])->name('store');
        Route::get('/nota/{id}', [CompraController::class, 'getNotaCompra'])->name('nota');
        Route::get('/detalles', [CompraController::class, 'getDetallesCompra'])->name('detalles');
        Route::get('/items-almacen/{id}', [CompraController::class, 'getItemsByAlmacen'])->name('items.almacen');
        Route::post('/proveedor', [CompraController::class, 'storeProveedor'])->name('proveedor.store');
        Route::post('/almacen', [CompraController::class, 'storeAlmacen'])->name('almacen.store');
        Route::post('/insumo', [CompraController::class, 'storeInsumo'])->name('insumo.store');
        Route::get('/proveedores', [CompraController::class, 'getProveedores'])->name('proveedores');
        Route::get('/almacenes', [CompraController::class, 'getAlmacenes'])->name('almacenes');
        Route::get('/items', [CompraController::class, 'getItems'])->name('items');
        Route::post('/enviar-correo', [CompraController::class, 'enviarCorreoCompra'])->name('enviar-correo');
    });

});
/*
|--------------------------------------------------------------------------
| Rutas Públicas
|--------------------------------------------------------------------------
*/

Auth::routes();

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (requieren autenticación)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    /*
    |--------------------------------------------------------------------------
    | MÓDULO: CLIENTES (Todos los autenticados)
    |--------------------------------------------------------------------------
    */
    Route::resource('clientes', ClienteController::class);

    /*
    |--------------------------------------------------------------------------
    | MÓDULO: USUARIOS Y SEGURIDAD (Solo Admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->group(function () {
        // Módulo de Acceso (Principal)
        Route::get('usuarios-acceso/crear', [UsuarioController::class, 'createAccess'])
            ->name('usuarios.create-access');
        Route::post('usuarios-acceso/guardar', [UsuarioController::class, 'storeAccess'])
            ->name('usuarios.store-access');

        // CRUD Usuarios
        Route::get('usuarios/{id}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');

        // API Permisos
        Route::get('usuarios/{id}/permisos', [UsuarioController::class, 'getPermisosUsuario'])
            ->name('usuarios.permisos');
        Route::post('usuarios/{id}/actualizar-permisos', [UsuarioController::class, 'updatePermisos'])
            ->name('usuarios.update-permisos');

        // Recursos de seguridad
        Route::resource('empleados', EmpleadoController::class);
        Route::resource('roles', RolController::class);
        Route::resource('permisos', PermisoController::class);
        Route::resource('rol-permisos', RolPermisoController::class);
        Route::resource('rol-permiso-usuarios', RolPermisoUsuarioController::class);

        // Endpoints AJAX para crear desde modales
        Route::post('empleados/store-ajax', [UsuarioController::class, 'storeEmpleado'])
            ->name('empleados.store-ajax');
        Route::post('roles/store-ajax', [UsuarioController::class, 'storeRol'])
            ->name('roles.store-ajax');
        Route::post('permisos/store-ajax', [UsuarioController::class, 'storePermiso'])
            ->name('permisos.store-ajax');
        Route::post('rol-permisos/store-ajax', [UsuarioController::class, 'storeRolPermiso'])
            ->name('rol-permisos.store-ajax');
        Route::post('usuarios/store-empleado', [UsuarioController::class, 'storeEmpleado'])
            ->name('usuarios.store-empleado');
        Route::post('usuarios/store-cliente', [UsuarioController::class, 'storeCliente'])
            ->name('usuarios.store-cliente');
    });

    /*
    |--------------------------------------------------------------------------
    | MÓDULO: GESTIÓN COMERCIAL (permiso: gestion_comercial_ver)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permiso:gestion_comercial_ver'])->group(function () {
        Route::resource('notas-venta', NotaVentaController::class);
        Route::resource('detalles-venta', DetalleVentaController::class);
        Route::resource('notas-compra', NotaCompraController::class);
        Route::resource('detalles-compra', DetalleCompraController::class);
        Route::resource('proveedores', ProveedorController::class);
        Route::resource('ppersona', PPesonaController::class);
        Route::resource('pempresa', PempresaController::class);
    });

   /*
    |--------------------------------------------------------------------------
    | MÓDULO: ALMACÉN (permiso: almacen_ver)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permiso:almacen_ver'])->group(function () {
        // Panel destacado del módulo
        Route::get('/modulo-almacen', [AlmacenModuleController::class, 'index'])
            ->name('modulo-almacen.index')
            ->middleware('permiso:panel_almacen_ver');

        // Endpoints AJAX del panel
        Route::post('/modulo-almacen/almacenes', [AlmacenModuleController::class, 'storeAlmacen'])
            ->name('modulo-almacen.almacenes.store');
        Route::post('/modulo-almacen/categorias-insumo', [AlmacenModuleController::class, 'storeCategoriaInsumo'])
            ->name('modulo-almacen.categorias-insumo.store');
        Route::post('/modulo-almacen/insumos', [AlmacenModuleController::class, 'storeInsumo'])
            ->name('modulo-almacen.insumos.store');
        Route::post('/modulo-almacen/categorias-producto', [AlmacenModuleController::class, 'storeCategoriaProducto'])
            ->name('modulo-almacen.categorias-producto.store');
        Route::post('/modulo-almacen/productos', [AlmacenModuleController::class, 'storeProducto'])
            ->name('modulo-almacen.productos.store');
        Route::post('/modulo-almacen/stock', [AlmacenModuleController::class, 'storeStock'])
            ->name('modulo-almacen.stock.store');
        Route::get('/modulo-almacen/{idAlmacen}/items', [AlmacenModuleController::class, 'getItemsPorAlmacen'])
            ->name('modulo-almacen.items');

        // Recursos tradicionales
        Route::resource('almacenes', AlmacenController::class);
        Route::resource('productos', ProductoController::class);

        // Rutas adicionales para categorías de productos dentro de producto
        Route::prefix('productos')->group(function () {
            Route::post('/categorias', [ProductoController::class, 'storeCategoria'])
                ->name('productos.categorias.store');
            Route::put('/categorias/{id}', [ProductoController::class, 'updateCategoria'])
                ->name('productos.categorias.update');
            Route::delete('/categorias/{id}', [ProductoController::class, 'destroyCategoria'])
                ->name('productos.categorias.destroy');
            Route::get('/categorias/{id}/edit', [ProductoController::class, 'editCategoria'])
                ->name('productos.categorias.edit');
        });

        Route::resource('items', ItemController::class);
        Route::resource('insumos', InsumoController::class);

        // Rutas adicionales para categorías de insumos dentro de insumo
        Route::prefix('insumos')->group(function () {
            Route::post('/categorias', [InsumoController::class, 'storeCategoria'])
                ->name('insumos.categorias.store');
            Route::put('/categorias/{id}', [InsumoController::class, 'updateCategoria'])
                ->name('insumos.categorias.update');
            Route::delete('/categorias/{id}', [InsumoController::class, 'destroyCategoria'])
                ->name('insumos.categorias.destroy');
            Route::get('/categorias/{id}/edit', [InsumoController::class, 'editCategoria'])
                ->name('insumos.categorias.edit');
        });

        Route::resource('almacen-items', AlmacenItemController::class);

        Route::post('/modulo-almacen/search-images', [AlmacenModuleController::class, 'searchImages'])
        ->name('modulo-almacen.search-images');
    });
    /*
    |--------------------------------------------------------------------------
    | MÓDULO: INVENTARIO (permiso: inventario_ver)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permiso:inventario_ver'])->group(function () {
        Route::resource('movimientos', MovimientoInventarioController::class);
        Route::post('movimientos/filtrar', [MovimientoInventarioController::class, 'filtrar'])
            ->name('movimientos.filtrar');

        Route::resource('traspasos', TraspasoInventarioController::class)->except(['edit', 'update']);
        Route::put('traspasos/{traspaso}/completar', [TraspasoInventarioController::class, 'completar'])
            ->name('traspasos.completar');
        Route::put('traspasos/{traspaso}/cancelar', [TraspasoInventarioController::class, 'cancelar'])
            ->name('traspasos.cancelar');

        Route::resource('lotes', LoteInventarioController::class);
        Route::post('lotes/filtrar', [LoteInventarioController::class, 'filtrar'])
            ->name('lotes.filtrar');
        Route::post('lotes/{lote}/consumir', [LoteInventarioController::class, 'consumir'])
            ->name('lotes.consumir');

        Route::get('configuracion-inventario/editar', [ConfiguracionInventarioController::class, 'edit'])
            ->name('configuracion.edit');
        Route::put('configuracion-inventario/actualizar', [ConfiguracionInventarioController::class, 'update'])
            ->name('configuracion.update');

        Route::get('movimientos/{referenciaId}', [MovimientoInventarioController::class, 'show'])
    ->name('movimientos.show');
    });

    /*
    |--------------------------------------------------------------------------
    | MÓDULO: PRODUCCIÓN (permiso: produccion_ver)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permiso:produccion_ver'])->group(function () {

        // =============================================
        // PANEL PRINCIPAL Y GESTIÓN BÁSICA
        // =============================================

        Route::resource('producciones', ProduccionController::class)
    ->except(['edit', 'update', 'destroy'])
    ->parameters(['producciones' => 'produccion']);  // ← Agregar esta línea


        Route::get('/produccion', [ProduccionModuleController::class, 'index'])
            ->name('produccion.index')
            ->middleware('permiso:panel_produccion_ver');

        // Categorías de insumos
        Route::post('/produccion/categorias', [ProduccionModuleController::class, 'storeCategoria'])
            ->name('produccion.categorias.store');

        // Insumos
        Route::post('/produccion/insumos', [ProduccionModuleController::class, 'storeInsumo'])
            ->name('produccion.insumos.store');

        // Recetas (creación desde el panel)
        Route::post('/produccion/recetas', [ProduccionModuleController::class, 'storeReceta'])
            ->name('produccion.recetas.store');

        // Detalles de receta (agregar/ver insumos)
        Route::get('/produccion/recetas/{receta}/detalles', [ProduccionModuleController::class, 'detallesReceta'])
            ->name('produccion.recetas.detalles');
        Route::post('/produccion/recetas/{receta}/detalles', [ProduccionModuleController::class, 'storeDetallesReceta'])
            ->name('produccion.recetas.detalles.store');
        Route::put('/produccion/detalles-receta/{detalle}', [ProduccionModuleController::class, 'updateDetalleReceta'])
            ->name('produccion.detalles-receta.update');
        Route::delete('/produccion/detalles-receta/{detalle}', [ProduccionModuleController::class, 'destroyDetalleReceta'])
            ->name('produccion.detalles-receta.destroy');

        // =============================================
        // RECURSOS TRADICIONALES
        // =============================================
        Route::resource('recetas', RecetaController::class);
        Route::resource('detalles-receta', DetalleRecetaController::class);
        Route::get('detalles-receta/por-receta/{id_receta}', [DetalleRecetaController::class, 'porReceta'])
            ->name('detalles-receta.por-receta');

        // =============================================
        // PRODUCCIONES
        // =============================================

        // Cálculo de insumos (AJAX - debe ir ANTES del resource)
 Route::post('producciones/calcular-insumos', [ProduccionController::class, 'calcularInsumos'])
    ->name('producciones.calcular-insumos');

Route::resource('producciones', ProduccionController::class)
    ->except(['edit', 'update', 'destroy'])
    ->parameters(['producciones' => 'produccion']);  // ← CORRECCIÓN

Route::post('producciones/{produccion}/aprobar', [ProduccionController::class, 'aprobar'])
    ->name('producciones.aprobar');
Route::post('producciones/{produccion}/rechazar', [ProduccionController::class, 'rechazar'])
    ->name('producciones.rechazar');
Route::post('producciones/{produccion}/cancelar', [ProduccionController::class, 'cancelar'])
    ->name('producciones.cancelar');

    });
});
