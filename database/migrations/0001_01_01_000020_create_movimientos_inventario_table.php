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
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id('id_movimiento');
            $table->enum('tipo_movimiento', ['ingreso', 'egreso', 'traspaso_origen', 'traspaso_destino', 'ajuste']);
            $table->unsignedBigInteger('id_almacen');
            $table->unsignedBigInteger('id_item');
            $table->decimal('cantidad', 12, 2);
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('costo_total', 14, 2);
            $table->dateTime('fecha_movimiento');
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->enum('referencia_tipo', ['compra', 'venta', 'produccion', 'ajuste', 'traspaso'])->nullable();
            $table->enum('estado', ['completado', 'pendiente', 'cancelado'])->default('completado');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Foreing keys
            $table->foreign('id_almacen')->references('id_almacen')->on('almacenes')->onDelete('restrict');
            $table->foreign('id_item')->references('id_item')->on('items')->onDelete('restrict');

            // Índices para búsquedas rápidas
            $table->index(['id_almacen', 'id_item']);
            $table->index('tipo_movimiento');
            $table->index('fecha_movimiento');
            $table->index(['referencia_tipo', 'referencia_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
