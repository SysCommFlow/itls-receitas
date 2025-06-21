<?php

// =============================================================================
// database/factories/TesteFactory.php
// =============================================================================

namespace Database\Factories;

use App\Models\Teste;
use App\Models\Receita;
use App\Models\Degustador;
use Illuminate\Database\Eloquent\Factories\Factory;

class TesteFactory extends Factory
{
    protected $model = Teste::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['agendado', 'em_andamento', 'concluido', 'cancelado']);

        // Definir data baseada no status
        $dataBase = match($status) {
            'agendado' => $this->faker->dateTimeBetween('now', '+1 month'),
            'em_andamento' => $this->faker->dateTimeBetween('-3 days', 'now'),
            'concluido' => $this->faker->dateTimeBetween('-2 months', '-1 day'),
            'cancelado' => $this->faker->dateTimeBetween('-1 month', '-1 day'),
        };

        return [
            'receita_id' => Receita::where('publicada', true)->inRandomOrder()->first()->id,
            'degustador_id' => Degustador::where('ativo', true)->inRandomOrder()->first()->id,
            'data_teste' => $dataBase,
            'status' => $status,
            'observacoes_pre_teste' => $this->faker->optional(0.8)->paragraph(),
            'observacoes_pos_teste' => in_array($status, ['concluido', 'cancelado'])
                ? $this->faker->paragraph()
                : null,
        ];
    }

    public function agendado(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'agendado',
            'data_teste' => $this->faker->dateTimeBetween('now', '+1 month'),
            'observacoes_pos_teste' => null,
        ]);
    }

    public function concluido(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'concluido',
            'data_teste' => $this->faker->dateTimeBetween('-2 months', '-1 day'),
            'observacoes_pos_teste' => $this->faker->paragraph(),
        ]);
    }
}
