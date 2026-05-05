<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transacciones_libelula', function (Blueprint $table) {
            // Cambiar de string a text para URLs largas
            $table->text('url_pasarela')->nullable()->change();
            $table->text('qr_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('transacciones_libelula', function (Blueprint $table) {
            $table->string('url_pasarela', 255)->nullable()->change();
            $table->string('qr_url', 255)->nullable()->change();
        });
    }
};