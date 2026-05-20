<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProduccionSeeder extends Seeder
{
    public function run(): void
    {
        // Deshabilitar foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Limpiar solo producciones y sus detalles (NO movimientos/lotes de compras previas)
        DB::table('detalle_produccion')->delete();
        DB::table('producciones')->delete();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info("\n" . str_repeat("=", 70));
        $this->command->info("🏭 INICIANDO PRODUCCIONES MASIVAS");
        $this->command->info(str_repeat("=", 70));

        // ==========================================
        // 1. OBTENER REFERENCIAS
        // ==========================================
        
        // Empleados
        $empleadoSolicita = DB::table('empleados')->where('nombre', 'Dennis')->where('apellido', 'Rodríguez')->first();
        if (!$empleadoSolicita) $empleadoSolicita = DB::table('empleados')->first();
        
        $empleadoAutoriza = DB::table('empleados')->where('nombre', 'Mario')->where('apellido', 'López')->first();
        if (!$empleadoAutoriza) $empleadoAutoriza = DB::table('empleados')->skip(1)->first();
        
        $empleadoAdmin = DB::table('empleados')->where('nombre', 'Carlos')->where('apellido', 'Mendoza')->first();
        if (!$empleadoAdmin) $empleadoAdmin = $empleadoSolicita;

        // Almacenes
        $almacenInsumos = DB::table('almacenes')->where('nombre', 'Almacén Insumos')->first();
        if (!$almacenInsumos) $almacenInsumos = DB::table('almacenes')->where('tipo_almacen', 'insumo')->first();
        
        $almacenProductos = DB::table('almacenes')->where('tipo_almacen', 'producto')->first();
        if (!$almacenProductos) $almacenProductos = DB::table('almacenes')->first();
        
        $almacenCentral = DB::table('almacenes')->where('nombre', 'Almacén Central')->first();
        if (!$almacenCentral) $almacenCentral = $almacenProductos;

        // ==========================================
        // 2. OBTENER TODOS LOS PRODUCTOS CON SUS RECETAS
        // ==========================================
        $productos = DB::table('items')
            ->join('productos', 'items.id_item', '=', 'productos.id_item')
            ->join('categoria_producto', 'productos.id_cat_producto', '=', 'categoria_producto.id_cat_producto')
            ->select('items.id_item', 'items.nombre', 'items.unidad_medida', 'productos.id_producto', 'productos.precio', 'categoria_producto.nombre as categoria')
            ->get();

        if ($productos->isEmpty()) {
            $this->command->error("No hay productos registrados. Ejecuta primero ItemsProductosInsumosSeeder.");
            return;
        }

        $this->command->info("\n📦 Productos encontrados: " . $productos->count());
        foreach ($productos as $prod) {
            $this->command->info("  - {$prod->nombre} ({$prod->categoria}) - Precio: \${$prod->precio}");
        }

        // Obtener recetas y sus detalles para cada producto
        $recetasPorProducto = [];
        $detallesPorReceta = [];

        foreach ($productos as $producto) {
            $receta = DB::table('recetas')->where('id_producto', $producto->id_producto)->first();
            if ($receta) {
                $recetasPorProducto[$producto->id_producto] = $receta;
                
                $detalles = DB::table('detalle_receta')
                    ->join('insumos', 'detalle_receta.id_insumo', '=', 'insumos.id_insumo')
                    ->join('items', 'insumos.id_item', '=', 'items.id_item')
                    ->where('detalle_receta.id_receta', $receta->id_receta)
                    ->select('detalle_receta.*', 'items.id_item', 'items.nombre as insumo_nombre', 'items.unidad_medida', 'insumos.id_insumo')
                    ->get();
                
                if ($detalles->isNotEmpty()) {
                    $detallesPorReceta[$receta->id_receta] = $detalles;
                    $this->command->info("  ✓ Receta para {$producto->nombre}: " . $detalles->count() . " insumos");
                } else {
                    $this->command->warn("  ⚠️ Receta para {$producto->nombre} sin detalles");
                }
            } else {
                $this->command->warn("  ⚠️ {$producto->nombre} no tiene receta asociada");
            }
        }

        // ==========================================
        // 3. CONFIGURAR PRODUCCIONES MÚLTIPLES
        // ==========================================
        
        $producciones = [
            // Producción 1: Pan de Muerto - Enero
            [
                'producto_nombre' => 'Pan de Muerto',
                'cantidad' => 500,
                'fecha' => Carbon::create(2026, 1, 20, 8, 0, 0),
                'estado' => 'aprobado',
                'solicita' => $empleadoSolicita,
                'autoriza' => $empleadoAutoriza,
                'observaciones' => 'Producción masiva para temporada de Día de Muertos',
                'dias_vencimiento' => 10,
            ],
            // Producción 2: Pan de Muerto - Febrero
            [
                'producto_nombre' => 'Pan de Muerto',
                'cantidad' => 300,
                'fecha' => Carbon::create(2026, 2, 15, 9, 30, 0),
                'estado' => 'aprobado',
                'solicita' => $empleadoSolicita,
                'autoriza' => $empleadoAutoriza,
                'observaciones' => 'Reposición de stock',
                'dias_vencimiento' => 10,
            ],
            // Producción 3: Concha - Marzo
            [
                'producto_nombre' => 'Concha',
                'cantidad' => 800,
                'fecha' => Carbon::create(2026, 3, 5, 7, 0, 0),
                'estado' => 'aprobado',
                'solicita' => $empleadoSolicita,
                'autoriza' => $empleadoAutoriza,
                'observaciones' => 'Producción para fin de semana',
                'dias_vencimiento' => 5,
            ],
            // Producción 4: Bolillo - Abril
            [
                'producto_nombre' => 'Bolillo',
                'cantidad' => 2000,
                'fecha' => Carbon::create(2026, 4, 10, 4, 0, 0),
                'estado' => 'aprobado',
                'solicita' => $empleadoSolicita,
                'autoriza' => $empleadoAutoriza,
                'observaciones' => 'Producción diaria de pan para venta',
                'dias_vencimiento' => 2,
            ],
            // Producción 5: Pastel de Chocolate - Mayo
            [
                'producto_nombre' => 'Pastel de Chocolate',
                'cantidad' => 150,
                'fecha' => Carbon::create(2026, 5, 1, 10, 0, 0),
                'estado' => 'aprobado',
                'solicita' => $empleadoAdmin,
                'autoriza' => $empleadoAutoriza,
                'observaciones' => 'Producción especial para cumpleaños',
                'dias_vencimiento' => 14,
            ],
            // Producción 6: Galleta María - Junio
            [
                'producto_nombre' => 'Galleta María',
                'cantidad' => 3000,
                'fecha' => Carbon::create(2026, 6, 20, 6, 0, 0),
                'estado' => 'aprobado',
                'solicita' => $empleadoSolicita,
                'autoriza' => $empleadoAutoriza,
                'observaciones' => 'Producción masiva de galletas',
                'dias_vencimiento' => 30,
            ],
            // Producción 7: Pan de Muerto - Julio (pendiente)
            [
                'producto_nombre' => 'Pan de Muerto',
                'cantidad' => 400,
                'fecha' => Carbon::create(2026, 7, 10, 8, 0, 0),
                'estado' => 'pendiente',
                'solicita' => $empleadoSolicita,
                'autoriza' => null,
                'observaciones' => 'Esperando autorización de gerencia',
                'dias_vencimiento' => 10,
            ],
            // Producción 8: Concha - Agosto (cancelado)
            [
                'producto_nombre' => 'Concha',
                'cantidad' => 200,
                'fecha' => Carbon::create(2026, 8, 15, 9, 0, 0),
                'estado' => 'cancelado',
                'solicita' => $empleadoSolicita,
                'autoriza' => $empleadoAdmin,
                'observaciones' => 'Cancelado por falta de insumos',
                'dias_vencimiento' => 5,
            ],
            // Producción 9: Bolillo - Septiembre (rechazado)
            [
                'producto_nombre' => 'Bolillo',
                'cantidad' => 1500,
                'fecha' => Carbon::create(2026, 9, 5, 4, 0, 0),
                'estado' => 'rechazado',
                'solicita' => $empleadoSolicita,
                'autoriza' => $empleadoAutoriza,
                'observaciones' => 'Rechazado por baja demanda',
                'dias_vencimiento' => 2,
            ],
            // Producción 10: Pastel de Chocolate - Octubre
            [
                'producto_nombre' => 'Pastel de Chocolate',
                'cantidad' => 200,
                'fecha' => Carbon::create(2026, 10, 20, 11, 0, 0),
                'estado' => 'aprobado',
                'solicita' => $empleadoAdmin,
                'autoriza' => $empleadoAutoriza,
                'observaciones' => 'Producción para eventos especiales',
                'dias_vencimiento' => 14,
            ],
            // Producción 11: Galleta María - Noviembre
            [
                'producto_nombre' => 'Galleta María',
                'cantidad' => 2500,
                'fecha' => Carbon::create(2026, 11, 15, 5, 0, 0),
                'estado' => 'aprobado',
                'solicita' => $empleadoSolicita,
                'autoriza' => $empleadoAutoriza,
                'observaciones' => 'Producción para temporada navideña',
                'dias_vencimiento' => 30,
            ],
            // Producción 12: Concha - Diciembre
            [
                'producto_nombre' => 'Concha',
                'cantidad' => 1200,
                'fecha' => Carbon::create(2026, 12, 10, 7, 30, 0),
                'estado' => 'aprobado',
                'solicita' => $empleadoSolicita,
                'autoriza' => $empleadoAutoriza,
                'observaciones' => 'Producción navideña',
                'dias_vencimiento' => 5,
            ],
            // Producción 13: Pan de Muerto para Almacén Central
            [
                'producto_nombre' => 'Pan de Muerto',
                'cantidad' => 500,
                'fecha' => Carbon::create(2026, 8, 20, 8, 0, 0),
                'estado' => 'aprobado',
                'solicita' => $empleadoSolicita,
                'autoriza' => $empleadoAutoriza,
                'observaciones' => 'Producción para almacén central',
                'dias_vencimiento' => 10,
            ],
            // Producción 14: Concha para Almacén Central
            [
                'producto_nombre' => 'Concha',
                'cantidad' => 1000,
                'fecha' => Carbon::create(2026, 9, 10, 7, 0, 0),
                'estado' => 'aprobado',
                'solicita' => $empleadoSolicita,
                'autoriza' => $empleadoAutoriza,
                'observaciones' => 'Conchas para almacén central',
                'dias_vencimiento' => 5,
            ],
            // Producción 15: Bolillo para Almacén Central
            [
                'producto_nombre' => 'Bolillo',
                'cantidad' => 2000,
                'fecha' => Carbon::create(2026, 10, 5, 4, 0, 0),
                'estado' => 'aprobado',
                'solicita' => $empleadoSolicita,
                'autoriza' => $empleadoAutoriza,
                'observaciones' => 'Bolillos para almacén central',
                'dias_vencimiento' => 2,
            ],
            // Producción 16: Galleta María para Almacén Central
            [
                'producto_nombre' => 'Galleta María',
                'cantidad' => 2000,
                'fecha' => Carbon::create(2026, 11, 15, 6, 0, 0),
                'estado' => 'aprobado',
                'solicita' => $empleadoSolicita,
                'autoriza' => $empleadoAutoriza,
                'observaciones' => 'Galletas para almacén central',
                'dias_vencimiento' => 30,
            ],
        ];

        // ==========================================
        // 4. EJECUTAR CADA PRODUCCIÓN
        // ==========================================
        
        $totalIngresos = 0;
        $totalEgresos = 0;
        $produccionesExitosas = 0;
        $produccionesPendientes = 0;
        $produccionesRechazadas = 0;
        $produccionesCanceladas = 0;

        foreach ($producciones as $index => $prodData) {
            $this->command->info("\n" . str_repeat("-", 70));
            $this->command->info("🏭 Producción " . ($index + 1) . " de " . count($producciones));
            
            $resultado = $this->procesarProduccion(
                $prodData,
                $productos,
                $recetasPorProducto,
                $detallesPorReceta,
                $almacenInsumos,
                $almacenProductos,
                $almacenCentral
            );
            
            if ($resultado['estado'] === 'aprobado') {
                $produccionesExitosas++;
                $totalIngresos += $resultado['ingreso_cantidad'];
                $totalEgresos += $resultado['egreso_cantidad'];
            } elseif ($resultado['estado'] === 'pendiente') {
                $produccionesPendientes++;
            } elseif ($resultado['estado'] === 'rechazado') {
                $produccionesRechazadas++;
            } elseif ($resultado['estado'] === 'cancelado') {
                $produccionesCanceladas++;
            }
        }

        // ==========================================
        // 5. REPORTE FINAL DETALLADO
        // ==========================================
        $this->command->info("\n" . str_repeat("=", 70));
        $this->command->info("📊 REPORTE FINAL DE PRODUCCIONES");
        $this->command->info(str_repeat("=", 70));
        
        $this->command->info("\n📋 RESUMEN POR ESTADO:");
        $this->command->info("   ✅ Aprobadas:     {$produccionesExitosas}");
        $this->command->info("   ⏳ Pendientes:    {$produccionesPendientes}");
        $this->command->info("   ❌ Rechazadas:    {$produccionesRechazadas}");
        $this->command->info("   🚫 Canceladas:    {$produccionesCanceladas}");
        
        $this->command->info("\n📦 TOTALES PRODUCIDOS:");
        $this->command->info("   - Unidades producidas: " . number_format($totalIngresos, 0));
        $this->command->info("   - Unidades de insumos consumidos: " . number_format($totalEgresos, 0));
        
        // Mostrar stock actual por producto
        $this->command->info("\n📦 STOCK ACTUAL DE PRODUCTOS:");
        $stocksProductosFinal = DB::table('almacen_item')
            ->join('items', 'almacen_item.id_item', '=', 'items.id_item')
            ->where('items.tipo_item', 'producto')
            ->where('almacen_item.id_almacen', $almacenProductos->id_almacen)
            ->select('items.nombre', 'almacen_item.stock', 'items.unidad_medida')
            ->orderBy('items.nombre')
            ->get();
        
        foreach ($stocksProductosFinal as $stock) {
            $porcentaje = $stock->stock > 0 ? "✓" : "⚠️";
            $this->command->info("   {$porcentaje} {$stock->nombre}: " . number_format($stock->stock, 0) . " {$stock->unidad_medida}");
        }
        
        // Mostrar stock actual de insumos
        $this->command->info("\n📦 STOCK ACTUAL DE INSUMOS:");
        $stocksInsumosFinal = DB::table('almacen_item')
            ->join('items', 'almacen_item.id_item', '=', 'items.id_item')
            ->where('items.tipo_item', 'insumo')
            ->where('almacen_item.id_almacen', $almacenInsumos->id_almacen)
            ->select('items.nombre', 'almacen_item.stock', 'items.unidad_medida')
            ->orderBy('items.nombre')
            ->get();
        
        foreach ($stocksInsumosFinal as $stock) {
            $advertencia = $stock->stock < 500 ? "⚠️" : "✓";
            $this->command->info("   {$advertencia} {$stock->nombre}: " . number_format($stock->stock, 0) . " {$stock->unidad_medida}");
        }
        
        // Mostrar lotes activos por producto
        $this->command->info("\n📦 LOTES ACTIVOS POR PRODUCTO (PEPS):");
        $lotesActivos = DB::table('lotes_inventario')
            ->join('items', 'lotes_inventario.id_item', '=', 'items.id_item')
            ->where('items.tipo_item', 'producto')
            ->where('lotes_inventario.estado', 'disponible')
            ->where('lotes_inventario.cantidad_disponible', '>', 0)
            ->select('items.nombre', 'lotes_inventario.cantidad_disponible', 'lotes_inventario.precio_unitario', 'lotes_inventario.fecha_entrada', 'lotes_inventario.fecha_salida')
            ->orderBy('items.nombre')
            ->orderBy('lotes_inventario.fecha_entrada')
            ->get();
        
        if ($lotesActivos->isNotEmpty()) {
            foreach ($lotesActivos as $lote) {
                $vencimiento = $lote->fecha_salida ? Carbon::parse($lote->fecha_salida)->format('d/m/Y') : 'N/A';
                $this->command->info("   - {$lote->nombre}: {$lote->cantidad_disponible} unidades | Precio: \${$lote->precio_unitario} | Vence: {$vencimiento}");
            }
        } else {
            $this->command->info("   No hay lotes activos de productos");
        }
        
        // Mostrar movimientos de inventario por producción
        $totalMovimientos = DB::table('movimientos_inventario')
            ->where('referencia_tipo', 'produccion')
            ->count();
        
        $totalLotes = DB::table('lotes_inventario')
            ->where('referencia_tipo', 'produccion')
            ->count();
        
        $totalDetalles = DB::table('detalle_produccion')->count();
        
        $this->command->info("\n📊 ESTADÍSTICAS DE BASE DE DATOS:");
        $this->command->info("   - Detalles de producción: {$totalDetalles}");
        $this->command->info("   - Movimientos de inventario: {$totalMovimientos}");
        $this->command->info("   - Lotes creados: {$totalLotes}");
        
        $this->command->info("\n" . str_repeat("=", 70));
        $this->command->info("✅ SEEDER DE PRODUCCIONES COMPLETADO CON ÉXITO!");
        $this->command->info(str_repeat("=", 70));
    }
    
    /**
     * Procesar una producción individual
     */
    private function procesarProduccion($prodData, $productos, $recetasPorProducto, $detallesPorReceta, $almacenInsumos, $almacenProductos, $almacenCentral)
    {
        // Buscar el producto
        $producto = null;
        foreach ($productos as $p) {
            if ($p->nombre === $prodData['producto_nombre']) {
                $producto = $p;
                break;
            }
        }
        
        if (!$producto) {
            $this->command->warn("  ⚠️ Producto '{$prodData['producto_nombre']}' no encontrado");
            return ['estado' => $prodData['estado'], 'ingreso_cantidad' => 0, 'egreso_cantidad' => 0];
        }
        
        // Verificar si tiene receta
        if (!isset($recetasPorProducto[$producto->id_producto])) {
            $this->command->warn("  ⚠️ {$producto->nombre} no tiene receta asociada");
            return ['estado' => $prodData['estado'], 'ingreso_cantidad' => 0, 'egreso_cantidad' => 0];
        }
        
        $receta = $recetasPorProducto[$producto->id_producto];
        $detallesReceta = $detallesPorReceta[$receta->id_receta] ?? collect();
        
        if ($detallesReceta->isEmpty()) {
            $this->command->warn("  ⚠️ Receta de {$producto->nombre} sin detalles");
            return ['estado' => $prodData['estado'], 'ingreso_cantidad' => 0, 'egreso_cantidad' => 0];
        }
        
        $cantidadProducida = $prodData['cantidad'];
        $fecha = $prodData['fecha'];
        $estado = $prodData['estado'];
        $solicita = $prodData['solicita'];
        $autoriza = $prodData['autoriza'];
        $observaciones = $prodData['observaciones'];
        $diasVencimiento = $prodData['dias_vencimiento'];
        
        // Crear producción
        $produccionId = DB::table('producciones')->insertGetId([
            'fecha_produccion' => $fecha,
            'cantidad_producida' => $cantidadProducida,
            'id_empleado_solicita' => $solicita->id_empleado,
            'id_empleado_autoriza' => $autoriza ? $autoriza->id_empleado : null,
            'estado' => $estado,
            'fecha_solicitud' => $fecha->copy()->subHours(rand(1, 48)),
            'fecha_autorizacion' => $autoriza ? $fecha->copy()->subHours(rand(0, 24)) : null,
            'observaciones' => $observaciones,
            'created_at' => $fecha,
            'updated_at' => now(),
        ]);
        
        $this->command->info("  📝 Producción #{$produccionId}: {$producto->nombre} x {$cantidadProducida} - {$estado}");
        
        $totalEgreso = 0;
        $totalIngreso = 0;
        
        // Solo procesar insumos si está aprobado
        if ($estado === 'aprobado') {
            // ==========================================
            // PROCESAR EGRESO DE INSUMOS
            // ==========================================
            $this->command->info("  📦 Consumiendo insumos...");
            
            foreach ($detallesReceta as $detalle) {
                $cantidadRequerida = $detalle->cantidad_requerida * $cantidadProducida;
                
                // Verificar stock disponible
                $stockActual = DB::table('almacen_item')
                    ->where('id_almacen', $almacenInsumos->id_almacen)
                    ->where('id_item', $detalle->id_item)
                    ->value('stock');
                
                // 🔴 CAMBIO IMPORTANTE: Siempre registrar el detalle, incluso si no hay stock
                $cantidadReal = min($stockActual, $cantidadRequerida);
                
                // Registrar detalle de producción (egreso) SIEMPRE
                DB::table('detalle_produccion')->insert([
                    'id_produccion' => $produccionId,
                    'id_detalle_receta' => $detalle->id_detalle_receta,
                    'id_almacen' => $almacenInsumos->id_almacen,
                    'id_item' => $detalle->id_item,
                    'cantidad' => $cantidadRequerida,  // Guardamos la cantidad requerida (no la real)
                    'tipo_movimiento' => 'egreso',
                    'created_at' => $fecha,
                    'updated_at' => now(),
                ]);
                
                if ($stockActual >= $cantidadRequerida) {
                    // Hay suficiente stock, consumir todo
                    DB::table('almacen_item')
                        ->where('id_almacen', $almacenInsumos->id_almacen)
                        ->where('id_item', $detalle->id_item)
                        ->decrement('stock', $cantidadRequerida);
                    
                    $totalEgreso += $cantidadRequerida;
                    
                    // === MÉTODO PEPS: Consumir lotes ===
                    $lotesDisponibles = DB::table('lotes_inventario')
                        ->where('id_almacen', $almacenInsumos->id_almacen)
                        ->where('id_item', $detalle->id_item)
                        ->where('estado', 'disponible')
                        ->where('cantidad_disponible', '>', 0)
                        ->orderBy('fecha_entrada', 'asc')
                        ->get();
                    
                    $cantidadPorConsumir = $cantidadRequerida;
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
                    }
                    
                    // Crear movimiento de inventario (egreso)
                    DB::table('movimientos_inventario')->insert([
                        'tipo_movimiento' => 'egreso',
                        'id_almacen' => $almacenInsumos->id_almacen,
                        'id_item' => $detalle->id_item,
                        'cantidad' => $cantidadRequerida,
                        'precio_unitario' => 0,
                        'costo_total' => 0,
                        'fecha_movimiento' => $fecha,
                        'referencia_id' => $produccionId,
                        'referencia_tipo' => 'produccion',
                        'estado' => 'completado',
                        'observaciones' => "Consumo para producción #{$produccionId}",
                        'created_at' => $fecha,
                        'updated_at' => now(),
                    ]);
                    
                    $this->command->info("    - {$detalle->insumo_nombre}: {$cantidadRequerida} {$detalle->unidad_medida}");
                } else {
                    // Stock insuficiente, advertir pero seguir
                    $this->command->warn("    ⚠️ {$detalle->insumo_nombre}: Stock insuficiente (req: {$cantidadRequerida}, disp: {$stockActual}). Detalle guardado pero sin consumo real.");
                }
            }
            
            // ==========================================
            // PROCESAR INGRESO DEL PRODUCTO
            // ==========================================
            $this->command->info("  📦 Generando producto terminado...");
            
            // Actualizar stock del producto
            DB::table('almacen_item')
                ->where('id_almacen', $almacenProductos->id_almacen)
                ->where('id_item', $producto->id_item)
                ->increment('stock', $cantidadProducida);
            
            $totalIngreso = $cantidadProducida;
            
            // Registrar detalle de producción (ingreso)
            DB::table('detalle_produccion')->insert([
                'id_produccion' => $produccionId,
                'id_detalle_receta' => null,
                'id_almacen' => $almacenProductos->id_almacen,
                'id_item' => $producto->id_item,
                'cantidad' => $cantidadProducida,
                'tipo_movimiento' => 'ingreso',
                'created_at' => $fecha,
                'updated_at' => now(),
            ]);
            
            // Crear lote del producto con fecha de vencimiento
            $fechaVencimiento = $fecha->copy()->addDays($diasVencimiento);
            
            DB::table('lotes_inventario')->insert([
                'id_almacen' => $almacenProductos->id_almacen,
                'id_item' => $producto->id_item,
                'cantidad_inicial' => $cantidadProducida,
                'cantidad_disponible' => $cantidadProducida,
                'precio_unitario' => $producto->precio,
                'fecha_entrada' => $fecha,
                'fecha_salida' => $fechaVencimiento,
                'metodo_valuacion' => 'PEPS',
                'estado' => 'disponible',
                'referencia_id' => $produccionId,
                'referencia_tipo' => 'produccion',
                'created_at' => $fecha,
                'updated_at' => now(),
            ]);
            
            // Crear movimiento de inventario (ingreso)
            DB::table('movimientos_inventario')->insert([
                'tipo_movimiento' => 'ingreso',
                'id_almacen' => $almacenProductos->id_almacen,
                'id_item' => $producto->id_item,
                'cantidad' => $cantidadProducida,
                'precio_unitario' => $producto->precio,
                'costo_total' => $producto->precio * $cantidadProducida,
                'fecha_movimiento' => $fecha,
                'referencia_id' => $produccionId,
                'referencia_tipo' => 'produccion',
                'estado' => 'completado',
                'observaciones' => "Producción #{$produccionId}",
                'created_at' => $fecha,
                'updated_at' => now(),
            ]);
            
            $this->command->info("    + {$producto->nombre}: {$cantidadProducida} {$producto->unidad_medida} (vence: {$fechaVencimiento->format('d/m/Y')})");
        }
        
        return [
            'estado' => $estado,
            'ingreso_cantidad' => $totalIngreso,
            'egreso_cantidad' => $totalEgreso
        ];
    }
}