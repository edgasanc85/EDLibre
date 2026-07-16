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
            $table->dropColumn('calificacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evidencia_funcionals', function (Blueprint $table) {
            $table->decimal('calificacion', 5, 2)->nullable()->comment('Calificación de 0 a 100');
        });
    }
};
