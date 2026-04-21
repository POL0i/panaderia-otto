<?php
// database/migrations/xxxx_create_detalles_compra_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_compra', function (Blueprint $table) {
            // ID autoincremental como clave primaria
            $table->id('id_detalle_compra');
            
            $table->foreignId('id_nota_compra')
                  ->constrained('notas_compra', 'id_nota_compra')
                  ->onDelete('cascade');
            $table->foreignId('id_almacen')
                  ->constrained('almacenes', 'id_almacen')
                  ->onDelete('restrict');
            $table->foreignId('id_item')
                  ->constrained('items', 'id_item')
                  ->onDelete('restrict');
            $table->integer('cantidad');
            $table->decimal('precio', 10, 2);
            $table->decimal('subtotal', 12, 2)->storedAs('cantidad * precio');
            $table->timestamps();
            
            // Índice único para evitar duplicados (reemplaza la clave primaria compuesta)
            $table->unique(['id_nota_compra', 'id_almacen', 'id_item'], 'detalles_compra_unique');
            
            // Foreign key compuesta hacia almacen_item
            $table->foreign(['id_almacen', 'id_item'])
                  ->references(['id_almacen', 'id_item'])
                  ->on('almacen_item')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_compra');
    }
};