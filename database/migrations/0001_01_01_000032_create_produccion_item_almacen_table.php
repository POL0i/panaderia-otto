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
        Schema::create('produccion_item_almacen', function (Blueprint $table) {
            // FK a producciones
            $table->foreignId('id_produccion')
                  ->constrained('producciones', 'id_produccion')
                  ->onDelete('cascade');
            
            // Referencias a almacen_item
            $table->foreignId('id_almacen');
            $table->foreignId('id_item');
            
            $table->integer('cantidad');
            $table->enum('tipo_movimiento', ['ingreso', 'egreso']);
            $table->timestamps();
            
            // Primaria compuesta
            $table->primary(['id_produccion', 'id_almacen', 'id_item']);
            
            // Foreign key compuesta hacia almacen_item
            $table->foreign(['id_almacen', 'id_item'])
                  ->references(['id_almacen', 'id_item'])
                  ->on('almacen_item')
                  ->onDelete('restrict');
            
            // Índice para consultas rápidas por producción
            $table->index(['id_produccion', 'tipo_movimiento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produccion_item_almacen');
    }
};