<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receita_id')->constrained()->onDelete('cascade');
            $table->foreignId('degustador_id')->constrained('degustadores');
            $table->dateTime('data_teste');
            $table->enum('status', ['agendado', 'em_andamento', 'concluido', 'cancelado'])->default('agendado');
            $table->text('observacoes_pre_teste')->nullable();
            $table->text('observacoes_pos_teste')->nullable();
            $table->json('fotos_teste')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testes');
    }
};
