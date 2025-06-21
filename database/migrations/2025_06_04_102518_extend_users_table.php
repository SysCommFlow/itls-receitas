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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('tipo_usuario', ['cozinheiro', 'degustador', 'admin'])->default('cozinheiro');
            $table->string('telefone')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->text('bio')->nullable();
            $table->string('foto_perfil')->nullable();
            $table->json('especializacoes')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamp('ultimo_acesso')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_usuario',
                'telefone',
                'data_nascimento',
                'bio',
                'foto_perfil',
                'especializacoes',
                'ativo',
                'ultimo_acesso'
            ]);
        });
    }
};
