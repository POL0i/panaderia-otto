<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\NotaVenta;
use App\Models\DetalleVenta;
use App\Models\Cliente;
use App\Models\Empleado;
use Carbon\Carbon;

class VentasPruebaSeeder extends Seeder
{
    public function run()
    {
        $cliente = Cliente::first();
        $empleado = Empleado::first();
        
        if (!$cliente) {
            $cliente = Cliente::create([
                'nombre' => 'Cliente Demo',
                'apellido' => 'Prueba',
                'telefono' => '77777777',
            ]);
        }
        
        if (!$empleado) {
            $empleado = Empleado::create([
                'nombre' => 'Empleado',
                'apellido' => 'Demo',
                'telefono' => '77777777',
            ]);
        }
        
        // Verificar almacenes existentes
        $almacenes = DB::table('almacenes')->pluck('id_almacen')->toArray();
        $this->command->info('Almacenes disponibles: ' . implode(', ', $almacenes));
        
        if (empty($almacenes)) {
            $this->command->error('No hay almacenes registrados. Crea al menos un almacén primero.');
            return;
        }
        
        // Usar el primer almacén disponible
        $almacenId = $almacenes[0];
        
        // Verificar items existentes (productos)
        $items = DB::table('items')->where('tipo_item', 'producto')->pluck('id_item')->toArray();
        $this->command->info('Productos disponibles: ' . implode(', ', $items));
        
        if (empty($items)) {
            $this->command->error('No hay productos registrados. Crea al menos un producto primero.');
            return;
        }
        
        $productoId = $items[0];
        
        $ventasConfig = [
            ['dias' => 6, 'monto' => 150.00, 'cantidad' => 2, 'estado' => 'completado'],
            ['dias' => 5, 'monto' => 200.00, 'cantidad' => 1, 'estado' => 'completado'],
            ['dias' => 5, 'monto' => 100.00, 'cantidad' => 5, 'estado' => 'completado'],
            ['dias' => 4, 'monto' => 300.00, 'cantidad' => 2, 'estado' => 'completado'],
            ['dias' => 3, 'monto' => 50.00, 'cantidad' => 2, 'estado' => 'completado'],
            ['dias' => 3, 'monto' => 120.00, 'cantidad' => 1, 'estado' => 'completado'],
            ['dias' => 2, 'monto' => 80.00, 'cantidad' => 4, 'estado' => 'completado'],
            ['dias' => 1, 'monto' => 45.00, 'cantidad' => 1, 'estado' => 'completado'],
            ['dias' => 0, 'monto' => 25.00, 'cantidad' => 1, 'estado' => 'pendiente'],
        ];
        
        foreach ($ventasConfig as $venta) {
            $fecha = Carbon::now()->subDays($venta['dias'])->setTime(rand(8, 20), rand(0, 59));
            $precioUnitario = $venta['monto'] / $venta['cantidad'];
            
            $nota = NotaVenta::create([
                'fecha_venta' => $fecha,
                'monto_total' => $venta['monto'],
                'estado' => $venta['estado'],
                'id_cliente' => $cliente->id_cliente,
                'id_empleado' => $empleado->id_empleado,
                'created_at' => $fecha,
                'updated_at' => $fecha,
            ]);
            
            DetalleVenta::create([
                'id_nota_venta' => $nota->id_nota_venta,
                'id_almacen' => $almacenId,
                'id_item' => $productoId,
                'cantidad' => $venta['cantidad'],
                'precio' => $precioUnitario,
                'created_at' => $fecha,
                'updated_at' => $fecha,
            ]);
            
            $this->command->info("Venta creada: {$fecha->toDateString()} - Bs. {$venta['monto']}");
        }
        
        $this->command->info('Seeder completado!');
    }
}