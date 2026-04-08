<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detalle_receta', function (Blueprint $table) {
            $table->id('id_detalle_receta');
            $table->foreignId('id_receta')->constrained('recetas', 'id_receta')->onDelete('cascade');
            $table->foreignId('id_insumo')->constrained('insumos', 'id_insumo')->onDelete('cascade');
            $table->integer('cantidad_requerida');
            $table->timestamps();
            
            // Evitar duplicados: una receta no puede tener el mismo insumo dos veces
            $table->unique(['id_receta', 'id_insumo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_receta');
    }
};
