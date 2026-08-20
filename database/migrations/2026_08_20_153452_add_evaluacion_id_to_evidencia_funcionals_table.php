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
        Schema::table('evidencia_funcionals', function (Blueprint $table) {
            $table->foreignId('evaluacion_id')->nullable()->after('compromiso_funcional_id')->constrained('evaluacions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evidencia_funcionals', function (Blueprint $table) {
            $table->dropForeign(['evaluacion_id']);
            $table->dropColumn('evaluacion_id');
        });
    }
};
