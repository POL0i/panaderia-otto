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
        Schema::create('rol_permiso', function (Blueprint $table) {
            $table->id('id_rol_permiso');
            $table->foreignId('id_rol')->constrained('roles', 'id_rol')->onDelete('cascade');
            $table->foreignId('id_permiso')->constrained('permisos', 'id_permiso')->onDelete('cascade');
            $table->string('estado', 15)->default('activo'); // activo/inactivo
            $table->string('descripcion', 90)->nullable();
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['id_rol', 'id_permiso']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol_permiso');
    }
};
