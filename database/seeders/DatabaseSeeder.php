<?php

// =============================================================================
// database/seeders/DatabaseSeeder.php
// =============================================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Iniciando processo de seed...');

        // Ordem é importante devido às foreign keys
        $this->command->info('👥 Criando usuários...');
        $this->call(UserSeeder::class);

        $this->command->info('🏷️ Criando categorias...');
        $this->call(CategoriaSeeder::class);

        $this->command->info('🥕 Criando ingredientes...');
        $this->call(IngredienteSeeder::class);

        $this->command->info('👅 Criando degustadores...');
        $this->call(DegustadorSeeder::class);

        $this->command->info('📚 Criando editores...');
        $this->call(EditorSeeder::class);

        $this->command->info('🏪 Criando restaurantes...');
        $this->call(RestauranteSeeder::class);

        $this->command->info('🍳 Criando receitas...');
        $this->call(ReceitaSeeder::class);

        $this->command->info('🧪 Criando testes...');
        $this->call(TesteSeeder::class);

        $this->command->info('✅ Seed concluído com sucesso!');
        $this->command->line('');
        $this->command->info('📋 Dados criados:');
        $this->command->line('   - Usuários administradores, cozinheiros e degustadores');
        $this->command->line('   - Categorias de receitas');
        $this->command->line('   - Ingredientes variados');
        $this->command->line('   - Degustadores profissionais');
        $this->command->line('   - Editores de livros');
        $this->command->line('   - Restaurantes parceiros');
        $this->command->line('   - Receitas de exemplo');
        $this->command->line('   - Testes e avaliações');
        $this->command->line('');
        $this->command->info('🔐 Credenciais de acesso:');
        $this->command->line('   Admin: admin@itls.com / password');
        $this->command->line('   Cozinheiro: maria@itls.com / password');
        $this->command->line('   Degustador: degustador@itls.com / password');
    }
}
