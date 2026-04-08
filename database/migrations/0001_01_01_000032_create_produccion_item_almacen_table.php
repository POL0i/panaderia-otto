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
            $table->foreignId('id_produccion')->constrained('producciones', 'id_produccion')->onDelete('cascade');
            $table->foreignId('id_item')->constrained('items', 'id_item')->onDelete('cascade');
            $table->foreignId('id_almacen')->constrained('almacenes', 'id_almacen')->onDelete('cascade');
            $table->integer('cantidad');
            $table->string('tipo_movimiento', 20); // ingreso/egreso
            $table->timestamps();
            
            // Primaria compuesta
            $table->primary(['id_produccion', 'id_item', 'id_almacen']);
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
