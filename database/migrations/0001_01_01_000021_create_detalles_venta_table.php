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
            // FK a nota_venta
            $table->foreignId('id_nota_venta')
                  ->constrained('notas_venta', 'id_nota_venta')
                  ->onDelete('cascade');
            
            // FK a almacen (para saber de qué almacén se descuenta)
            $table->foreignId('id_almacen')
                  ->constrained('almacenes', 'id_almacen')
                  ->onDelete('restrict'); // No permitir borrar almacén con ventas
            
            // FK a item (para conectar con almacen_item)
            $table->foreignId('id_item')
                  ->constrained('items', 'id_item')
                  ->onDelete('restrict');
            
            $table->integer('cantidad');
            $table->integer('precio'); // Precio unitario al momento de la venta
            $table->decimal('subtotal', 12, 2)->storedAs('cantidad * precio'); // Calculado automáticamente
            $table->timestamps();
            
            // Primaria compuesta con las 3 llaves (nota, almacen, item)
            $table->primary(['id_nota_venta', 'id_almacen', 'id_item']);
            
            // Foreign key compuesta hacia almacen_item
            $table->foreign(['id_almacen', 'id_item'])
                  ->references(['id_almacen', 'id_item'])
                  ->on('almacen_item')
                  ->onDelete('restrict');
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