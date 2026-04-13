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
        Schema::create('traspasos_inventario', function (Blueprint $table) {
            $table->id('id_traspaso');
            $table->unsignedBigInteger('id_almacen_origen');
            $table->unsignedBigInteger('id_almacen_destino');
            $table->unsignedBigInteger('id_item');
            $table->decimal('cantidad', 12, 2);
            $table->decimal('precio_unitario', 12, 2);
            $table->dateTime('fecha_traspaso');
            $table->enum('estado', ['completado', 'pendiente', 'cancelado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_almacen_origen')->references('id_almacen')->on('almacenes')->onDelete('restrict');
            $table->foreign('id_almacen_destino')->references('id_almacen')->on('almacenes')->onDelete('restrict');
            $table->foreign('id_item')->references('id_item')->on('items')->onDelete('restrict');

            // Índices
            $table->index(['id_almacen_origen', 'id_almacen_destino']);
            $table->index('id_item');
            $table->index('estado');
            $table->index('fecha_traspaso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traspasos_inventario');
    }
};
