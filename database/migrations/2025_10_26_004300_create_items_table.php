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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carpeta_id')->constrained('carpetas')->cascadeOnDelete();
            $table->foreignId('numero_id')->nullable()->constrained('numeros')->nullOnDelete();
            $table->foreignId('seccion_id')->nullable()->constrained('secciones')->nullOnDelete();

            $table->string('titulo', 300)->index();
            $table->string('subtitulo', 300)->nullable()->index();
            $table->text('descripcion')->nullable();
            $table->date('fecha')->nullable()->index();
            $table->string('pagina', 30)->nullable();

            $table->string('estado', 30)->nullable();
            $table->boolean('tiene_ocr')->default(false);
            $table->longText('texto_ocr')->nullable();
            $table->json('archivos')->nullable();

            // Índices compuestos para búsquedas comunes
            $table->index(['carpeta_id', 'fecha']);
            $table->index(['numero_id', 'fecha']);
            $table->index(['seccion_id', 'fecha']);

            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        });

        // Crear índice fulltext solo para MySQL/MariaDB
        if (Schema::connection(null)->getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('items', function (Blueprint $table) {
                $table->fullText(['titulo', 'descripcion', 'texto_ocr'], 'busqueda_items');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
