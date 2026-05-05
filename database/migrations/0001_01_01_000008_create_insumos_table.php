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
        Schema::create('insumos', function (Blueprint $table) {
            $table->id('id_insumo');
            $table->foreignId('id_item')->constrained('items', 'id_item')->onDelete('cascade');
            $table->foreignId('id_cat_insumo')->constrained('categoria_insumo', 'id_cat_insumo')->onDelete('restrict');
            $table->decimal('precio_compra', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};
