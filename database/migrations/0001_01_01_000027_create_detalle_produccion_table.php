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
        Schema::create('detalle_produccion', function (Blueprint $table) {
            $table->foreignId('id_produccion')->constrained('producciones', 'id_produccion')->onDelete('cascade');
            $table->foreignId('id_detalle_receta')->constrained('detalle_receta', 'id_detalle_receta')->onDelete('cascade');
            $table->integer('cantidad_usada');
            $table->timestamps();
            
            // Primaria compuesta
            $table->primary(['id_produccion', 'id_detalle_receta']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_produccion');
    }
};
