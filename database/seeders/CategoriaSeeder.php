<?php

// =============================================================================
// database/seeders/CategoriaSeeder.php
// =============================================================================

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nome' => 'Carnes',
                'descricao' => 'Pratos principais à base de carnes vermelhas',
                'ativo' => true,
            ],
            [
                'nome' => 'Aves',
                'descricao' => 'Pratos à base de frango, peru, pato e outras aves',
                'ativo' => true,
            ],
            [
                'nome' => 'Peixes e Frutos do Mar',
                'descricao' => 'Pratos com peixes, camarão, lula e outros frutos do mar',
                'ativo' => true,
            ],
            [
                'nome' => 'Vegetariano',
                'descricao' => 'Pratos sem carne, adequados para vegetarianos',
                'ativo' => true,
            ],
            [
                'nome' => 'Vegano',
                'descricao' => 'Pratos 100% vegetais, sem ingredientes de origem animal',
                'ativo' => true,
            ],
            [
                'nome' => 'Sobremesas',
                'descricao' => 'Doces, pudins, tortas e outras sobremesas',
                'ativo' => true,
            ],
            [
                'nome' => 'Massas',
                'descricao' => 'Pratos à base de massas como macarrão, lasanha, nhoque',
                'ativo' => true,
            ],
            [
                'nome' => 'Saladas',
                'descricao' => 'Saladas frias e quentes, entradas leves',
                'ativo' => true,
            ],
            [
                'nome' => 'Sopas e Caldos',
                'descricao' => 'Sopas, caldos, cremes e consommés',
                'ativo' => true,
            ],
            [
                'nome' => 'Lanches',
                'descricao' => 'Sanduíches, wraps, tapiocas e lanches rápidos',
                'ativo' => true,
            ],
            [
                'nome' => 'Bebidas',
                'descricao' => 'Sucos, smoothies, vitaminas e outras bebidas',
                'ativo' => true,
            ],
            [
                'nome' => 'Tortas e Quiches',
                'descricao' => 'Tortas doces e salgadas, quiches e empadas',
                'ativo' => true,
            ],
            [
                'nome' => 'Bolos e Pães',
                'descricao' => 'Bolos, cupcakes, pães e produtos de panificação',
                'ativo' => true,
            ],
            [
                'nome' => 'Aperitivos',
                'descricao' => 'Petiscos, canapés e finger foods',
                'ativo' => true,
            ],
            [
                'nome' => 'Culinária Internacional',
                'descricao' => 'Pratos de diversas culinárias do mundo',
                'ativo' => true,
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
