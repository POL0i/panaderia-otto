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
            $table->id('id_detalle_produccion');
            $table->foreignId('id_produccion')->constrained('producciones', 'id_produccion')->onDelete('cascade');
            $table->foreignId('id_detalle_receta')->constrained('detalle_receta', 'id_detalle_receta');
            $table->foreignId('id_almacen');
            $table->foreignId('id_item');
            $table->integer('cantidad');
            $table->enum('tipo_movimiento', ['ingreso', 'egreso']);
            $table->timestamps();

            $table->foreign(['id_almacen', 'id_item'])
                ->references(['id_almacen', 'id_item'])
                ->on('almacen_item')
                ->onDelete('restrict');

            $table->index(['id_produccion', 'tipo_movimiento']);
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
