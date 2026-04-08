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
        Schema::table('insumos', function (Blueprint $table) {
            $table->foreignId('id_cat_insumo')->nullable()->after('nombre')->constrained('categoria_insumo', 'id_cat_insumo')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insumos', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['id_cat_insumo_foreign']);
            $table->dropColumn('id_cat_insumo');
        });
    }
};
