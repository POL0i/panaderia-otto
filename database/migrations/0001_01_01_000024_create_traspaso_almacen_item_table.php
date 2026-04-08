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
        Schema::create('traspaso_almacen_item', function (Blueprint $table) {
            $table->id('id_detalle_traspaso');
            $table->foreignId('id_traspaso')->constrained('traspasos', 'id_traspaso')->onDelete('cascade');
            $table->foreignId('id_almacen_origen')->constrained('almacenes', 'id_almacen')->onDelete('cascade');
            $table->foreignId('id_almacen_destino')->constrained('almacenes', 'id_almacen')->onDelete('cascade');
            $table->foreignId('id_item')->constrained('items', 'id_item')->onDelete('cascade');
            $table->integer('cantidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traspaso_almacen_item');
    }
};
