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
        Schema::create('producciones', function (Blueprint $table) {
            $table->id('id_produccion');
            $table->date('fecha_produccion');
            $table->integer('cantidad_producida');
            $table->foreignId('id_receta')->constrained('recetas', 'id_receta')->onDelete('cascade');
            $table->foreignId('id_empleado')->constrained('empleados', 'id_empleado')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producciones');
    }
};
