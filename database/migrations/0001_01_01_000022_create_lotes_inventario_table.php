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
        Schema::create('lotes_inventario', function (Blueprint $table) {
            $table->id('id_lote');
            $table->unsignedBigInteger('id_almacen');
            $table->unsignedBigInteger('id_item');
            $table->decimal('cantidad_inicial', 12, 2);
            $table->decimal('cantidad_disponible', 12, 2);
            $table->decimal('precio_unitario', 12, 2);
            $table->dateTime('fecha_entrada');
            $table->dateTime('fecha_salida')->nullable();
            $table->enum('metodo_valuacion', ['PEPS', 'UEPS'])->default('PEPS');
            $table->enum('estado', ['disponible', 'consumido', 'anulado'])->default('disponible');
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_almacen')->references('id_almacen')->on('almacenes')->onDelete('restrict');
            $table->foreign('id_item')->references('id_item')->on('items')->onDelete('restrict');

            // Índices
            $table->index(['id_almacen', 'id_item']);
            $table->index(['id_item', 'estado']);
            $table->index(['id_item', 'metodo_valuacion']);
            $table->index('fecha_entrada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes_inventario');
    }
};
