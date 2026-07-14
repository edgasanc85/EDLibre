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
        Schema::create('compromiso_funcionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluado_id')->constrained('evaluados')->onDelete('cascade');
            $table->foreignId('periodo_id')->constrained('periodos')->onDelete('cascade');
            $table->string('verbo');
            $table->text('objeto');
            $table->text('condicion');
            $table->integer('peso'); // Porcentaje, e.g., 20
            
            // Campo de borrado lógico
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
        Schema::dropIfExists('compromiso_funcionals');
    }
};
