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
        Schema::create('detalles_venta', function (Blueprint $table) {
            $table->foreignId('id_nota_venta')->constrained('notas_venta', 'id_nota_venta')->onDelete('cascade');
            $table->foreignId('id_producto')->constrained('productos', 'id_producto')->onDelete('cascade');
            $table->integer('cantidad');
            $table->integer('precio'); // Precio unitario
            $table->timestamps();
            
            // Primaria compuesta
            $table->primary(['id_nota_venta', 'id_producto']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_venta');
    }
};
