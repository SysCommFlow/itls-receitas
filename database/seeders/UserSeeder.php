<?php

// =============================================================================
// database/seeders/UserSeeder.php
// =============================================================================

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin padrão
        User::create([
            'name' => 'Administrador ITLS',
            'email' => 'admin@itls.com',
            'password' => Hash::make('password'),
            'tipo_usuario' => 'admin',
            'telefone' => '(11) 99999-0000',
            'bio' => 'Administrador do sistema ITLS de receitas culinárias.',
            'especializacoes' => ['Administração', 'Gestão de Sistema'],
            'ativo' => true,
            'email_verified_at' => now(),
        ]);

        // Cozinheiros de exemplo
        $cozinheiros = [
            [
                'name' => 'Chef Maria Santos',
                'email' => 'maria@itls.com',
                'password' => Hash::make('password'),
                'tipo_usuario' => 'cozinheiro',
                'telefone' => '(11) 98888-1111',
                'bio' => 'Chef especializada em culinária brasileira e mediterrânea.',
                'especializacoes' => ['Culinária Brasileira', 'Mediterrânea', 'Massas'],
                'ativo' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Chef João Silva',
                'email' => 'joao@itls.com',
                'password' => Hash::make('password'),
                'tipo_usuario' => 'cozinheiro',
                'telefone' => '(11) 97777-2222',
                'bio' => 'Especialista em culinária oriental e fusion.',
                'especializacoes' => ['Culinária Oriental', 'Fusion', 'Sushi'],
                'ativo' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Chef Ana Costa',
                'email' => 'ana@itls.com',
                'password' => Hash::make('password'),
                'tipo_usuario' => 'cozinheiro',
                'telefone' => '(11) 96666-3333',
                'bio' => 'Confeiteira e especialista em doces e sobremesas.',
                'especializacoes' => ['Confeitaria', 'Sobremesas', 'Bolos'],
                'ativo' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($cozinheiros as $cozinheiro) {
            User::create($cozinheiro);
        }

        // Degustador de exemplo
        User::create([
            'name' => 'Carlos Degustador',
            'email' => 'degustador@itls.com',
            'password' => Hash::make('password'),
            'tipo_usuario' => 'degustador',
            'telefone' => '(11) 95555-4444',
            'bio' => 'Degustador profissional com 15 anos de experiência.',
            'especializacoes' => ['Análise Sensorial', 'Culinária Internacional'],
            'ativo' => true,
            'email_verified_at' => now(),
        ]);

        // Usuários adicionais usando factory
        User::factory(10)->create();
    }
}
