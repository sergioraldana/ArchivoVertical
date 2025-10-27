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
        Schema::create('anios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fuente_id')->constrained('fuentes')->cascadeOnDelete();
            $table->string('anio', 10)->index(); // Acepta numérico o romano
            $table->unique(['fuente_id', 'anio']);
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anios');
    }
};
