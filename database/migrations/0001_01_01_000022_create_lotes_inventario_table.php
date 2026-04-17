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
            
            // Referencia directa a almacen_item (NO por separado)
            $table->foreignId('id_almacen');
            $table->foreignId('id_item');
            
            $table->decimal('cantidad_inicial', 12, 2);
            $table->decimal('cantidad_disponible', 12, 2);
            $table->decimal('precio_unitario', 12, 2);
            $table->dateTime('fecha_entrada');
            $table->dateTime('fecha_salida')->nullable();
            $table->enum('metodo_valuacion', ['PEPS', 'UEPS'])->default('PEPS');
            $table->enum('estado', ['disponible', 'consumido', 'anulado'])->default('disponible');
            
            // Referencia al origen del lote
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->enum('referencia_tipo', ['compra', 'produccion', 'ajuste', 'inicial'])->nullable();
            
            $table->timestamps();

            // Foreign key compuesta hacia almacen_item
            $table->foreign(['id_almacen', 'id_item'])
                  ->references(['id_almacen', 'id_item'])
                  ->on('almacen_item')
                  ->onDelete('restrict');

            // Índices optimizados
            $table->index(['id_almacen', 'id_item', 'estado']);
            $table->index(['id_item', 'metodo_valuacion', 'estado']);
            $table->index('fecha_entrada');
            $table->index(['referencia_tipo', 'referencia_id']);
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