<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_venta', function (Blueprint $table) {
        $table->id('id_detalle_venta');  // ← Clave primaria autoincremental

        $table->foreignId('id_nota_venta')
            ->constrained('notas_venta', 'id_nota_venta')
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

        // Índice único para evitar duplicados (reemplaza la clave compuesta)
        $table->unique(['id_nota_venta', 'id_almacen', 'id_item'], 'detalles_venta_unique');

        // FK compuesta hacia almacen_item
        $table->foreign(['id_almacen', 'id_item'])
            ->references(['id_almacen', 'id_item'])
            ->on('almacen_item')
            ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_venta');
    }
};