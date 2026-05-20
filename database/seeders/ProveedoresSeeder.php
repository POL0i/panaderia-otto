<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedoresSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('ppersona')->truncate();
        DB::table('pempresa')->truncate();
        DB::table('proveedores')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ==========================================
        // 1. Insertar PROVEEDORES
        // ==========================================
        DB::table('proveedores')->insert([
            // Proveedores tipo EMPRESA
            [
                'tipo_proveedor' => 'empresa',
                'telefono' => 5550101,
                'direccion' => 'Av. Principal 123, CDMX',
                'correo' => 'ventas@distribuidora.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_proveedor' => 'empresa',
                'telefono' => 5550102,
                'direccion' => 'Calle Industrial 456, GDL',
                'correo' => 'contacto@insumos.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_proveedor' => 'empresa',
                'telefono' => 5550103,
                'direccion' => 'Blvd. Tecnológico 789, MTY',
                'correo' => 'ventas@tecnologias.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Proveedores tipo PERSONA
            [
                'tipo_proveedor' => 'persona',
                'telefono' => 5550201,
                'direccion' => 'Calle 5 de Mayo 45, Puebla',
                'correo' => 'carlos.martinez@email.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_proveedor' => 'persona',
                'telefono' => 5550202,
                'direccion' => 'Av. Reforma 234, Querétaro',
                'correo' => 'maria.garcia@email.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tipo_proveedor' => 'persona',
                'telefono' => 5550203,
                'direccion' => 'Calle Morelos 78, Cancún',
                'correo' => 'juan.perez@email.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ==========================================
        // 2. Insertar PEMPRESA (con nombres más cortos)
        // ==========================================
        DB::table('pempresa')->insert([
            [
                'id_proveedor' => 1,
                'razon_social' => 'Distribuidora de Insumos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_proveedor' => 2,
                'razon_social' => 'Materiales Industriales',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_proveedor' => 3,
                'razon_social' => 'Tecnologías Avanzadas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ==========================================
        // 3. Insertar PPERSONA
        // ==========================================
        DB::table('ppersona')->insert([
            [
                'id_proveedor' => 4,
                'nombre' => 'Carlos Martínez',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_proveedor' => 5,
                'nombre' => 'María García',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_proveedor' => 6,
                'nombre' => 'Juan Pérez',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✓ Proveedores seedeados correctamente:');
        $this->command->info('  - Proveedores: ' . DB::table('proveedores')->count());
        $this->command->info('  - Empresas: ' . DB::table('pempresa')->count());
        $this->command->info('  - Personas: ' . DB::table('ppersona')->count());
    }
}