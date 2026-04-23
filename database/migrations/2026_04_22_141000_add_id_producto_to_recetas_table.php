<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recetas', function (Blueprint $table) {
            $table->foreignId('id_producto')
                  ->nullable()
                  ->after('cantidad_requerida')
                  ->constrained('productos', 'id_producto')
                  ->onDelete('restrict'); // No permitir borrar un producto si tiene recetas asociadas
        });
    }

    public function down(): void
    {
        Schema::table('recetas', function (Blueprint $table) {
            $table->dropForeign(['id_producto']);
            $table->dropColumn('id_producto');
        });
    }
};