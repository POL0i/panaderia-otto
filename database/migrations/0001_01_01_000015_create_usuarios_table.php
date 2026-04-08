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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('correo', 45)->unique();
            $table->string('contraseña', 255); // Hashed password
            $table->string('estado', 15)->default('activo'); // activo/inactivo
            $table->string('tipo_usuario', 20); // cliente/empleado
            $table->foreignId('id_cliente')->nullable()->constrained('clientes', 'id_cliente')->onDelete('cascade');
            $table->foreignId('id_empleado')->nullable()->constrained('empleados', 'id_empleado')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
