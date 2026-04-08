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
        Schema::create('detalles_compra', function (Blueprint $table) {
            $table->foreignId('id_nota_compra')->constrained('notas_compra', 'id_nota_compra')->onDelete('cascade');
            $table->foreignId('id_insumo')->constrained('insumos', 'id_insumo')->onDelete('cascade');
            $table->integer('cantidad');
            $table->integer('precio'); // Precio unitario
            $table->timestamps();
            
            // Primaria compuesta
            $table->primary(['id_nota_compra', 'id_insumo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_compra');
    }
};
