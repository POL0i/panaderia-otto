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
            
            // FK a traspasos
            $table->foreignId('id_traspaso')
                  ->constrained('traspasos', 'id_traspaso')
                  ->onDelete('cascade');
            
            // Referencias directas a almacen_item (origen)
            $table->foreignId('id_almacen_origen');
            $table->foreignId('id_item');
            
            // Referencias directas a almacen_item (destino)
            $table->foreignId('id_almacen_destino');
            // id_item es el mismo, no se duplica
            
            $table->integer('cantidad');
            $table->timestamps();
            
            // Foreign key compuesta hacia almacen_item (origen)
            $table->foreign(['id_almacen_origen', 'id_item'])
                  ->references(['id_almacen', 'id_item'])
                  ->on('almacen_item')
                  ->onDelete('restrict');
            
            // Foreign key compuesta hacia almacen_item (destino)
            $table->foreign(['id_almacen_destino', 'id_item'])
                  ->references(['id_almacen', 'id_item'])
                  ->on('almacen_item')
                  ->onDelete('restrict');
            
            // Índice único para evitar duplicados
            $table->unique(
                ['id_traspaso', 'id_almacen_origen', 'id_almacen_destino', 'id_item'],
                'traspaso_item_unico'
            );
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