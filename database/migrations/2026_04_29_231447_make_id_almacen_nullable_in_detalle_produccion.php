<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_produccion', function (Blueprint $table) {
            // 1. Eliminar la FK compuesta
            $table->dropForeign(['id_almacen', 'id_item']);
            
            // 2. Hacer id_almacen nullable
            $table->unsignedBigInteger('id_almacen')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('detalle_produccion', function (Blueprint $table) {
            $table->unsignedBigInteger('id_almacen')->nullable(false)->change();
            
            // Restaurar FK (solo si no hay NULLs)
            $table->foreign(['id_almacen', 'id_item'])
                  ->references(['id_almacen', 'id_item'])
                  ->on('almacen_item')
                  ->onDelete('restrict');
        });
    }
};