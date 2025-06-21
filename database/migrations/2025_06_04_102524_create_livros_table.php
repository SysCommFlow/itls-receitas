<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('isbn')->unique();
            $table->foreignId('editor_id')->constrained('editores');
            $table->json('receitas_incluidas');
            $table->date('data_publicacao')->nullable();
            $table->text('descricao')->nullable();
            $table->string('capa')->nullable();
            $table->enum('status', ['rascunho', 'em_revisao', 'publicado'])->default('rascunho');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livros');
    }
};
