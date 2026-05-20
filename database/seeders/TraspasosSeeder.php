<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TraspasosSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('traspaso_almacen_item')->delete();
        DB::table('traspasos')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info("\n" . str_repeat("=", 70));
        $this->command->info("🔄 INICIANDO TRASPASOS ENTRE ALMACENES");
        $this->command->info(str_repeat("=", 70));

        // Empleado
        $empleado = DB::table('empleados')->where('nombre', 'Mario')->where('apellido', 'López')->first();
        if (!$empleado) $empleado = DB::table('empleados')->first();

        // Almacenes
        $almacenInsumos = DB::table('almacenes')->where('nombre', 'Almacén Insumos')->first();
        if (!$almacenInsumos) $almacenInsumos = DB::table('almacenes')->where('tipo_almacen', 'insumo')->first();
        $almacenCentral = DB::table('almacenes')->where('nombre', 'Almacén Central')->first();
        if (!$almacenCentral) $almacenCentral = DB::table('almacenes')->first();
        $almacenRefrigerado = DB::table('almacenes')->where('nombre', 'Almacén Refrigerado')->first();
        if (!$almacenRefrigerado) $almacenRefrigerado = DB::table('almacenes')->skip(1)->first();
        $almacenProductos = DB::table('almacenes')->where('tipo_almacen', 'producto')->first();
        if (!$almacenProductos) $almacenProductos = $almacenCentral;

        // Items
        $itemsMap = [];
        $itemsInsumos = DB::table('items')->where('tipo_item', 'insumo')->get();
        $itemsProductos = DB::table('items')->where('tipo_item', 'producto')->get();
        foreach ($itemsInsumos as $item) $itemsMap[$item->nombre] = $item;
        foreach ($itemsProductos as $item) $itemsMap[$item->nombre] = $item;

        // Traspasos con descripciones cortas (<=40 caracteres)
        $traspasos = [
            [
                'fecha' => Carbon::create(2026, 1, 10, 9, 0, 0),
                'empleado' => $empleado,
                'descripcion' => 'Harina y azúcar a insumos',
                'detalles' => [
                    ['item' => 'Harina de Trigo', 'origen' => $almacenCentral, 'destino' => $almacenInsumos, 'cantidad' => 200], // antes 500
                    ['item' => 'Azúcar Estándar', 'origen' => $almacenCentral, 'destino' => $almacenInsumos, 'cantidad' => 100], // antes 200
                ],
            ],
            [
                'fecha' => Carbon::create(2026, 1, 20, 10, 30, 0),
                'empleado' => $empleado,
                'descripcion' => 'Refrigerados a cámara',
                'detalles' => [
                    ['item' => 'Mantequilla', 'origen' => $almacenCentral, 'destino' => $almacenRefrigerado, 'cantidad' => 150],
                    ['item' => 'Huevo', 'origen' => $almacenCentral, 'destino' => $almacenRefrigerado, 'cantidad' => 500],
                ],
            ],
            [
                'fecha' => Carbon::create(2026, 2, 5, 11, 15, 0),
                'empleado' => $empleado,
                'descripcion' => 'Levadura a central',
                'detalles' => [
                    ['item' => 'Levadura Seca', 'origen' => $almacenInsumos, 'destino' => $almacenCentral, 'cantidad' => 100],
                ],
            ],
            [
                'fecha' => Carbon::create(2026, 2, 18, 8, 45, 0),
                'empleado' => $empleado,
                'descripcion' => 'Productos a almacén ventas',
                'detalles' => [
                    ['item' => 'Pan de Muerto', 'origen' => $almacenCentral, 'destino' => $almacenProductos, 'cantidad' => 200],
                    ['item' => 'Concha', 'origen' => $almacenCentral, 'destino' => $almacenProductos, 'cantidad' => 500],
                    ['item' => 'Bolillo', 'origen' => $almacenCentral, 'destino' => $almacenProductos, 'cantidad' => 1000],
                ],
            ],
            [
                'fecha' => Carbon::create(2026, 3, 10, 14, 0, 0),
                'empleado' => $empleado,
                'descripcion' => 'Galletas a ventas',
                'detalles' => [
                    ['item' => 'Galleta María', 'origen' => $almacenCentral, 'destino' => $almacenProductos, 'cantidad' => 800],
                ],
            ],
            [
                'fecha' => Carbon::create(2026, 4, 12, 9, 30, 0),
                'empleado' => $empleado,
                'descripcion' => 'Mantequilla/huevo a refrigerado',
                'detalles' => [
                    ['item' => 'Mantequilla', 'origen' => $almacenInsumos, 'destino' => $almacenRefrigerado, 'cantidad' => 80],
                    ['item' => 'Huevo', 'origen' => $almacenInsumos, 'destino' => $almacenRefrigerado, 'cantidad' => 300],
                ],
            ],
            [
                'fecha' => Carbon::create(2026, 5, 20, 12, 0, 0),
                'empleado' => $empleado,
                'descripcion' => 'Pasteles a central',
                'detalles' => [
                    ['item' => 'Pastel de Chocolate', 'origen' => $almacenProductos, 'destino' => $almacenCentral, 'cantidad' => 30],
                ],
            ],
            [
                'fecha' => Carbon::create(2026, 6, 5, 7, 15, 0),
                'empleado' => $empleado,
                'descripcion' => 'Harina especial a insumos',
                'detalles' => [
                    ['item' => 'Harina de Trigo', 'origen' => $almacenCentral, 'destino' => $almacenInsumos, 'cantidad' => 300],
                ],
            ],
            [
                'fecha' => Carbon::create(2026, 7, 8, 10, 0, 0),
                'empleado' => $empleado,
                'descripcion' => 'Azúcar a insumos',
                'detalles' => [
                    ['item' => 'Azúcar Estándar', 'origen' => $almacenCentral, 'destino' => $almacenInsumos, 'cantidad' => 150],
                ],
            ],
            [
                'fecha' => Carbon::create(2026, 12, 1, 8, 0, 0),
                'empleado' => $empleado,
                'descripcion' => 'Productos navideños',
                'detalles' => [
                    ['item' => 'Pan de Muerto', 'origen' => $almacenCentral, 'destino' => $almacenProductos, 'cantidad' => 300],
                    ['item' => 'Pastel de Chocolate', 'origen' => $almacenProductos, 'destino' => $almacenCentral, 'cantidad' => 50],
                ],
            ],
        ];

        $totalTraspasos = 0;
        $totalItems = 0;

        foreach ($traspasos as $data) {
            $result = $this->procesarTraspaso($data, $itemsMap);
            if ($result['success']) {
                $totalTraspasos++;
                $totalItems += $result['items'];
            } else {
                $this->command->warn("  ❌ Error: {$result['error']}");
            }
        }

        // Reporte final...
        $this->command->info("\n✅ Traspasos completados: $totalTraspasos, items movidos: $totalItems");
    }

    private function procesarTraspaso($data, $itemsMap)
    {
        $fecha = $data['fecha'];
        $empleado = $data['empleado'];
        $descripcion = $data['descripcion'];
        $detalles = $data['detalles'];

        // Verificar stock
        foreach ($detalles as $det) {
            $item = $itemsMap[$det['item']] ?? null;
            if (!$item) return ['success' => false, 'error' => "Item {$det['item']} no existe"];
            $stock = DB::table('almacen_item')
                ->where('id_almacen', $det['origen']->id_almacen)
                ->where('id_item', $item->id_item)
                ->value('stock');
            if ($stock < $det['cantidad']) {
                return ['success' => false, 'error' => "Stock insuficiente de {$item->nombre} en {$det['origen']->nombre}"];
            }
        }

        $traspasoId = DB::table('traspasos')->insertGetId([
            'fecha_traspaso' => $fecha,
            'descripcion' => $descripcion,
            'id_empleado' => $empleado->id_empleado,
            'created_at' => $fecha,
            'updated_at' => now(),
        ]);

        $this->command->info("  📝 Traspaso #{$traspasoId}: {$descripcion}");
        $itemsMovidos = 0;

        foreach ($detalles as $det) {
            $item = $itemsMap[$det['item']];
            $origen = $det['origen'];
            $destino = $det['destino'];
            $cantidad = $det['cantidad'];

            // Registrar detalle
            DB::table('traspaso_almacen_item')->insert([
                'id_traspaso' => $traspasoId,
                'id_almacen_origen' => $origen->id_almacen,
                'id_item' => $item->id_item,
                'id_almacen_destino' => $destino->id_almacen,
                'cantidad' => $cantidad,
                'created_at' => $fecha,
                'updated_at' => now(),
            ]);

            // Actualizar stock
            DB::table('almacen_item')
                ->where('id_almacen', $origen->id_almacen)
                ->where('id_item', $item->id_item)
                ->decrement('stock', $cantidad);

            $existe = DB::table('almacen_item')
                ->where('id_almacen', $destino->id_almacen)
                ->where('id_item', $item->id_item)
                ->exists();
            if (!$existe) {
                DB::table('almacen_item')->insert([
                    'id_almacen' => $destino->id_almacen,
                    'id_item' => $item->id_item,
                    'stock' => 0,
                    'created_at' => $fecha,
                    'updated_at' => now(),
                ]);
            }
            DB::table('almacen_item')
                ->where('id_almacen', $destino->id_almacen)
                ->where('id_item', $item->id_item)
                ->increment('stock', $cantidad);

            // === PEPS: consumir lotes del origen ===
            $lotesOrigen = DB::table('lotes_inventario')
                ->where('id_almacen', $origen->id_almacen)
                ->where('id_item', $item->id_item)
                ->where('estado', 'disponible')
                ->where('cantidad_disponible', '>', 0)
                ->orderBy('fecha_entrada')
                ->get();

            $porMover = $cantidad;
            $lotesConsumidos = [];
            foreach ($lotesOrigen as $lote) {
                if ($porMover <= 0) break;
                $consumir = min($lote->cantidad_disponible, $porMover);
                $nueva = $lote->cantidad_disponible - $consumir;
                DB::table('lotes_inventario')
                    ->where('id_lote', $lote->id_lote)
                    ->update([
                        'cantidad_disponible' => $nueva,
                        'estado' => $nueva == 0 ? 'consumido' : 'disponible',
                        'updated_at' => now(),
                    ]);
                $lotesConsumidos[] = ['lote' => $lote, 'consumido' => $consumir];
                $porMover -= $consumir;
            }

            // Crear nuevos lotes en destino (usando 'ajuste' como referencia_tipo)
            foreach ($lotesConsumidos as $lc) {
                $loteOrig = $lc['lote'];
                $cantTrasp = $lc['consumido'];
               // Dentro del método procesarTraspaso, al crear el lote en destino:
                    DB::table('lotes_inventario')->insert([
                        'id_almacen' => $destino->id_almacen,
                        'id_item' => $item->id_item,
                        'cantidad_inicial' => $cantTrasp,
                        'cantidad_disponible' => $cantTrasp,
                        'precio_unitario' => $loteOrig->precio_unitario,
                        'fecha_entrada' => $fecha,
                        'fecha_salida' => $loteOrig->fecha_salida,
                        'metodo_valuacion' => 'PEPS',
                        'estado' => 'disponible',
                        'referencia_id' => $traspasoId,
                        'referencia_tipo' => 'compra', // Cambiado de 'ajuste' a 'inicial'
                        'created_at' => $fecha,
                        'updated_at' => now(),
                    ]);
            }

            // Movimientos de inventario (usar 'traspaso' que sí existe en movimientos)
            DB::table('movimientos_inventario')->insert([
                'tipo_movimiento' => 'traspaso_origen',
                'id_almacen' => $origen->id_almacen,
                'id_item' => $item->id_item,
                'cantidad' => $cantidad,
                'precio_unitario' => 0,
                'costo_total' => 0,
                'fecha_movimiento' => $fecha,
                'referencia_id' => $traspasoId,
                'referencia_tipo' => 'traspaso',
                'estado' => 'completado',
                'observaciones' => "Traspaso a {$destino->nombre}",
                'created_at' => $fecha,
                'updated_at' => now(),
            ]);
            DB::table('movimientos_inventario')->insert([
                'tipo_movimiento' => 'traspaso_destino',
                'id_almacen' => $destino->id_almacen,
                'id_item' => $item->id_item,
                'cantidad' => $cantidad,
                'precio_unitario' => 0,
                'costo_total' => 0,
                'fecha_movimiento' => $fecha,
                'referencia_id' => $traspasoId,
                'referencia_tipo' => 'traspaso',
                'estado' => 'completado',
                'observaciones' => "Traspaso desde {$origen->nombre}",
                'created_at' => $fecha,
                'updated_at' => now(),
            ]);

            $itemsMovidos++;
            $this->command->info("    - {$item->nombre}: {$cantidad} de {$origen->nombre} → {$destino->nombre}");
        }

        return ['success' => true, 'items' => $itemsMovidos];
    }
}