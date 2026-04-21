<?php
// database/migrations/xxxx_create_almacen_item_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('almacen_item', function (Blueprint $table) {
            $table->foreignId('id_almacen')
                  ->constrained('almacenes', 'id_almacen')
                  ->onDelete('cascade');
            $table->foreignId('id_item')
                  ->constrained('items', 'id_item')
                  ->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->timestamps();
            
            // Primaria compuesta
            $table->primary(['id_almacen', 'id_item']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('almacen_item');
    }
};