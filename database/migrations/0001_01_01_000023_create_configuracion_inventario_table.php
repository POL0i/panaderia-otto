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
        Schema::create('configuracion_inventario', function (Blueprint $table) {
            $table->id('id_config');
            $table->enum('metodo_valuacion_predeterminado', ['PEPS', 'UEPS'])->default('PEPS');
            $table->boolean('automatizar_movimientos')->default(true);
            $table->boolean('requerir_aprobacion')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion_inventario');
    }
};
