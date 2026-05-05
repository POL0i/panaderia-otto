<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transacciones_libelula', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_venta_id')->constrained('notas_venta', 'id_nota_venta')->onDelete('cascade');
            $table->string('identificador');
            $table->string('id_transaccion_libelula')->nullable();
            $table->string('codigo_recaudacion')->nullable();
            $table->decimal('monto', 10, 2);
            $table->string('estado')->default('pendiente');
            $table->string('qr_url')->nullable();
            $table->string('url_pasarela')->nullable();
            $table->json('respuesta_api')->nullable();
            $table->timestamps();
            
            $table->index('identificador');
            $table->index('id_transaccion_libelula');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transacciones_libelula');
    }
};