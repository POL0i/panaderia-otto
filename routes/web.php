<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProduccionModuleController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\DetalleRecetaController;
use App\Http\Controllers\InsumoController;

Route::get('/', function () {
    return view('PanaderiaOtto');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
  
        // MÓDULO: USUARIOS (Solo Admin puede gestionar usuarios y permisos)
        
            Route::get('usuarios-acceso/crear', [App\Http\Controllers\UsuarioController::class, 'createAccess'])
            ->name('usuarios.create-access');
            Route::post('usuarios-acceso/guardar', [App\Http\Controllers\UsuarioController::class, 'storeAccess'])
                ->name('usuarios.store-access');
    
            // API endpoints para gestión AJAX
            Route::get('usuarios/{id}/permisos', [App\Http\Controllers\UsuarioController::class, 'getPermisosUsuario'])
                ->name('usuarios.permisos');
            Route::post('usuarios/{id}/actualizar-permisos', [App\Http\Controllers\UsuarioController::class, 'updatePermisos'])
                ->name('usuarios.update-permisos');
            Route::post('empleados/store-ajax', [App\Http\Controllers\UsuarioController::class, 'storeEmpleado'])
                ->name('empleados.store-ajax');
            Route::post('roles/store-ajax', [App\Http\Controllers\UsuarioController::class, 'storeRol'])
                ->name('roles.store-ajax');
            Route::post('permisos/store-ajax', [App\Http\Controllers\UsuarioController::class, 'storePermiso'])
                ->name('permisos.store-ajax');
            Route::post('rol-permisos/store-ajax', [App\Http\Controllers\UsuarioController::class, 'storeRolPermiso'])
                ->name('rol-permisos.store-ajax');
                    
            Route::resource('roles', App\Http\Controllers\RolController::class);
            Route::resource('permisos', App\Http\Controllers\PermisoController::class);
            Route::resource('rol-permisos', App\Http\Controllers\RolPermisoController::class);
            Route::resource('rol-permiso-usuarios', App\Http\Controllers\RolPermisoUsuarioController::class);
            Route::resource('empleados', App\Http\Controllers\EmpleadoController::class);
        
// Rutas protegidas con autenticación
Route::middleware(['auth'])->group(function () {
      

    // Clientes - Cualquier usuario autenticado puede ver
    Route::resource('clientes', App\Http\Controllers\ClienteController::class);

    // =====================================================
    // MÓDULO: GESTIÓN COMERCIAL
    // =====================================================
    Route::middleware(['permiso:gestion_comercial_ver'])->group(function () {
        Route::resource('notas-venta', App\Http\Controllers\NotaVentaController::class);
        Route::resource('detalles-venta', App\Http\Controllers\DetalleVentaController::class);
        Route::resource('notas-compra', App\Http\Controllers\NotaCompraController::class);
        Route::resource('detalles-compra', App\Http\Controllers\DetalleCompraController::class);
        Route::resource('proveedores', App\Http\Controllers\ProveedorController::class);
        Route::resource('ppersona', App\Http\Controllers\PPesonaController::class);
        Route::resource('pempresa', App\Http\Controllers\PempresaController::class);
    });

    // =====================================================
    // MÓDULO: ALMACÉN
    // =====================================================
    Route::middleware(['permiso:almacen_ver'])->group(function () {
        Route::resource('almacenes', App\Http\Controllers\AlmacenController::class);
        Route::resource('productos', App\Http\Controllers\ProductoController::class);
        Route::resource('items', App\Http\Controllers\ItemController::class);
        Route::resource('insumos', App\Http\Controllers\InsumoController::class);
        Route::resource('almacen-items', App\Http\Controllers\AlmacenItemController::class);
    });

    // =====================================================
    // MÓDULO: INVENTARIO
    // =====================================================
    Route::middleware(['permiso:inventario_ver'])->group(function () {
        Route::resource('movimientos', App\Http\Controllers\MovimientoInventarioController::class);
        Route::post('movimientos/filtrar', [App\Http\Controllers\MovimientoInventarioController::class, 'filtrar'])->name('movimientos.filtrar');
        
        Route::resource('traspasos', App\Http\Controllers\TraspasoInventarioController::class);
        Route::put('traspasos/{traspaso}/completar', [App\Http\Controllers\TraspasoInventarioController::class, 'completar'])->name('traspasos.completar');
        Route::put('traspasos/{traspaso}/cancelar', [App\Http\Controllers\TraspasoInventarioController::class, 'cancelar'])->name('traspasos.cancelar');
        
        Route::resource('lotes', App\Http\Controllers\LoteInventarioController::class);
        Route::post('lotes/filtrar', [App\Http\Controllers\LoteInventarioController::class, 'filtrar'])->name('lotes.filtrar');
        Route::post('lotes/{lote}/consumir', [App\Http\Controllers\LoteInventarioController::class, 'consumir'])->name('lotes.consumir');
        
        Route::get('configuracion-inventario/editar', [App\Http\Controllers\ConfiguracionInventarioController::class, 'edit'])->name('configuracion.edit');
        Route::put('configuracion-inventario/actualizar', [App\Http\Controllers\ConfiguracionInventarioController::class, 'update'])->name('configuracion.update');
    });

    // =====================================================
    // MÓDULO: PRODUCCIÓN
    // =====================================================
    Route::middleware(['permiso:produccion_ver'])->group(function () {
        Route::resource('recetas', App\Http\Controllers\RecetaController::class);
        Route::resource('detalles-receta', App\Http\Controllers\DetalleRecetaController::class);
        Route::get('detalles-receta/por-receta/{id_receta}', [App\Http\Controllers\DetalleRecetaController::class, 'porReceta'])->name('detalles-receta.por-receta');
        
        Route::resource('producciones', App\Http\Controllers\ProduccionController::class);
        Route::post('producciones/filtrar', [App\Http\Controllers\ProduccionController::class, 'filtrar'])->name('producciones.filtrar');
        
        Route::resource('produccion-items', App\Http\Controllers\ProduccionItemAlmacenController::class);
        Route::get('produccion-items/por-produccion/{id_produccion}', [App\Http\Controllers\ProduccionItemAlmacenController::class, 'porProduccion'])->name('produccion-items.por-produccion');
    });
    
    Route::middleware(['auth'])->group(function () {
        // Panel principal
        Route::get('/produccion', [ProduccionModuleController::class, 'index'])
            ->name('produccion.index');
        
        // Endpoints AJAX - ¡Asegúrate que estos nombres coincidan con los usados en el formulario!
        Route::post('/produccion/categorias', [ProduccionModuleController::class, 'storeCategoria'])
            ->name('produccion.categorias.store');
        
        Route::post('/produccion/insumos', [ProduccionModuleController::class, 'storeInsumo'])
            ->name('produccion.insumos.store');
        
        Route::post('/produccion/recetas', [ProduccionModuleController::class, 'storeReceta'])
            ->name('produccion.recetas.store');
        
        // Detalles de receta
        Route::get('/produccion/recetas/{receta}/detalles', [ProduccionModuleController::class, 'detallesReceta'])
            ->name('produccion.recetas.detalles');
        
        Route::post('/produccion/recetas/{receta}/detalles', [ProduccionModuleController::class, 'storeDetallesReceta'])
            ->name('produccion.recetas.detalles.store');
    });
});