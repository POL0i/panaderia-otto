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
        Schema::create('producciones', function (Blueprint $table) {
            $table->id('id_produccion');
            $table->date('fecha_produccion');
            $table->integer('cantidad_producida');
            $table->foreignId('id_empleado_solicita')->constrained('empleados', 'id_empleado');
            $table->foreignId('id_empleado_autoriza')->nullable()->constrained('empleados', 'id_empleado');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado', 'cancelado'])->default('pendiente');
            $table->timestamp('fecha_solicitud')->useCurrent();
            $table->timestamp('fecha_autorizacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producciones');
    }
};
