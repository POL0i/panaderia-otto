<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notas_venta', function (Blueprint $table) {
            $table->id('id_nota_venta');
            $table->date('fecha_venta');
            $table->decimal('monto_total', 10, 2);
            $table->string('estado', 20)->default('pendiente');
            $table->string('metodo_pago', 50)->nullable();
            $table->string('id_transaccion_libelula')->nullable();
            $table->foreignId('id_cliente')->constrained('clientes', 'id_cliente')->onDelete('cascade');
            $table->foreignId('id_empleado')->nullable()->constrained('empleados', 'id_empleado')->onDelete('set null');
            $table->timestamps();
            
            $table->index('estado');
            $table->index('metodo_pago');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notas_venta');
    }
};