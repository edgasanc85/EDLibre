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
        Schema::create('dependencias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('parent_id')->nullable()->constrained('dependencias')->onDelete('cascade');
            
            // Campo de borrado lógico (True = Activo/Visible, False = Borrado/Oculto)
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Índice de optimización
            $table->index('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dependencias');
    }
};
