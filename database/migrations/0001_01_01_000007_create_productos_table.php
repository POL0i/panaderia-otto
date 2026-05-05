<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_producto');
            $table->foreignId('id_item')->constrained('items', 'id_item')->onDelete('cascade');
            $table->foreignId('id_cat_producto')->constrained('categoria_producto', 'id_cat_producto')->onDelete('restrict');
            $table->decimal('precio', 10, 2);
            $table->string('imagen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};