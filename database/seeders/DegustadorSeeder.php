<?php

// =============================================================================
// database/seeders/DegustadorSeeder.php
// =============================================================================

namespace Database\Seeders;

use App\Models\Degustador;
use Illuminate\Database\Seeder;

class DegustadorSeeder extends Seeder
{
    public function run(): void
    {
        $degustadores = [
            [
                'nome' => 'Carlos Roberto Silva',
                'email' => 'carlos.degustador@itls.com',
                'telefone' => '(11) 95555-1111',
                'especializacoes' => 'Culinária Internacional, Análise Sensorial',
                'experiencia_anos' => 15,
                'ativo' => true,
            ],
            [
                'nome' => 'Fernanda Gourmet',
                'email' => 'fernanda@degustadores.com',
                'telefone' => '(11) 94444-2222',
                'especializacoes' => 'Doces, Sobremesas, Confeitaria',
                'experiencia_anos' => 8,
                'ativo' => true,
            ],
            [
                'nome' => 'Ricardo Paladar',
                'email' => 'ricardo.paladar@gmail.com',
                'telefone' => '(11) 93333-3333',
                'especializacoes' => 'Carnes, Churrascos, Grelhados',
                'experiencia_anos' => 12,
                'ativo' => true,
            ],
            [
                'nome' => 'Juliana Sabores',
                'email' => 'ju.sabores@hotmail.com',
                'telefone' => '(11) 92222-4444',
                'especializacoes' => 'Culinária Vegetariana, Vegana',
                'experiencia_anos' => 6,
                'ativo' => true,
            ],
            [
                'nome' => 'Marco Antonio Chef',
                'email' => 'marco.chef@outlook.com',
                'telefone' => '(11) 91111-5555',
                'especializacoes' => 'Culinária Italiana, Massas, Molhos',
                'experiencia_anos' => 20,
                'ativo' => true,
            ],
        ];

        foreach ($degustadores as $degustador) {
            Degustador::create($degustador);
        }
    }
}
