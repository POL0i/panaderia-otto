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
        Schema::create('ppersona', function (Blueprint $table) {
            $table->id('id_persona');
            $table->foreignId('id_proveedor')->constrained('proveedores', 'id_proveedor')->onDelete('cascade');
            $table->string('nombre', 35);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppersona');
    }
};
