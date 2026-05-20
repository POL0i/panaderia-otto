<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlmacenesRecetasSeeder extends Seeder
{
    public function run(): void
    {
        // Deshabilitar foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Limpiar tablas en orden inverso (dependencias)
        DB::table('detalle_receta')->truncate();
        DB::table('recetas')->truncate();
        DB::table('almacenes')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ==========================================
        // 1. ALMACENES
        // ==========================================
        DB::table('almacenes')->insert([
            [
                'nombre' => 'Almacén Central',
                'ubicacion' => 'Zona Norte',
                'capacidad' => 5000,
                'tipo_almacen' => 'mixto',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Almacén Insumos',
                'ubicacion' => 'Zona Sur',
                'capacidad' => 3000,
                'tipo_almacen' => 'insumo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Almacén Productos',
                'ubicacion' => 'Zona Este',
                'capacidad' => 2000,
                'tipo_almacen' => 'producto',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Almacén Refrigerado',
                'ubicacion' => 'Zona Oeste',
                'capacidad' => 1500,
                'tipo_almacen' => 'mixto',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ==========================================
        // 2. RECETAS (asociadas a productos existentes)
        // id_producto: 1 (Pan de Muerto), 2 (Concha), 3 (Bolillo), 4 (Pastel Chocolate), 5 (Galleta María)
        // ==========================================
        DB::table('recetas')->insert([
            [
                'id_producto' => 1, // Pan de Muerto
                'nombre' => 'Receta Pan de Muerto',
                'descripcion' => 'Pan tradicional de temporada',
                'cantidad_requerida' => '1 pieza',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_producto' => 2, // Concha
                'nombre' => 'Receta Concha',
                'descripcion' => 'Pan dulce con cubierta',
                'cantidad_requerida' => '1 pieza',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_producto' => 3, // Bolillo
                'nombre' => 'Receta Bolillo',
                'descripcion' => 'Pan salado blanco',
                'cantidad_requerida' => '1 pieza',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_producto' => 4, // Pastel Chocolate
                'nombre' => 'Receta Pastel Chocolate',
                'descripcion' => 'Pastel de 8 porciones',
                'cantidad_requerida' => '8 porciones',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_producto' => 5, // Galleta María
                'nombre' => 'Receta Galleta María',
                'descripcion' => 'Galleta tipo María',
                'cantidad_requerida' => '1 pieza',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ==========================================
        // 3. DETALLE RECETA (insumos necesarios por receta)
        // Insumos IDs: 1 (Harina), 2 (Mantequilla), 3 (Huevo), 4 (Azúcar), 5 (Levadura)
        // ==========================================
        // Receta 1: Pan de Muerto
        DB::table('detalle_receta')->insert([
            [
                'id_receta' => 1,
                'id_insumo' => 1, // Harina
                'cantidad_requerida' => 500, // gramos
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 1,
                'id_insumo' => 2, // Mantequilla
                'cantidad_requerida' => 150,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 1,
                'id_insumo' => 3, // Huevo
                'cantidad_requerida' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 1,
                'id_insumo' => 4, // Azúcar
                'cantidad_requerida' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 1,
                'id_insumo' => 5, // Levadura
                'cantidad_requerida' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Receta 2: Concha
        DB::table('detalle_receta')->insert([
            [
                'id_receta' => 2,
                'id_insumo' => 1,
                'cantidad_requerida' => 400,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 2,
                'id_insumo' => 2,
                'cantidad_requerida' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 2,
                'id_insumo' => 3,
                'cantidad_requerida' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 2,
                'id_insumo' => 4,
                'cantidad_requerida' => 120,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 2,
                'id_insumo' => 5,
                'cantidad_requerida' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Receta 3: Bolillo
        DB::table('detalle_receta')->insert([
            [
                'id_receta' => 3,
                'id_insumo' => 1,
                'cantidad_requerida' => 300,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 3,
                'id_insumo' => 2,
                'cantidad_requerida' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 3,
                'id_insumo' => 3,
                'cantidad_requerida' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 3,
                'id_insumo' => 4,
                'cantidad_requerida' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 3,
                'id_insumo' => 5,
                'cantidad_requerida' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Receta 4: Pastel de Chocolate
        DB::table('detalle_receta')->insert([
            [
                'id_receta' => 4,
                'id_insumo' => 1,
                'cantidad_requerida' => 800,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 4,
                'id_insumo' => 2,
                'cantidad_requerida' => 300,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 4,
                'id_insumo' => 3,
                'cantidad_requerida' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 4,
                'id_insumo' => 4,
                'cantidad_requerida' => 400,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 4,
                'id_insumo' => 5,
                'cantidad_requerida' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Receta 5: Galleta María
        DB::table('detalle_receta')->insert([
            [
                'id_receta' => 5,
                'id_insumo' => 1,
                'cantidad_requerida' => 250,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 5,
                'id_insumo' => 2,
                'cantidad_requerida' => 80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 5,
                'id_insumo' => 3,
                'cantidad_requerida' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 5,
                'id_insumo' => 4,
                'cantidad_requerida' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_receta' => 5,
                'id_insumo' => 5,
                'cantidad_requerida' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✓ Almacenes y recetas seedeados correctamente:');
        $this->command->info('  - Almacenes: ' . DB::table('almacenes')->count());
        $this->command->info('  - Recetas: ' . DB::table('recetas')->count());
        $this->command->info('  - Detalle receta: ' . DB::table('detalle_receta')->count());
    }
}