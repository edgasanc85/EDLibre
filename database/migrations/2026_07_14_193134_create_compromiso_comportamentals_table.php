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
        Schema::create('compromiso_comportamentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluado_id')->constrained('evaluados')->onDelete('cascade');
            $table->foreignId('periodo_id')->constrained('periodos')->onDelete('cascade');
            $table->foreignId('competencia_id')->constrained('competencias')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index('activo');
        });

        Schema::create('cc_conductas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compromiso_comportamental_id')
                  ->constrained('compromiso_comportamentals', 'id', 'cc_comp_id_fk')
                  ->onDelete('cascade');
            $table->foreignId('conducta_id')->constrained('conductas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cc_conductas');
        Schema::dropIfExists('compromiso_comportamentals');
    }
};
