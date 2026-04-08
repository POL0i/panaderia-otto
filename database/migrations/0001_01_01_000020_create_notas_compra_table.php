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
        Schema::create('notas_compra', function (Blueprint $table) {
            $table->id('id_nota_compra');
            $table->date('fecha_compra');
            $table->integer('monto_total');
            $table->string('estado', 20)->default('pendiente'); // pendiente, completado, cancelado
            $table->foreignId('id_empleado')->constrained('empleados', 'id_empleado')->onDelete('cascade');
            $table->foreignId('id_proveedor')->constrained('proveedores', 'id_proveedor')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas_compra');
    }
};
