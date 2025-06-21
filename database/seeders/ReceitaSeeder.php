<?php

// =============================================================================
// database/seeders/ReceitaSeeder.php
// =============================================================================

namespace Database\Seeders;

use App\Models\Receita;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Ingrediente;
use Illuminate\Database\Seeder;

class ReceitaSeeder extends Seeder
{
    public function run(): void
    {
        $cozinheiros = User::where('tipo_usuario', 'cozinheiro')->get();
        $categorias = Categoria::all();
        $ingredientes = Ingrediente::all();

        if ($cozinheiros->isEmpty() || $categorias->isEmpty() || $ingredientes->isEmpty()) {
            $this->command->error('É necessário ter cozinheiros, categorias e ingredientes antes de criar receitas.');
            return;
        }

        $receitas = [
            [
                'nome' => 'Risotto de Cogumelos',
                'categoria' => 'Massas',
                'modo_preparacao' => 'Em uma panela, refogue a cebola picada com azeite até ficar transparente. Adicione o arroz arbóreo e refogue por 2 minutos. Vá adicionando o caldo quente aos poucos, mexendo sempre, até o arroz ficar cremoso. Incorpore os cogumelos refogados e finalize com queijo parmesão e manteiga.',
                'tempo_cozimento' => 35,
                'numero_porcoes' => 4,
                'observacoes' => 'O segredo do risotto é mexer sempre e adicionar o caldo aos poucos.',
                'ingredientes' => [
                    ['nome' => 'Arroz branco', 'quantidade' => 300, 'unidade' => 'g'],
                    ['nome' => 'Cebola', 'quantidade' => 1, 'unidade' => 'unidade'],
                    ['nome' => 'Azeite de oliva', 'quantidade' => 50, 'unidade' => 'mL'],
                    ['nome' => 'Queijo parmesão', 'quantidade' => 100, 'unidade' => 'g'],
                    ['nome' => 'Manteiga', 'quantidade' => 50, 'unidade' => 'g'],
                ]
            ],
            [
                'nome' => 'Salmão Grelhado com Legumes',
                'categoria' => 'Peixes e Frutos do Mar',
                'modo_preparacao' => 'Tempere o salmão com sal, pimenta e ervas. Grelhe por 4-5 minutos de cada lado. Refogue os legumes com azeite e temperos. Sirva o salmão sobre os legumes com um fio de azeite.',
                'tempo_cozimento' => 20,
                'numero_porcoes' => 2,
                'observacoes' => 'O salmão deve estar bem seco antes de grelhar para formar uma crosta dourada.',
                'ingredientes' => [
                    ['nome' => 'Peixe salmão', 'quantidade' => 400, 'unidade' => 'g'],
                    ['nome' => 'Abobrinha', 'quantidade' => 200, 'unidade' => 'g'],
                    ['nome' => 'Pimentão', 'quantidade' => 150, 'unidade' => 'g'],
                    ['nome' => 'Azeite de oliva', 'quantidade' => 30, 'unidade' => 'mL'],
                    ['nome' => 'Sal', 'quantidade' => 5, 'unidade' => 'g'],
                ]
            ],
            [
                'nome' => 'Brownie de Chocolate',
                'categoria' => 'Sobremesas',
                'modo_preparacao' => 'Derreta o chocolate com a manteiga em banho-maria. Bata os ovos com açúcar até dobrar de volume. Misture o chocolate derretido, adicione a farinha peneirada e incorpore delicadamente. Asse em forma untada por 25-30 minutos.',
                'tempo_cozimento' => 45,
                'numero_porcoes' => 12,
                'observacoes' => 'O brownie deve ficar úmido no centro. Não asse demais.',
                'ingredientes' => [
                    ['nome' => 'Farinha de trigo', 'quantidade' => 200, 'unidade' => 'g'],
                    ['nome' => 'Açúcar cristal', 'quantidade' => 300, 'unidade' => 'g'],
                    ['nome' => 'Ovos', 'quantidade' => 4, 'unidade' => 'unidades'],
                    ['nome' => 'Manteiga', 'quantidade' => 200, 'unidade' => 'g'],
                ]
            ],
            [
                'nome' => 'Salada Caesar Vegetariana',
                'categoria' => 'Vegetariano',
                'modo_preparacao' => 'Lave e corte a alface em pedaços grandes. Prepare o molho misturando azeite, limão, mostarda e queijo parmesão. Tempere a salada com o molho, adicione croutons e sirva imediatamente.',
                'tempo_cozimento' => 15,
                'numero_porcoes' => 4,
                'observacoes' => 'Sirva logo após temperar para manter a crocância.',
                'ingredientes' => [
                    ['nome' => 'Alface', 'quantidade' => 2, 'unidade' => 'maços'],
                    ['nome' => 'Queijo parmesão', 'quantidade' => 80, 'unidade' => 'g'],
                    ['nome' => 'Azeite de oliva', 'quantidade' => 60, 'unidade' => 'mL'],
                    ['nome' => 'Limão', 'quantidade' => 2, 'unidade' => 'unidades'],
                ]
            ],
            [
                'nome' => 'Frango ao Curry',
                'categoria' => 'Aves',
                'modo_preparacao' => 'Corte o frango em cubos e tempere com sal. Refogue a cebola, adicione o frango e doure. Acrescente o curry, leite de coco e deixe cozinhar por 20 minutos. Ajuste os temperos e sirva com arroz.',
                'tempo_cozimento' => 40,
                'numero_porcoes' => 6,
                'observacoes' => 'O curry pode ser ajustado conforme o gosto pessoal.',
                'ingredientes' => [
                    ['nome' => 'Frango (peito)', 'quantidade' => 800, 'unidade' => 'g'],
                    ['nome' => 'Cebola', 'quantidade' => 2, 'unidade' => 'unidades'],
                    ['nome' => 'Curry em pó', 'quantidade' => 15, 'unidade' => 'g'],
                    ['nome' => 'Leite integral', 'quantidade' => 400, 'unidade' => 'mL'],
                ]
            ],
            [
                'nome' => 'Lasanha de Berinjela',
                'categoria' => 'Vegetariano',
                'modo_preparacao' => 'Corte as berinjelas em fatias e grelhe. Prepare o molho de tomate refogando cebola, alho e tomate. Monte a lasanha alternando camadas de berinjela, molho e queijo. Asse por 30 minutos.',
                'tempo_cozimento' => 50,
                'numero_porcoes' => 8,
                'observacoes' => 'Deixe descansar 10 minutos antes de cortar.',
                'ingredientes' => [
                    ['nome' => 'Abobrinha', 'quantidade' => 800, 'unidade' => 'g'],
                    ['nome' => 'Tomate', 'quantidade' => 500, 'unidade' => 'g'],
                    ['nome' => 'Queijo mussarela', 'quantidade' => 300, 'unidade' => 'g'],
                    ['nome' => 'Cebola', 'quantidade' => 1, 'unidade' => 'unidade'],
                    ['nome' => 'Alho', 'quantidade' => 20, 'unidade' => 'g'],
                ]
            ],
            [
                'nome' => 'Sopa de Abóbora',
                'categoria' => 'Sopas e Caldos',
                'modo_preparacao' => 'Refogue cebola e alho, adicione a abóbora em cubos e o caldo. Cozinhe até amolecer, bata no liquidificador e volte ao fogo. Tempere e finalize com creme de leite.',
                'tempo_cozimento' => 30,
                'numero_porcoes' => 4,
                'observacoes' => 'Pode ser congelada por até 3 meses.',
                'ingredientes' => [
                    ['nome' => 'Batata', 'quantidade' => 600, 'unidade' => 'g'],
                    ['nome' => 'Cebola', 'quantidade' => 1, 'unidade' => 'unidade'],
                    ['nome' => 'Alho', 'quantidade' => 15, 'unidade' => 'g'],
                    ['nome' => 'Creme de leite', 'quantidade' => 200, 'unidade' => 'mL'],
                ]
            ],
            [
                'nome' => 'Smoothie Detox',
                'categoria' => 'Bebidas',
                'modo_preparacao' => 'Bata todos os ingredientes no liquidificador até ficar homogêneo. Sirva imediatamente bem gelado.',
                'tempo_cozimento' => 5,
                'numero_porcoes' => 2,
                'observacoes' => 'Ideal para consumir pela manhã.',
                'ingredientes' => [
                    ['nome' => 'Espinafre', 'quantidade' => 1, 'unidade' => 'maço'],
                    ['nome' => 'Banana', 'quantidade' => 200, 'unidade' => 'g'],
                    ['nome' => 'Maçã', 'quantidade' => 150, 'unidade' => 'g'],
                    ['nome' => 'Limão', 'quantidade' => 50, 'unidade' => 'g'],
                ]
            ],
        ];

        foreach ($receitas as $receitaData) {
            $categoria = $categorias->where('nome', $receitaData['categoria'])->first();
            $cozinheiro = $cozinheiros->random();

            $receita = Receita::create([
                'nome' => $receitaData['nome'],
                'user_id' => $cozinheiro->id,
                'categoria_id' => $categoria->id,
                'modo_preparacao' => $receitaData['modo_preparacao'],
                'tempo_cozimento' => $receitaData['tempo_cozimento'],
                'numero_porcoes' => $receitaData['numero_porcoes'],
                'observacoes' => $receitaData['observacoes'],
                'publicada' => true,
            ]);

            // Adicionar ingredientes
            foreach ($receitaData['ingredientes'] as $ingredienteData) {
                $ingrediente = $ingredientes->where('nome', $ingredienteData['nome'])->first();
                if ($ingrediente) {
                    $receita->ingredientes()->attach($ingrediente->id, [
                        'quantidade' => $ingredienteData['quantidade'],
                        'unidade' => $ingredienteData['unidade'],
                    ]);
                }
            }
        }

        // Criar receitas adicionais usando factory
        Receita::factory(15)->create();
    }
}
