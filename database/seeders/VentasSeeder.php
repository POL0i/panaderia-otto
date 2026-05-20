<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VentasSeeder extends Seeder
{
    public function run(): void
    {
        // Deshabilitar foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Limpiar tablas de ventas
        DB::table('detalles_venta')->delete();
        DB::table('notas_venta')->delete();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info("\n" . str_repeat("=", 70));
        $this->command->info("💰 INICIANDO VENTAS MASIVAS");
        $this->command->info(str_repeat("=", 70));

        // ==========================================
        // 1. OBTENER REFERENCIAS
        // ==========================================
        
        // Empleados (vendedores)
        $vendedor1 = DB::table('empleados')->where('nombre', 'Lizeth')->where('apellido', 'García')->first();
        if (!$vendedor1) $vendedor1 = DB::table('empleados')->first();
        
        $vendedor2 = DB::table('empleados')->where('nombre', 'Juan')->where('apellido', 'Pérez')->first();
        if (!$vendedor2) $vendedor2 = DB::table('empleados')->skip(1)->first();
        
        $vendedorAdmin = DB::table('empleados')->where('nombre', 'Carlos')->where('apellido', 'Mendoza')->first();
        if (!$vendedorAdmin) $vendedorAdmin = $vendedor1;

        // Clientes
        $clientes = DB::table('clientes')->get();
        if ($clientes->isEmpty()) {
            $this->command->error("No hay clientes registrados. Ejecuta primero RBACSeeder.");
            return;
        }

        // Almacén de productos
        $almacenProductos = DB::table('almacenes')->where('tipo_almacen', 'producto')->first();
        if (!$almacenProductos) $almacenProductos = DB::table('almacenes')->first();

        // Obtener productos con su stock actual
        $productos = DB::table('items')
            ->join('productos', 'items.id_item', '=', 'productos.id_item')
            ->join('almacen_item', function($join) use ($almacenProductos) {
                $join->on('items.id_item', '=', 'almacen_item.id_item')
                     ->where('almacen_item.id_almacen', '=', $almacenProductos->id_almacen);
            })
            ->select('items.id_item', 'items.nombre', 'items.unidad_medida', 'productos.precio', 'almacen_item.stock')
            ->where('almacen_item.stock', '>', 0)
            ->get();

        if ($productos->isEmpty()) {
            $this->command->error("No hay productos con stock. Ejecuta primero ProduccionSeeder.");
            return;
        }

        $this->command->info("\n📦 Productos disponibles para venta:");
        foreach ($productos as $p) {
            $this->command->info("  - {$p->nombre}: {$p->stock} {$p->unidad_medida} (Precio: \${$p->precio})");
        }

        // ==========================================
        // 2. CONFIGURAR VENTAS
        // ==========================================
        
        $ventas = [
            // Venta 1 - Cliente regular, enero
            [
                'fecha' => Carbon::create(2026, 1, 18, 10, 30, 0),
                'cliente' => $clientes[0],
                'empleado' => $vendedor1,
                'estado' => 'completado',
                'metodo_pago' => 'efectivo',
                'detalles' => [
                    ['producto' => 'Pan de Muerto', 'cantidad' => 10],
                    ['producto' => 'Concha', 'cantidad' => 20],
                    ['producto' => 'Bolillo', 'cantidad' => 30],
                ],
            ],
            // Venta 2 - Cliente frecuente, enero
            [
                'fecha' => Carbon::create(2026, 1, 25, 15, 45, 0),
                'cliente' => $clientes[1],
                'empleado' => $vendedor2,
                'estado' => 'completado',
                'metodo_pago' => 'tarjeta',
                'detalles' => [
                    ['producto' => 'Pastel de Chocolate', 'cantidad' => 2],
                    ['producto' => 'Galleta María', 'cantidad' => 50],
                    ['producto' => 'Concha', 'cantidad' => 15],
                ],
            ],
            // Venta 3 - Febrero
            [
                'fecha' => Carbon::create(2026, 2, 14, 9, 15, 0),
                'cliente' => $clientes[2],
                'empleado' => $vendedor1,
                'estado' => 'completado',
                'metodo_pago' => 'efectivo',
                'detalles' => [
                    ['producto' => 'Pan de Muerto', 'cantidad' => 5],
                    ['producto' => 'Bolillo', 'cantidad' => 40],
                    ['producto' => 'Galleta María', 'cantidad' => 30],
                ],
            ],
            // Venta 4 - Marzo (pendiente)
            [
                'fecha' => Carbon::create(2026, 3, 8, 11, 0, 0),
                'cliente' => $clientes[0],
                'empleado' => $vendedor2,
                'estado' => 'pendiente',
                'metodo_pago' => 'transferencia',
                'detalles' => [
                    ['producto' => 'Concha', 'cantidad' => 25],
                    ['producto' => 'Pastel de Chocolate', 'cantidad' => 1],
                ],
            ],
            // Venta 5 - Abril
            [
                'fecha' => Carbon::create(2026, 4, 12, 14, 20, 0),
                'cliente' => $clientes[1],
                'empleado' => $vendedorAdmin,
                'estado' => 'completado',
                'metodo_pago' => 'tarjeta',
                'detalles' => [
                    ['producto' => 'Bolillo', 'cantidad' => 100],
                    ['producto' => 'Concha', 'cantidad' => 50],
                    ['producto' => 'Pan de Muerto', 'cantidad' => 20],
                    ['producto' => 'Galleta María', 'cantidad' => 80],
                ],
            ],
            // Venta 6 - Mayo (gran venta)
            [
                'fecha' => Carbon::create(2026, 5, 20, 16, 30, 0),
                'cliente' => $clientes[2],
                'empleado' => $vendedor1,
                'estado' => 'completado',
                'metodo_pago' => 'efectivo',
                'detalles' => [
                    ['producto' => 'Pastel de Chocolate', 'cantidad' => 5],
                    ['producto' => 'Galleta María', 'cantidad' => 200],
                    ['producto' => 'Concha', 'cantidad' => 100],
                    ['producto' => 'Bolillo', 'cantidad' => 150],
                ],
            ],
            // Venta 7 - Junio
            [
                'fecha' => Carbon::create(2026, 6, 5, 8, 45, 0),
                'cliente' => $clientes[0],
                'empleado' => $vendedor2,
                'estado' => 'completado',
                'metodo_pago' => 'tarjeta',
                'detalles' => [
                    ['producto' => 'Pan de Muerto', 'cantidad' => 8],
                    ['producto' => 'Bolillo', 'cantidad' => 60],
                ],
            ],
            // Venta 8 - Julio
            [
                'fecha' => Carbon::create(2026, 7, 19, 12, 0, 0),
                'cliente' => $clientes[1],
                'empleado' => $vendedor1,
                'estado' => 'completado',
                'metodo_pago' => 'efectivo',
                'detalles' => [
                    ['producto' => 'Pastel de Chocolate', 'cantidad' => 3],
                    ['producto' => 'Galleta María', 'cantidad' => 120],
                    ['producto' => 'Concha', 'cantidad' => 40],
                ],
            ],
            // Venta 9 - Agosto (cancelada)
            [
                'fecha' => Carbon::create(2026, 8, 22, 17, 15, 0),
                'cliente' => $clientes[2],
                'empleado' => $vendedorAdmin,
                'estado' => 'cancelado',
                'metodo_pago' => null,
                'detalles' => [
                    ['producto' => 'Pan de Muerto', 'cantidad' => 15],
                    ['producto' => 'Pastel de Chocolate', 'cantidad' => 2],
                ],
            ],
            // Venta 10 - Septiembre
            [
                'fecha' => Carbon::create(2026, 9, 10, 10, 30, 0),
                'cliente' => $clientes[0],
                'empleado' => $vendedor2,
                'estado' => 'completado',
                'metodo_pago' => 'transferencia',
                'detalles' => [
                    ['producto' => 'Bolillo', 'cantidad' => 200],
                    ['producto' => 'Concha', 'cantidad' => 80],
                ],
            ],
            // Venta 11 - Octubre
            [
                'fecha' => Carbon::create(2026, 10, 15, 9, 0, 0),
                'cliente' => $clientes[1],
                'empleado' => $vendedor1,
                'estado' => 'completado',
                'metodo_pago' => 'tarjeta',
                'detalles' => [
                    ['producto' => 'Galleta María', 'cantidad' => 300],
                    ['producto' => 'Pan de Muerto', 'cantidad' => 25],
                ],
            ],
            // Venta 12 - Noviembre
            [
                'fecha' => Carbon::create(2026, 11, 25, 14, 0, 0),
                'cliente' => $clientes[2],
                'empleado' => $vendedorAdmin,
                'estado' => 'completado',
                'metodo_pago' => 'efectivo',
                'detalles' => [
                    ['producto' => 'Pastel de Chocolate', 'cantidad' => 8],
                    ['producto' => 'Concha', 'cantidad' => 150],
                    ['producto' => 'Bolillo', 'cantidad' => 250],
                ],
            ],
            // Venta 13 - Diciembre (venta navideña)
            [
                'fecha' => Carbon::create(2026, 12, 20, 11, 30, 0),
                'cliente' => $clientes[0],
                'empleado' => $vendedor1,
                'estado' => 'completado',
                'metodo_pago' => 'tarjeta',
                'detalles' => [
                    ['producto' => 'Pastel de Chocolate', 'cantidad' => 10],
                    ['producto' => 'Galleta María', 'cantidad' => 500],
                    ['producto' => 'Pan de Muerto', 'cantidad' => 50],
                    ['producto' => 'Concha', 'cantidad' => 200],
                    ['producto' => 'Bolillo', 'cantidad' => 300],
                ],
            ],
        ];

        // ==========================================
        // 3. EJECUTAR CADA VENTA
        // ==========================================
        
        $totalVentas = 0;
        $totalCompletadas = 0;
        $totalIngresos = 0;
        $totalProductosVendidos = 0;

        foreach ($ventas as $index => $ventaData) {
            $this->command->info("\n" . str_repeat("-", 70));
            $this->command->info("💰 Venta " . ($index + 1) . " de " . count($ventas));
            
            $resultado = $this->procesarVenta(
                $ventaData,
                $productos,
                $almacenProductos
            );
            
            if ($resultado['estado'] === 'completado') {
                $totalCompletadas++;
                $totalIngresos += $resultado['monto'];
                $totalProductosVendidos += $resultado['cantidad'];
            }
            $totalVentas++;
        }

        // ==========================================
        // 4. REPORTE FINAL
        // ==========================================
        $this->command->info("\n" . str_repeat("=", 70));
        $this->command->info("📊 REPORTE FINAL DE VENTAS");
        $this->command->info(str_repeat("=", 70));
        
        $this->command->info("\n📋 RESUMEN:");
        $this->command->info("   - Total ventas procesadas: {$totalVentas}");
        $this->command->info("   - Ventas completadas: {$totalCompletadas}");
        $this->command->info("   - Ventas pendientes/canceladas: " . ($totalVentas - $totalCompletadas));
        $this->command->info("   - Total productos vendidos: " . number_format($totalProductosVendidos, 0));
        $this->command->info("   - Ingreso total: \$" . number_format($totalIngresos, 2));
        
        // Mostrar stock actual de productos
        $this->command->info("\n📦 STOCK ACTUAL DE PRODUCTOS:");
        $stocksActuales = DB::table('almacen_item')
            ->join('items', 'almacen_item.id_item', '=', 'items.id_item')
            ->where('items.tipo_item', 'producto')
            ->where('almacen_item.id_almacen', $almacenProductos->id_almacen)
            ->select('items.nombre', 'almacen_item.stock', 'items.unidad_medida')
            ->orderBy('items.nombre')
            ->get();
        
        foreach ($stocksActuales as $stock) {
            $this->command->info("   - {$stock->nombre}: " . number_format($stock->stock, 0) . " {$stock->unidad_medida}");
        }
        
        // Mostrar lotes activos después de ventas
        $this->command->info("\n📦 LOTES ACTIVOS (PEPS):");
        $lotesActivos = DB::table('lotes_inventario')
            ->join('items', 'lotes_inventario.id_item', '=', 'items.id_item')
            ->where('items.tipo_item', 'producto')
            ->where('lotes_inventario.estado', 'disponible')
            ->where('lotes_inventario.cantidad_disponible', '>', 0)
            ->select('items.nombre', 'lotes_inventario.cantidad_disponible', 'lotes_inventario.precio_unitario', 'lotes_inventario.fecha_entrada')
            ->orderBy('items.nombre')
            ->orderBy('lotes_inventario.fecha_entrada')
            ->get();
        
        foreach ($lotesActivos as $lote) {
            $this->command->info("   - {$lote->nombre}: {$lote->cantidad_disponible} unidades @ \${$lote->precio_unitario}");
        }
        
        // Estadísticas de movimientos
        $movimientosVenta = DB::table('movimientos_inventario')
            ->where('referencia_tipo', 'venta')
            ->count();
        
        $this->command->info("\n📊 MOVIMIENTOS DE INVENTARIO:");
        $this->command->info("   - Movimientos de egreso por ventas: {$movimientosVenta}");
        
        $this->command->info("\n" . str_repeat("=", 70));
        $this->command->info("✅ SEEDER DE VENTAS COMPLETADO CON ÉXITO!");
        $this->command->info(str_repeat("=", 70));
    }
    
    /**
     * Procesar una venta individual
     */
    private function procesarVenta($ventaData, $productos, $almacenProductos)
    {
        $cliente = $ventaData['cliente'];
        $empleado = $ventaData['empleado'];
        $fecha = $ventaData['fecha'];
        $estado = $ventaData['estado'];
        $metodoPago = $ventaData['metodo_pago'];
        $detallesVenta = $ventaData['detalles'];
        
        // Verificar stock antes de procesar
        $stockSuficiente = true;
        $verificacionStock = [];
        
        foreach ($detallesVenta as $detalle) {
            $producto = null;
            foreach ($productos as $p) {
                if ($p->nombre === $detalle['producto']) {
                    $producto = $p;
                    break;
                }
            }
            
            if (!$producto) {
                $this->command->warn("  ⚠️ Producto '{$detalle['producto']}' no encontrado");
                $stockSuficiente = false;
                continue;
            }
            
            $stockActual = DB::table('almacen_item')
                ->where('id_almacen', $almacenProductos->id_almacen)
                ->where('id_item', $producto->id_item)
                ->value('stock');
            
            if ($stockActual < $detalle['cantidad']) {
                $this->command->warn("  ⚠️ Stock insuficiente para {$producto->nombre}: disponible {$stockActual}, requerido {$detalle['cantidad']}");
                $stockSuficiente = false;
                $verificacionStock[] = [
                    'producto' => $producto,
                    'disponible' => $stockActual,
                    'requerido' => $detalle['cantidad']
                ];
            }
        }
        
        // Si no hay stock suficiente y la venta debería ser completada, la marcamos como pendiente
        if (!$stockSuficiente && $estado === 'completado') {
            $this->command->warn("  ⚠️ Venta marcada como PENDIENTE por falta de stock");
            $estadoReal = 'pendiente';
        } else {
            $estadoReal = $estado;
        }
        
        // Calcular monto total
        $montoTotal = 0;
        $detallesProcesar = [];
        
        foreach ($detallesVenta as $detalle) {
            $producto = null;
            foreach ($productos as $p) {
                if ($p->nombre === $detalle['producto']) {
                    $producto = $p;
                    break;
                }
            }
            
            if (!$producto) continue;
            
            $subtotal = $detalle['cantidad'] * $producto->precio;
            $montoTotal += $subtotal;
            
            $detallesProcesar[] = [
                'id_item' => $producto->id_item,
                'nombre' => $producto->nombre,
                'cantidad' => $detalle['cantidad'],
                'precio' => $producto->precio,
                'subtotal' => $subtotal,
            ];
        }
        
        // Crear nota de venta
        $notaVentaId = DB::table('notas_venta')->insertGetId([
            'fecha_venta' => $fecha,
            'monto_total' => $montoTotal,
            'estado' => $estadoReal,
            'metodo_pago' => $metodoPago,
            'id_transaccion_libelula' => $estadoReal === 'completado' ? 'TRX-' . strtoupper(uniqid()) : null,
            'id_cliente' => $cliente->id_cliente,
            'id_empleado' => $empleado->id_empleado,
            'created_at' => $fecha,
            'updated_at' => now(),
        ]);
        
        $this->command->info("  📝 Nota #{$notaVentaId}: Cliente: {$cliente->nombre} {$cliente->apellido} | Vendedor: {$empleado->nombre} | Total: \${$montoTotal} | {$estadoReal}");
        
        $totalVendido = 0;
        
        // Procesar cada detalle solo si la venta está completada
        if ($estadoReal === 'completado') {
            foreach ($detallesProcesar as $detalle) {
                // Crear detalle de venta
                DB::table('detalles_venta')->insert([
                    'id_nota_venta' => $notaVentaId,
                    'id_almacen' => $almacenProductos->id_almacen,
                    'id_item' => $detalle['id_item'],
                    'cantidad' => $detalle['cantidad'],
                    'precio' => $detalle['precio'],
                    'created_at' => $fecha,
                    'updated_at' => now(),
                ]);
                
                // Actualizar stock en almacen_item
                DB::table('almacen_item')
                    ->where('id_almacen', $almacenProductos->id_almacen)
                    ->where('id_item', $detalle['id_item'])
                    ->decrement('stock', $detalle['cantidad']);
                
                $totalVendido += $detalle['cantidad'];
                
                // === MÉTODO PEPS: Consumir lotes ===
                $lotesDisponibles = DB::table('lotes_inventario')
                    ->where('id_almacen', $almacenProductos->id_almacen)
                    ->where('id_item', $detalle['id_item'])
                    ->where('estado', 'disponible')
                    ->where('cantidad_disponible', '>', 0)
                    ->orderBy('fecha_entrada', 'asc')
                    ->get();
                
                $cantidadPorConsumir = $detalle['cantidad'];
                foreach ($lotesDisponibles as $lote) {
                    if ($cantidadPorConsumir <= 0) break;
                    
                    $consumir = min($lote->cantidad_disponible, $cantidadPorConsumir);
                    $nuevaDisponible = $lote->cantidad_disponible - $consumir;
                    
                    DB::table('lotes_inventario')
                        ->where('id_lote', $lote->id_lote)
                        ->update([
                            'cantidad_disponible' => $nuevaDisponible,
                            'estado' => $nuevaDisponible == 0 ? 'consumido' : 'disponible',
                            'updated_at' => now(),
                        ]);
                    
                    $cantidadPorConsumir -= $consumir;
                    
                    $this->command->info("    - Lote {$lote->id_lote}: {$consumir} unidades de {$detalle['nombre']} (Precio: \${$lote->precio_unitario})");
                }
                
                // Crear movimiento de inventario (egreso)
                DB::table('movimientos_inventario')->insert([
                    'tipo_movimiento' => 'egreso',
                    'id_almacen' => $almacenProductos->id_almacen,
                    'id_item' => $detalle['id_item'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio'],
                    'costo_total' => $detalle['subtotal'],
                    'fecha_movimiento' => $fecha,
                    'referencia_id' => $notaVentaId,
                    'referencia_tipo' => 'venta',
                    'estado' => 'completado',
                    'observaciones' => "Venta #{$notaVentaId} - Cliente: {$cliente->nombre} {$cliente->apellido}",
                    'created_at' => $fecha,
                    'updated_at' => now(),
                ]);
                
                $this->command->info("    - {$detalle['nombre']}: {$detalle['cantidad']} unidades @ \${$detalle['precio']} = \${$detalle['subtotal']}");
            }
        }
        
        return [
            'estado' => $estadoReal,
            'monto' => $montoTotal,
            'cantidad' => $totalVendido
        ];
    }
}