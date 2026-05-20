<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompraInicialSeeder extends Seeder
{
    public function run(): void
    {
        // Deshabilitar foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Limpiar tablas en orden correcto
        DB::table('movimientos_inventario')->where('referencia_tipo', 'compra')->delete();
        DB::table('lotes_inventario')->where('referencia_tipo', 'compra')->delete();
        DB::table('detalles_compra')->delete();
        DB::table('notas_compra')->delete();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ==========================================
        // 1. OBTENER REFERENCIAS EXISTENTES
        // ==========================================
        
        // Empleados
        $empleadoCompra = DB::table('empleados')->where('nombre', 'Roberto')->where('apellido', 'Flores')->first();
        if (!$empleadoCompra) $empleadoCompra = DB::table('empleados')->first();
        
        $empleadoInventario = DB::table('empleados')->where('nombre', 'Mario')->where('apellido', 'López')->first();
        if (!$empleadoInventario) $empleadoInventario = $empleadoCompra;
        
        $empleadoAdmin = DB::table('empleados')->where('nombre', 'Carlos')->where('apellido', 'Mendoza')->first();
        if (!$empleadoAdmin) $empleadoAdmin = $empleadoCompra;

        // Proveedores
        $proveedores = DB::table('proveedores')->where('tipo_proveedor', 'empresa')->get();
        if ($proveedores->isEmpty()) $proveedores = DB::table('proveedores')->take(3)->get();
        
        $proveedor1 = $proveedores[0] ?? null;
        $proveedor2 = $proveedores[1] ?? $proveedor1;
        $proveedor3 = $proveedores[2] ?? $proveedor1;

        // Almacenes
        $almacenInsumos = DB::table('almacenes')->where('nombre', 'Almacén Insumos')->first();
        if (!$almacenInsumos) $almacenInsumos = DB::table('almacenes')->where('tipo_almacen', 'insumo')->first();
        if (!$almacenInsumos) $almacenInsumos = DB::table('almacenes')->first();
        
        $almacenCentral = DB::table('almacenes')->where('nombre', 'Almacén Central')->first();
        if (!$almacenCentral) $almacenCentral = DB::table('almacenes')->skip(1)->first() ?? $almacenInsumos;
        
        $almacenRefrigerado = DB::table('almacenes')->where('nombre', 'Almacén Refrigerado')->first();
        if (!$almacenRefrigerado) $almacenRefrigerado = $almacenInsumos;
        
        $almacenProductos = DB::table('almacenes')->where('tipo_almacen', 'producto')->first();
        if (!$almacenProductos) $almacenProductos = $almacenCentral;

        // Items (insumos y productos)
        $itemsInsumos = DB::table('items')->where('tipo_item', 'insumo')->get();
        if ($itemsInsumos->isEmpty()) {
            $itemsInsumos = DB::table('items')->take(5)->get();
        }
        
        $itemsProductos = DB::table('items')->where('tipo_item', 'producto')->get();
        if ($itemsProductos->isEmpty()) {
            $itemsProductos = DB::table('items')->skip(5)->take(5)->get();
        }

        // Crear mapa de items por nombre
        $itemsMap = [];
        foreach ($itemsInsumos as $item) {
            $itemsMap[$item->nombre] = $item;
        }
        foreach ($itemsProductos as $item) {
            $itemsMap[$item->nombre] = $item;
        }

        // ==========================================
        // 2. CREAR REGISTROS INICIALES EN almacen_item
        // ==========================================
        $this->command->info("\n📦 Creando registros base en almacen_item...");
        
        $almacenes = [$almacenInsumos, $almacenCentral, $almacenRefrigerado, $almacenProductos];
        foreach ($almacenes as $almacen) {
            if (!$almacen) continue;
            
            foreach ($itemsInsumos as $item) {
                $existe = DB::table('almacen_item')
                    ->where('id_almacen', $almacen->id_almacen)
                    ->where('id_item', $item->id_item)
                    ->exists();
                
                if (!$existe) {
                    DB::table('almacen_item')->insert([
                        'id_almacen' => $almacen->id_almacen,
                        'id_item' => $item->id_item,
                        'stock' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            
            foreach ($itemsProductos as $item) {
                $existe = DB::table('almacen_item')
                    ->where('id_almacen', $almacen->id_almacen)
                    ->where('id_item', $item->id_item)
                    ->exists();
                
                if (!$existe) {
                    DB::table('almacen_item')->insert([
                        'id_almacen' => $almacen->id_almacen,
                        'id_item' => $item->id_item,
                        'stock' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        $this->command->info("  ✅ Registros base creados");

        // ==========================================
        // 3. COMPRA 1 - ENERO (COMPRA GRANDE INICIAL)
        // ==========================================
        $this->crearCompraAgrupada([
            'proveedor' => $proveedor1,
            'empleado' => $empleadoCompra,
            'almacen' => $almacenInsumos,
            'fecha' => Carbon::create(2026, 1, 15, 10, 30, 0),
            'estado' => 'completado',
            'detalles' => [
                ['item_nombre' => 'Harina de Trigo', 'cantidad' => 1500, 'precio' => 17.80], // 1000+500
                ['item_nombre' => 'Azúcar Estándar', 'cantidad' => 800, 'precio' => 21.20], // 500+300
                ['item_nombre' => 'Levadura Seca', 'cantidad' => 350, 'precio' => 14.70], // 200+150
                ['item_nombre' => 'Mantequilla', 'cantidad' => 500, 'precio' => 82.50], // 300+200
                ['item_nombre' => 'Huevo', 'cantidad' => 1800, 'precio' => 4.25], // 1000+800
            ],
            'observaciones' => 'Compra masiva de enero - inicio de año'
        ]);

        // ==========================================
        // 4. COMPRA 2 - FEBRERO
        // ==========================================
        $this->crearCompraAgrupada([
            'proveedor' => $proveedor2,
            'empleado' => $empleadoCompra,
            'almacen' => $almacenInsumos,
            'fecha' => Carbon::create(2026, 2, 10, 11, 0, 0),
            'estado' => 'completado',
            'detalles' => [
                ['item_nombre' => 'Harina de Trigo', 'cantidad' => 800, 'precio' => 18.00],
                ['item_nombre' => 'Azúcar Estándar', 'cantidad' => 400, 'precio' => 22.00],
                ['item_nombre' => 'Levadura Seca', 'cantidad' => 180, 'precio' => 15.00],
                ['item_nombre' => 'Mantequilla', 'cantidad' => 250, 'precio' => 84.00],
                ['item_nombre' => 'Huevo', 'cantidad' => 1200, 'precio' => 4.40],
            ],
            'observaciones' => 'Compra febrero - temporada de pan dulce'
        ]);

        // ==========================================
        // 5. COMPRA 3 - MARZO (PRODUCTOS NUEVOS)
        // ==========================================
        $this->crearCompraAgrupada([
            'proveedor' => $proveedor1,
            'empleado' => $empleadoInventario,
            'almacen' => $almacenRefrigerado,
            'fecha' => Carbon::create(2026, 3, 5, 9, 45, 0),
            'estado' => 'completado',
            'detalles' => [
                ['item_nombre' => 'Mantequilla', 'cantidad' => 180, 'precio' => 85.00],
                ['item_nombre' => 'Huevo', 'cantidad' => 1500, 'precio' => 4.50],
            ],
            'observaciones' => 'Compra marzo - lácteos refrigerados'
        ]);

        // ==========================================
        // 6. COMPRA 4 - ABRIL (PRECIOS ELEVADOS)
        // ==========================================
        $this->crearCompraAgrupada([
            'proveedor' => $proveedor3,
            'empleado' => $empleadoCompra,
            'almacen' => $almacenCentral,
            'fecha' => Carbon::create(2026, 4, 12, 14, 20, 0),
            'estado' => 'completado',
            'detalles' => [
                ['item_nombre' => 'Harina de Trigo', 'cantidad' => 600, 'precio' => 19.00],
                ['item_nombre' => 'Azúcar Estándar', 'cantidad' => 350, 'precio' => 23.00],
                ['item_nombre' => 'Levadura Seca', 'cantidad' => 120, 'precio' => 16.00],
                ['item_nombre' => 'Mantequilla', 'cantidad' => 150, 'precio' => 88.00],
                ['item_nombre' => 'Huevo', 'cantidad' => 900, 'precio' => 4.80],
            ],
            'observaciones' => 'Compra abril - precios elevados por temporada'
        ]);

        // ==========================================
        // 7. COMPRA 5 - MAYO (PENDIENTE)
        // ==========================================
        $this->crearCompraAgrupada([
            'proveedor' => $proveedor2,
            'empleado' => $empleadoCompra,
            'almacen' => $almacenInsumos,
            'fecha' => Carbon::create(2026, 5, 18, 15, 30, 0),
            'estado' => 'pendiente',
            'detalles' => [
                ['item_nombre' => 'Harina de Trigo', 'cantidad' => 400, 'precio' => 19.50],
                ['item_nombre' => 'Azúcar Estándar', 'cantidad' => 250, 'precio' => 23.50],
                ['item_nombre' => 'Levadura Seca', 'cantidad' => 100, 'precio' => 16.50],
                ['item_nombre' => 'Mantequilla', 'cantidad' => 100, 'precio' => 90.00],
                ['item_nombre' => 'Huevo', 'cantidad' => 600, 'precio' => 5.00],
            ],
            'observaciones' => 'Compra mayo - pendiente de aprobación'
        ]);

        // ==========================================
        // 8. COMPRA 6 - ENERO (PRODUCTOS)
        // ==========================================
        $this->crearCompraAgrupada([
            'proveedor' => $proveedor1,
            'empleado' => $empleadoAdmin,
            'almacen' => $almacenProductos,
            'fecha' => Carbon::create(2026, 1, 20, 12, 0, 0),
            'estado' => 'completado',
            'detalles' => [
                ['item_nombre' => 'Pan de Muerto', 'cantidad' => 500, 'precio' => 25.00],
                ['item_nombre' => 'Concha', 'cantidad' => 1000, 'precio' => 12.00],
                ['item_nombre' => 'Bolillo', 'cantidad' => 2000, 'precio' => 4.50],
                ['item_nombre' => 'Pastel de Chocolate', 'cantidad' => 100, 'precio' => 85.00],
                ['item_nombre' => 'Galleta María', 'cantidad' => 800, 'precio' => 8.00],
            ],
            'observaciones' => 'Compra de productos terminados para venta'
        ]);

        // ==========================================
        // 9. COMPRA 7 - MARZO (PRODUCTOS)
        // ==========================================
        $this->crearCompraAgrupada([
            'proveedor' => $proveedor2,
            'empleado' => $empleadoInventario,
            'almacen' => $almacenProductos,
            'fecha' => Carbon::create(2026, 3, 15, 10, 0, 0),
            'estado' => 'completado',
            'detalles' => [
                ['item_nombre' => 'Pan de Muerto', 'cantidad' => 300, 'precio' => 26.00],
                ['item_nombre' => 'Concha', 'cantidad' => 800, 'precio' => 12.50],
                ['item_nombre' => 'Bolillo', 'cantidad' => 1500, 'precio' => 4.80],
                ['item_nombre' => 'Galleta María', 'cantidad' => 600, 'precio' => 8.50],
            ],
            'observaciones' => 'Compra de productos - temporada baja'
        ]);

        // ==========================================
        // 10. COMPRA 8 - COMPRA CANCELADA
        // ==========================================
        $this->crearCompraAgrupada([
            'proveedor' => $proveedor3,
            'empleado' => $empleadoCompra,
            'almacen' => $almacenInsumos,
            'fecha' => Carbon::create(2026, 4, 25, 9, 0, 0),
            'estado' => 'cancelado',
            'detalles' => [
                ['item_nombre' => 'Harina de Trigo', 'cantidad' => 300, 'precio' => 20.00],
                ['item_nombre' => 'Azúcar Estándar', 'cantidad' => 200, 'precio' => 24.00],
            ],
            'observaciones' => 'Compra cancelada - proveedor sin stock'
        ]);

        // ==========================================
        // 11. COMPRA 9 - MAYO (COMPRA EXPRÉS)
        // ==========================================
        $this->crearCompraAgrupada([
            'proveedor' => $proveedor1,
            'empleado' => $empleadoCompra,
            'almacen' => $almacenInsumos,
            'fecha' => Carbon::now()->subDays(2),
            'estado' => 'completado',
            'detalles' => [
                ['item_nombre' => 'Huevo', 'cantidad' => 500, 'precio' => 5.10],
                ['item_nombre' => 'Mantequilla', 'cantidad' => 80, 'precio' => 92.00],
            ],
            'observaciones' => 'Compra exprés por alta demanda'
        ]);

        // ==========================================
        // 12. COMPRA 10 - JUNIO (COMPRA PROGRAMADA)
        // ==========================================
        $this->crearCompraAgrupada([
            'proveedor' => $proveedor2,
            'empleado' => $empleadoInventario,
            'almacen' => $almacenRefrigerado,
            'fecha' => Carbon::now()->addDays(5),
            'estado' => 'pendiente',
            'detalles' => [
                ['item_nombre' => 'Mantequilla', 'cantidad' => 120, 'precio' => 91.00],
                ['item_nombre' => 'Huevo', 'cantidad' => 800, 'precio' => 5.20],
                ['item_nombre' => 'Levadura Seca', 'cantidad' => 150, 'precio' => 17.00],
            ],
            'observaciones' => 'Compra programada para junio'
        ]);

        // ==========================================
        // 13. COMPRA 11 - STOCK PARA ALMACÉN CENTRAL (AGOSTO)
        // ==========================================
        $this->crearCompraAgrupada([
            'proveedor' => $proveedor1,
            'empleado' => $empleadoCompra,
            'almacen' => $almacenCentral,
            'fecha' => Carbon::create(2026, 8, 10, 10, 0, 0),
            'estado' => 'completado',
            'detalles' => [
                ['item_nombre' => 'Harina de Trigo', 'cantidad' => 1000, 'precio' => 19.00],
                ['item_nombre' => 'Azúcar Estándar', 'cantidad' => 500, 'precio' => 23.00],
                ['item_nombre' => 'Levadura Seca', 'cantidad' => 200, 'precio' => 16.00],
                ['item_nombre' => 'Mantequilla', 'cantidad' => 300, 'precio' => 88.00],
                ['item_nombre' => 'Huevo', 'cantidad' => 1000, 'precio' => 4.90],
            ],
            'observaciones' => 'Compra para abastecer almacén central'
        ]);

        // ==========================================
        // 14. COMPRA 12 - PRODUCTOS PARA ALMACÉN CENTRAL (SEPTIEMBRE)
        // ==========================================
        $this->crearCompraAgrupada([
            'proveedor' => $proveedor2,
            'empleado' => $empleadoInventario,
            'almacen' => $almacenCentral,
            'fecha' => Carbon::create(2026, 9, 5, 9, 0, 0),
            'estado' => 'completado',
            'detalles' => [
                ['item_nombre' => 'Pan de Muerto', 'cantidad' => 800, 'precio' => 26.00],
                ['item_nombre' => 'Concha', 'cantidad' => 1500, 'precio' => 12.50],
                ['item_nombre' => 'Bolillo', 'cantidad' => 3000, 'precio' => 4.80],
                ['item_nombre' => 'Pastel de Chocolate', 'cantidad' => 200, 'precio' => 86.00],
                ['item_nombre' => 'Galleta María', 'cantidad' => 1500, 'precio' => 8.50],
            ],
            'observaciones' => 'Productos para almacén central'
        ]);

        // ==========================================
        // 13. MOSTRAR REPORTE FINAL
        // ==========================================
        $this->mostrarReporteFinal();
    }

    /**
     * Crear una compra completa con sus detalles (agrupando items duplicados)
     */
    private function crearCompraAgrupada(array $data): void
    {
        $proveedor = $data['proveedor'];
        $empleado = $data['empleado'];
        $almacen = $data['almacen'];
        $fecha = $data['fecha'];
        $estado = $data['estado'];
        $detallesData = $data['detalles'];
        $observaciones = $data['observaciones'] ?? '';

        if (!$proveedor || !$empleado || !$almacen) {
            $this->command->warn("  ⚠️ Faltan referencias, compra omitida");
            return;
        }

        // Agrupar detalles por item (por si hay duplicados)
        $agrupados = [];
        foreach ($detallesData as $detalle) {
            $key = $detalle['item_nombre'];
            if (!isset($agrupados[$key])) {
                $agrupados[$key] = [
                    'item_nombre' => $detalle['item_nombre'],
                    'cantidad' => 0,
                    'precio' => $detalle['precio']
                ];
            }
            $agrupados[$key]['cantidad'] += $detalle['cantidad'];
            // El precio se mantiene como el primero (o podrías promediar)
        }

        // Calcular monto total
        $montoTotal = 0;
        foreach ($agrupados as $detalle) {
            $montoTotal += $detalle['cantidad'] * $detalle['precio'];
        }

        // Crear nota de compra
        $notaCompraId = DB::table('notas_compra')->insertGetId([
            'fecha_compra' => $fecha,
            'monto_total' => $montoTotal,
            'estado' => $estado,
            'id_empleado' => $empleado->id_empleado,
            'id_proveedor' => $proveedor->id_proveedor,
            'created_at' => $fecha,
            'updated_at' => now(),
        ]);

        // Procesar cada detalle agrupado
        foreach ($agrupados as $detalleData) {
            // Buscar el item por nombre exacto
            $item = DB::table('items')
                ->where('nombre', $detalleData['item_nombre'])
                ->first();
            
            if (!$item) {
                $this->command->warn("  ⚠️ Item '{$detalleData['item_nombre']}' no encontrado");
                continue;
            }

            $cantidad = $detalleData['cantidad'];
            $precio = $detalleData['precio'];
            $subtotal = $cantidad * $precio;

            // Crear detalle de compra (ahora sin duplicados)
            DB::table('detalles_compra')->insert([
                'id_nota_compra' => $notaCompraId,
                'id_almacen' => $almacen->id_almacen,
                'id_item' => $item->id_item,
                'cantidad' => $cantidad,
                'precio' => $precio,
                'created_at' => $fecha,
                'updated_at' => now(),
            ]);

            // Solo procesar inventario si la compra está completada
            if ($estado === 'completado') {
                // Actualizar stock en almacen_item
                $almacenItem = DB::table('almacen_item')
                    ->where('id_almacen', $almacen->id_almacen)
                    ->where('id_item', $item->id_item)
                    ->first();

                if ($almacenItem) {
                    DB::table('almacen_item')
                        ->where('id_almacen', $almacen->id_almacen)
                        ->where('id_item', $item->id_item)
                        ->update([
                            'stock' => $almacenItem->stock + $cantidad,
                            'updated_at' => now()
                        ]);
                } else {
                    DB::table('almacen_item')->insert([
                        'id_almacen' => $almacen->id_almacen,
                        'id_item' => $item->id_item,
                        'stock' => $cantidad,
                        'created_at' => $fecha,
                        'updated_at' => now(),
                    ]);
                }

                // Crear lote en lotes_inventario
                DB::table('lotes_inventario')->insert([
                    'id_almacen' => $almacen->id_almacen,
                    'id_item' => $item->id_item,
                    'cantidad_inicial' => $cantidad,
                    'cantidad_disponible' => $cantidad,
                    'precio_unitario' => $precio,
                    'fecha_entrada' => $fecha,
                    'fecha_salida' => null,
                    'metodo_valuacion' => 'PEPS',
                    'estado' => 'disponible',
                    'referencia_id' => $notaCompraId,
                    'referencia_tipo' => 'compra',
                    'created_at' => $fecha,
                    'updated_at' => now(),
                ]);

                // Registrar movimiento de inventario
                DB::table('movimientos_inventario')->insert([
                    'tipo_movimiento' => 'ingreso',
                    'id_almacen' => $almacen->id_almacen,
                    'id_item' => $item->id_item,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'costo_total' => $subtotal,
                    'fecha_movimiento' => $fecha,
                    'referencia_id' => $notaCompraId,
                    'referencia_tipo' => 'compra',
                    'estado' => 'completado',
                    'observaciones' => $observaciones,
                    'created_at' => $fecha,
                    'updated_at' => now(),
                ]);
            }
        }

        $estadoIcono = $estado === 'completado' ? '✅' : ($estado === 'pendiente' ? '⏳' : '❌');
        $this->command->info("  {$estadoIcono} Compra #{$notaCompraId} | {$estado} | $" . number_format($montoTotal, 2) . " | {$fecha->format('d/m/Y')}");
    }

    /**
     * Mostrar reporte final con estadísticas
     */
    private function mostrarReporteFinal(): void
    {
        $totalCompras = DB::table('notas_compra')->count();
        $totalCompletadas = DB::table('notas_compra')->where('estado', 'completado')->count();
        $totalPendientes = DB::table('notas_compra')->where('estado', 'pendiente')->count();
        $totalCanceladas = DB::table('notas_compra')->where('estado', 'cancelado')->count();
        
        $totalDetalles = DB::table('detalles_compra')->count();
        $totalLotes = DB::table('lotes_inventario')->where('referencia_tipo', 'compra')->count();
        $totalMovimientos = DB::table('movimientos_inventario')->where('referencia_tipo', 'compra')->count();
        
        $montoTotalCompletadas = DB::table('notas_compra')->where('estado', 'completado')->sum('monto_total');

        $this->command->info("\n" . str_repeat("=", 60));
        $this->command->info("📊 REPORTE FINAL DE COMPRAS");
        $this->command->info(str_repeat("=", 60));
        $this->command->info("📋 NOTAS DE COMPRA:");
        $this->command->info("   - Total: {$totalCompras}");
        $this->command->info("   - Completadas: {$totalCompletadas}");
        $this->command->info("   - Pendientes: {$totalPendientes}");
        $this->command->info("   - Canceladas: {$totalCanceladas}");
        $this->command->info("");
        $this->command->info("💰 MONTO TOTAL COMPLETADAS: $" . number_format($montoTotalCompletadas, 2));
        $this->command->info("");
        $this->command->info("📦 DETALLES Y MOVIMIENTOS:");
        $this->command->info("   - Detalles de compra: {$totalDetalles}");
        $this->command->info("   - Lotes creados: {$totalLotes}");
        $this->command->info("   - Movimientos ingreso: {$totalMovimientos}");
        
        // Stock actual por insumo
        $this->command->info("\n📦 STOCK ACTUAL POR INSUMO:");
        $stocks = DB::table('almacen_item')
            ->join('items', 'almacen_item.id_item', '=', 'items.id_item')
            ->select('items.nombre', DB::raw('SUM(almacen_item.stock) as total_stock'), 'items.unidad_medida')
            ->where('items.tipo_item', 'insumo')
            ->groupBy('items.id_item', 'items.nombre', 'items.unidad_medida')
            ->orderBy('items.nombre')
            ->get();
        
        foreach ($stocks as $stock) {
            $this->command->info("   - {$stock->nombre}: " . number_format($stock->total_stock, 0) . " {$stock->unidad_medida}");
        }
        
        $this->command->info("\n" . str_repeat("=", 60));
        $this->command->info("✅ SEEDER COMPLETADO CON ÉXITO!");
        $this->command->info(str_repeat("=", 60));
    }
}