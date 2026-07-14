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
        Schema::table('compromiso_funcionals', function (Blueprint $table) {
            $table->foreignId('concertacion_id')->nullable()->constrained('concertaciones')->onDelete('cascade');
        });

        Schema::table('compromiso_comportamentals', function (Blueprint $table) {
            $table->foreignId('concertacion_id')->nullable()->constrained('concertaciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compromiso_funcionals', function (Blueprint $table) {
            $table->dropForeign(['concertacion_id']);
            $table->dropColumn('concertacion_id');
        });

        Schema::table('compromiso_comportamentals', function (Blueprint $table) {
            $table->dropForeign(['concertacion_id']);
            $table->dropColumn('concertacion_id');
        });
    }
};
