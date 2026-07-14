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
        Schema::create('concertaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluado_id')->constrained('evaluados')->onDelete('cascade');
            $table->foreignId('evaluador_id')->nullable()->constrained('evaluadors')->onDelete('cascade');
            $table->foreignId('periodo_id')->constrained('periodos')->onDelete('cascade');
            $table->enum('estado', ['borrador', 'en_revision', 'aprobado', 'fijado_de_oficio'])->default('borrador');
            $table->timestamp('fecha_aprobacion_evaluado')->nullable();
            $table->timestamp('fecha_aprobacion_evaluador')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concertaciones');
    }
};
