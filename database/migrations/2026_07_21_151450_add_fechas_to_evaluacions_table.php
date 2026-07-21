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
        Schema::table('evaluacions', function (Blueprint $table) {
            $table->date('periodo_evaluado_inicio')->nullable()->after('causal');
            $table->date('periodo_evaluado_fin')->nullable()->after('periodo_evaluado_inicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluacions', function (Blueprint $table) {
            $table->dropColumn(['periodo_evaluado_inicio', 'periodo_evaluado_fin']);
        });
    }
};
