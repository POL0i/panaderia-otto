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
        Schema::create('rol_permiso_usuario', function (Blueprint $table) {
            $table->id('id_rol_permiso_usuario');
            $table->foreignId('id_rol_permiso')->constrained('rol_permiso', 'id_rol_permiso')->onDelete('cascade');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            $table->string('estado', 15)->default('activo'); // activo/inactivo
            $table->timestamp('fecha_asignacion')->useCurrent(); // Auditoría
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['id_rol_permiso', 'id_usuario']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol_permiso_usuario');
    }
};
