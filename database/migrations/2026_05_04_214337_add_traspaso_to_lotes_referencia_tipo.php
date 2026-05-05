<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE lotes_inventario MODIFY COLUMN referencia_tipo ENUM('compra', 'produccion', 'traspaso') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE lotes_inventario MODIFY COLUMN referencia_tipo ENUM('compra', 'produccion') NULL");
    }
};