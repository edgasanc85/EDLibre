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
        Schema::create('evaluacion_comportamentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluacion_id')->constrained()->onDelete('cascade');
            $table->foreignId('compromiso_comportamental_id')->constrained()->onDelete('cascade');
            $table->foreignId('conducta_id')->constrained()->onDelete('cascade');
            $table->decimal('calificacion', 5, 2)->nullable()->comment('De 0 a 100');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluacion_comportamentals');
    }
};
