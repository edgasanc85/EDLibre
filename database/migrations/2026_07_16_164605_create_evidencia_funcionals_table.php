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
        Schema::create('evidencia_funcionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compromiso_funcional_id')->constrained()->onDelete('cascade');
            $table->text('descripcion');
            $table->string('ubicacion')->nullable()->comment('URL de Drive, OneDrive, SharePoint, etc.');
            $table->decimal('calificacion', 5, 2)->nullable()->comment('Calificación de 0 a 100');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evidencia_funcionals');
    }
};
