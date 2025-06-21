<?php

// =============================================================================
// database/factories/UserFactory.php
// =============================================================================

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $tipoUsuario = $this->faker->randomElement(['cozinheiro', 'degustador']);

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'tipo_usuario' => $tipoUsuario,
            'telefone' => $this->faker->phoneNumber(),
            'data_nascimento' => $this->faker->dateTimeBetween('-60 years', '-18 years'),
            'bio' => $this->faker->paragraph(3),
            'especializacoes' => $this->gerarEspecializacoes($tipoUsuario),
            'ativo' => true,
        ];
    }

    private function gerarEspecializacoes(string $tipo): array
    {
        if ($tipo === 'cozinheiro') {
            $especializacoes = [
                'Culinária Brasileira', 'Culinária Italiana', 'Culinária Francesa',
                'Culinária Oriental', 'Confeitaria', 'Massas', 'Carnes', 'Frutos do Mar',
                'Vegetariana', 'Vegana', 'Sobremesas', 'Panificação'
            ];
        } else {
            $especializacoes = [
                'Análise Sensorial', 'Culinária Internacional', 'Vinhos',
                'Doces e Sobremesas', 'Carnes e Grelhados', 'Culinária Vegana',
                'Pratos Quentes', 'Entrada e Aperitivos'
            ];
        }

        return $this->faker->randomElements($especializacoes, rand(2, 4));
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo_usuario' => 'admin',
            'especializacoes' => ['Administração', 'Gestão de Sistema'],
        ]);
    }

    public function cozinheiro(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo_usuario' => 'cozinheiro',
        ]);
    }

    public function degustador(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo_usuario' => 'degustador',
        ]);
    }
}
