<?php

// =============================================================================
// database/seeders/RestauranteSeeder.php
// =============================================================================

namespace Database\Seeders;

use App\Models\Restaurante;
use Illuminate\Database\Seeder;

class RestauranteSeeder extends Seeder
{
    public function run(): void
    {
        $restaurantes = [
            [
                'nome' => 'Cantina Bella Vista',
                'endereco' => 'Rua das Flores, 123 - Vila Madalena, São Paulo',
                'telefone' => '(11) 3333-1111',
                'email' => 'contato@bellavista.com',
                'tipo_cozinha' => 'Italiana',
                'nota_media' => 8.5,
                'ativo' => true,
            ],
            [
                'nome' => 'Sushi Zen',
                'endereco' => 'Av. Paulista, 1000 - Bela Vista, São Paulo',
                'telefone' => '(11) 3333-2222',
                'email' => 'zen@sushizen.com.br',
                'tipo_cozinha' => 'Japonesa',
                'nota_media' => 9.2,
                'ativo' => true,
            ],
            [
                'nome' => 'Churrascaria Gaúcha',
                'endereco' => 'Rua dos Pampas, 456 - Moema, São Paulo',
                'telefone' => '(11) 3333-3333',
                'email' => 'gaucha@churrasco.com',
                'tipo_cozinha' => 'Brasileira',
                'nota_media' => 8.8,
                'ativo' => true,
            ],
            [
                'nome' => 'Bistrô França',
                'endereco' => 'Rua Oscar Freire, 789 - Jardins, São Paulo',
                'telefone' => '(11) 3333-4444',
                'email' => 'bistro@franca.com.br',
                'tipo_cozinha' => 'Francesa',
                'nota_media' => 9.0,
                'ativo' => true,
            ],
            [
                'nome' => 'Veggie Green',
                'endereco' => 'Rua Eco, 321 - Pinheiros, São Paulo',
                'telefone' => '(11) 3333-5555',
                'email' => 'green@veggie.com',
                'tipo_cozinha' => 'Vegetariana',
                'nota_media' => 8.3,
                'ativo' => true,
            ],
            [
                'nome' => 'Taco Mexicano',
                'endereco' => 'Rua das Américas, 654 - Vila Olímpia, São Paulo',
                'telefone' => '(11) 3333-6666',
                'email' => 'ole@tacomexicano.com',
                'tipo_cozinha' => 'Mexicana',
                'nota_media' => 7.9,
                'ativo' => true,
            ],
        ];

        foreach ($restaurantes as $restaurante) {
            Restaurante::create($restaurante);
        }
    }
}
