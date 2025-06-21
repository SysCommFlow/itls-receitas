<?php

// =============================================================================
// database/factories/ReceitaFactory.php
// =============================================================================

namespace Database\Factories;

use App\Models\Receita;
use App\Models\User;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceitaFactory extends Factory
{
    protected $model = Receita::class;

    public function definition(): array
    {
        $nomes = [
            'Lasanha de Berinjela', 'Salmão ao Molho de Maracujá', 'Risotto de Camarão',
            'Bolo de Chocolate com Ganache', 'Salada de Quinoa', 'Frango Xadrez',
            'Sopa de Abóbora', 'Hambúrguer Vegetariano', 'Pavê de Bis',
            'Escondidinho de Carne Seca', 'Quiche de Espinafre', 'Mousse de Limão',
            'Bobó de Camarão', 'Torta de Frango', 'Smoothie Detox',
            'Coxinha de Frango', 'Brigadeiro Gourmet', 'Salada Caesar',
            'Macarrão à Carbonara', 'Tiramisu', 'Feijoada Vegetariana',
            'Paella Valenciana', 'Ratatouille', 'Cheesecake de Frutas Vermelhas'
        ];

        return [
            'nome' => $this->faker->randomElement($nomes),
            'user_id' => User::where('tipo_usuario', 'cozinheiro')->inRandomOrder()->first()->id,
            'categoria_id' => Categoria::where('ativo', true)->inRandomOrder()->first()->id,
            'modo_preparacao' => $this->gerarModoPreparacao(),
            'tempo_cozimento' => $this->faker->numberBetween(10, 180),
            'numero_porcoes' => $this->faker->numberBetween(2, 12),
            'observacoes' => $this->faker->optional(0.7)->paragraph(),
            'publicada' => $this->faker->boolean(80), // 80% chance de estar publicada
            'testada' => $this->faker->boolean(60), // 60% chance de estar testada
            'nota_media' => $this->faker->optional(0.6)->randomFloat(1, 5.0, 10.0),
        ];
    }

    private function gerarModoPreparacao(): string
    {
        $passos = [
            'Prepare todos os ingredientes, lavando e cortando conforme necessário.',
            'Em uma panela, aqueça o óleo em fogo médio.',
            'Adicione os temperos e refogue até ficarem aromáticos.',
            'Acrescente os ingredientes principais e cozinhe conforme indicado.',
            'Tempere com sal e pimenta a gosto.',
            'Deixe cozinhar até o ponto desejado.',
            'Finalize com ervas frescas ou decoração escolhida.',
            'Sirva quente acompanhado dos complementos sugeridos.'
        ];

        return implode(' ', $this->faker->randomElements($passos, rand(4, 6)));
    }

    public function publicada(): static
    {
        return $this->state(fn (array $attributes) => [
            'publicada' => true,
        ]);
    }

    public function testada(): static
    {
        return $this->state(fn (array $attributes) => [
            'testada' => true,
            'nota_media' => $this->faker->randomFloat(1, 6.0, 10.0),
        ]);
    }
}
