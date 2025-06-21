<?php

// =============================================================================
// database/factories/AvaliacaoFactory.php
// =============================================================================

namespace Database\Factories;

use App\Models\Avaliacao;
use App\Models\Teste;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvaliacaoFactory extends Factory
{
    protected $model = Avaliacao::class;

    public function definition(): array
    {
        $notaSabor = $this->faker->randomFloat(1, 5.0, 10.0);
        $notaApresentacao = $this->faker->randomFloat(1, 5.0, 10.0);
        $notaAroma = $this->faker->randomFloat(1, 5.0, 10.0);
        $notaTextura = $this->faker->randomFloat(1, 5.0, 10.0);
        $notaGeral = ($notaSabor + $notaApresentacao + $notaAroma + $notaTextura) / 4;

        return [
            'teste_id' => Teste::where('status', 'concluido')->inRandomOrder()->first()->id,
            'nota_sabor' => $notaSabor,
            'nota_apresentacao' => $notaApresentacao,
            'nota_aroma' => $notaAroma,
            'nota_textura' => $notaTextura,
            'nota_geral' => $notaGeral,
            'comentarios' => $this->gerarComentario($notaGeral),
            'recomenda' => $notaGeral >= 7.0,
            'sugestoes_melhoria' => $this->gerarSugestoes(),
        ];
    }

    private function gerarComentario(float $nota): string
    {
        if ($nota >= 9) {
            return 'Receita excepcional! ' . $this->faker->sentence(10);
        } elseif ($nota >= 8) {
            return 'Muito boa receita. ' . $this->faker->sentence(8);
        } elseif ($nota >= 7) {
            return 'Receita satisfatória. ' . $this->faker->sentence(6);
        } else {
            return 'Receita precisa de melhorias. ' . $this->faker->sentence(8);
        }
    }

    private function gerarSugestoes(): array
    {
        $sugestoes = [
            'Ajustar tempo de cozimento',
            'Reduzir sal',
            'Adicionar mais temperos',
            'Melhorar apresentação',
            'Usar ingredientes mais frescos',
            'Ajustar textura',
            'Equilibrar sabores'
        ];

        return $this->faker->randomElements($sugestoes, rand(1, 3));
    }
}
