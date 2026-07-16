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
        Schema::create('evaluacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concertacion_id')->constrained('concertaciones')->onDelete('cascade');
            $table->string('causal'); // e.g. "Parcial primer semestre"
            $table->string('estado')->default('en_revision'); // en_revision, calificada, aceptada, rechazada_comision, dirimida
            $table->decimal('puntaje_funcional_obtenido', 5, 2)->nullable();
            $table->decimal('puntaje_comportamental_obtenido', 5, 2)->nullable();
            $table->timestamp('fecha_evaluacion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluacions');
    }
};
