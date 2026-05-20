<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsProductosInsumosSeeder extends Seeder
{
    public function run(): void
    {
        // Deshabilitar foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Limpiar tablas en orden inverso (las que tienen FK primero)
        DB::table('productos')->truncate();
        DB::table('insumos')->truncate();
        DB::table('items')->truncate();
        DB::table('categoria_producto')->truncate();
        DB::table('categoria_insumo')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ==========================================
        // 1. CATEGORÍAS DE PRODUCTOS
        // ==========================================
        DB::table('categoria_producto')->insert([
            [
                'nombre' => 'Pan Dulce',
                'descripcion' => 'Panes dulces tradicionales',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Pan Salado',
                'descripcion' => 'Panes salados y empanadas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Pastelería',
                'descripcion' => 'Pasteles, tartas y postres',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Galletas',
                'descripcion' => 'Galletas y bizcochos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ==========================================
        // 2. CATEGORÍAS DE INSUMOS
        // ==========================================
        DB::table('categoria_insumo')->insert([
            [
                'nombre' => 'Harinas',
                'descripcion' => 'Harinas de trigo, maíz, etc.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Lácteos',
                'descripcion' => 'Leche, mantequilla, crema',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Huevos',
                'descripcion' => 'Huevos frescos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Azúcares',
                'descripcion' => 'Azúcar, miel, edulcorantes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Levaduras',
                'descripcion' => 'Levadura fresca y seca',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ==========================================
        // 3. ITEMS (productos e insumos)
        // ==========================================
        DB::table('items')->insert([
            // ----- PRODUCTOS (tipo_item = 'producto') -----
            [
                'tipo_item' => 'producto',
                'nombre' => 'Pan de Muerto',
                'unidad_medida' => 'pieza',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_item' => 'producto',
                'nombre' => 'Concha',
                'unidad_medida' => 'pieza',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_item' => 'producto',
                'nombre' => 'Bolillo',
                'unidad_medida' => 'pieza',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_item' => 'producto',
                'nombre' => 'Pastel de Chocolate',
                'unidad_medida' => 'porción',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_item' => 'producto',
                'nombre' => 'Galleta María',
                'unidad_medida' => 'pieza',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ----- INSUMOS (tipo_item = 'insumo') -----
            [
                'tipo_item' => 'insumo',
                'nombre' => 'Harina de Trigo',
                'unidad_medida' => 'kg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_item' => 'insumo',
                'nombre' => 'Mantequilla',
                'unidad_medida' => 'kg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_item' => 'insumo',
                'nombre' => 'Huevo',
                'unidad_medida' => 'pieza',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_item' => 'insumo',
                'nombre' => 'Azúcar Estándar',
                'unidad_medida' => 'kg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_item' => 'insumo',
                'nombre' => 'Levadura Seca',
                'unidad_medida' => 'sobre',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ==========================================
        // 4. PRODUCTOS (relaciona items + categoria_producto)
        // Los id_item deben coincidir con los items de tipo 'producto'
        // ==========================================
        DB::table('productos')->insert([
            [
                'id_item' => 1, // Pan de Muerto
                'id_cat_producto' => 1, // Pan Dulce
                'precio' => 25.50,
                'imagen' => 'https://cdn.pixabay.com/photo/2023/11/15/21/15/bread-8391079_1280.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_item' => 2, // Concha
                'id_cat_producto' => 1, // Pan Dulce
                'precio' => 12.00,
                'imagen' => 'https://cdn.pixabay.com/photo/2021/01/14/13/10/bread-5916804_1280.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_item' => 3, // Bolillo
                'id_cat_producto' => 2, // Pan Salado
                'precio' => 4.50,
                'imagen' => 'https://cdn.pixabay.com/photo/2019/02/07/21/19/bobbin-lace-3982200_1280.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_item' => 4, // Pastel de Chocolate
                'id_cat_producto' => 3, // Pastelería
                'precio' => 85.00,
                'imagen' => 'https://cdn.pixabay.com/photo/2016/11/22/18/52/cake-1850011_1280.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_item' => 5, // Galleta María
                'id_cat_producto' => 4, // Galletas
                'precio' => 8.00,
                'imagen' => 'https://cdn.pixabay.com/photo/2014/11/27/14/35/cookies-547636_1280.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ==========================================
        // 5. INSUMOS (relaciona items + categoria_insumo)
        // Los id_item son los de tipo 'insumo' (ids 6 al 10)
        // ==========================================
        DB::table('insumos')->insert([
            [
                'id_item' => 6, // Harina de Trigo
                'id_cat_insumo' => 1, // Harinas
                'precio_compra' => 18.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_item' => 7, // Mantequilla
                'id_cat_insumo' => 2, // Lácteos
                'precio_compra' => 85.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_item' => 8, // Huevo
                'id_cat_insumo' => 3, // Huevos
                'precio_compra' => 32.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_item' => 9, // Azúcar Estándar
                'id_cat_insumo' => 4, // Azúcares
                'precio_compra' => 22.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_item' => 10, // Levadura Seca
                'id_cat_insumo' => 5, // Levaduras
                'precio_compra' => 15.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✓ Items, productos e insumos seedeados correctamente:');
        $this->command->info('  - Categorías de productos: ' . DB::table('categoria_producto')->count());
        $this->command->info('  - Categorías de insumos: ' . DB::table('categoria_insumo')->count());
        $this->command->info('  - Items: ' . DB::table('items')->count());
        $this->command->info('  - Productos: ' . DB::table('productos')->count());
        $this->command->info('  - Insumos: ' . DB::table('insumos')->count());
    }
}