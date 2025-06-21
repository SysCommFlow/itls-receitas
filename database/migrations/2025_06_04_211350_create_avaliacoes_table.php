<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teste_id')->constrained()->onDelete('cascade');
            $table->decimal('nota_sabor', 3, 2); // 0.00 a 10.00
            $table->decimal('nota_apresentacao', 3, 2);
            $table->decimal('nota_aroma', 3, 2);
            $table->decimal('nota_textura', 5, 2);
            $table->decimal('nota_geral', 3, 2);
            $table->text('comentarios')->nullable();
            $table->boolean('recomenda')->default(true);
            $table->json('sugestoes_melhoria')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};
