<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receitas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_unico')->unique();
            $table->string('nome');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->text('modo_preparacao');
            $table->integer('tempo_cozimento');
            $table->integer('numero_porcoes');
            $table->text('observacoes')->nullable();
            $table->json('imagens')->nullable();
            $table->boolean('publicada')->default(false);
            $table->boolean('testada')->default(false);
            $table->decimal('nota_media', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receitas');
    }
};
