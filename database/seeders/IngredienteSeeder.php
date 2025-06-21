<?php

// =============================================================================
// database/seeders/IngredienteSeeder.php
// =============================================================================

namespace Database\Seeders;

use App\Models\Ingrediente;
use Illuminate\Database\Seeder;

class IngredienteSeeder extends Seeder
{
    public function run(): void
    {
        $ingredientes = [
            // Proteínas
            ['nome' => 'Carne bovina (alcatra)', 'unidade_medida' => 'kg', 'preco_medio' => 35.00, 'exotico' => false],
            ['nome' => 'Frango (peito)', 'unidade_medida' => 'kg', 'preco_medio' => 15.00, 'exotico' => false],
            ['nome' => 'Peixe salmão', 'unidade_medida' => 'kg', 'preco_medio' => 45.00, 'exotico' => true],
            ['nome' => 'Camarão', 'unidade_medida' => 'kg', 'preco_medio' => 50.00, 'exotico' => false],
            ['nome' => 'Ovos', 'unidade_medida' => 'dúzia', 'preco_medio' => 8.00, 'exotico' => false],
            ['nome' => 'Queijo mussarela', 'unidade_medida' => 'kg', 'preco_medio' => 35.00, 'exotico' => false],

            // Vegetais básicos
            ['nome' => 'Tomate', 'unidade_medida' => 'kg', 'preco_medio' => 6.00, 'exotico' => false],
            ['nome' => 'Cebola', 'unidade_medida' => 'kg', 'preco_medio' => 4.00, 'exotico' => false],
            ['nome' => 'Alho', 'unidade_medida' => 'kg', 'preco_medio' => 25.00, 'exotico' => false],
            ['nome' => 'Batata', 'unidade_medida' => 'kg', 'preco_medio' => 3.50, 'exotico' => false],
            ['nome' => 'Cenoura', 'unidade_medida' => 'kg', 'preco_medio' => 4.50, 'exotico' => false],
            ['nome' => 'Abobrinha', 'unidade_medida' => 'kg', 'preco_medio' => 5.00, 'exotico' => false],
            ['nome' => 'Pimentão', 'unidade_medida' => 'kg', 'preco_medio' => 8.00, 'exotico' => false],
            ['nome' => 'Brócolis', 'unidade_medida' => 'kg', 'preco_medio' => 7.00, 'exotico' => false],
            ['nome' => 'Couve-flor', 'unidade_medida' => 'kg', 'preco_medio' => 6.50, 'exotico' => false],

            // Verduras e folhas
            ['nome' => 'Alface', 'unidade_medida' => 'maço', 'preco_medio' => 3.00, 'exotico' => false],
            ['nome' => 'Rúcula', 'unidade_medida' => 'maço', 'preco_medio' => 4.00, 'exotico' => false],
            ['nome' => 'Espinafre', 'unidade_medida' => 'maço', 'preco_medio' => 3.50, 'exotico' => false],
            ['nome' => 'Manjericão', 'unidade_medida' => 'maço', 'preco_medio' => 2.50, 'exotico' => false],
            ['nome' => 'Salsa', 'unidade_medida' => 'maço', 'preco_medio' => 2.00, 'exotico' => false],
            ['nome' => 'Cebolinha', 'unidade_medida' => 'maço', 'preco_medio' => 2.00, 'exotico' => false],

            // Temperos e especiarias
            ['nome' => 'Sal', 'unidade_medida' => 'kg', 'preco_medio' => 2.00, 'exotico' => false],
            ['nome' => 'Pimenta-do-reino', 'unidade_medida' => 'g', 'preco_medio' => 0.05, 'exotico' => false],
            ['nome' => 'Açafrão', 'unidade_medida' => 'g', 'preco_medio' => 2.00, 'exotico' => true],
            ['nome' => 'Orégano', 'unidade_medida' => 'g', 'preco_medio' => 0.02, 'exotico' => false],
            ['nome' => 'Cominho', 'unidade_medida' => 'g', 'preco_medio' => 0.08, 'exotico' => false],
            ['nome' => 'Páprica', 'unidade_medida' => 'g', 'preco_medio' => 0.06, 'exotico' => false],
            ['nome' => 'Curry em pó', 'unidade_medida' => 'g', 'preco_medio' => 0.12, 'exotico' => true],

            // Laticínios
            ['nome' => 'Leite integral', 'unidade_medida' => 'L', 'preco_medio' => 4.50, 'exotico' => false],
            ['nome' => 'Manteiga', 'unidade_medida' => 'kg', 'preco_medio' => 18.00, 'exotico' => false],
            ['nome' => 'Creme de leite', 'unidade_medida' => 'L', 'preco_medio' => 8.00, 'exotico' => false],
            ['nome' => 'Iogurte natural', 'unidade_medida' => 'kg', 'preco_medio' => 12.00, 'exotico' => false],
            ['nome' => 'Queijo parmesão', 'unidade_medida' => 'kg', 'preco_medio' => 55.00, 'exotico' => false],
            ['nome' => 'Requeijão', 'unidade_medida' => 'kg', 'preco_medio' => 20.00, 'exotico' => false],

            // Grãos e cereais
            ['nome' => 'Arroz branco', 'unidade_medida' => 'kg', 'preco_medio' => 4.00, 'exotico' => false],
            ['nome' => 'Feijão preto', 'unidade_medida' => 'kg', 'preco_medio' => 6.00, 'exotico' => false],
            ['nome' => 'Feijão carioca', 'unidade_medida' => 'kg', 'preco_medio' => 5.50, 'exotico' => false],
            ['nome' => 'Farinha de trigo', 'unidade_medida' => 'kg', 'preco_medio' => 3.00, 'exotico' => false],
            ['nome' => 'Macarrão espaguete', 'unidade_medida' => 'kg', 'preco_medio' => 5.00, 'exotico' => false],
            ['nome' => 'Macarrão penne', 'unidade_medida' => 'kg', 'preco_medio' => 5.50, 'exotico' => false],
            ['nome' => 'Quinoa', 'unidade_medida' => 'kg', 'preco_medio' => 25.00, 'exotico' => true],
            ['nome' => 'Aveia', 'unidade_medida' => 'kg', 'preco_medio' => 8.00, 'exotico' => false],

            // Óleos e gorduras
            ['nome' => 'Óleo de soja', 'unidade_medida' => 'L', 'preco_medio' => 6.00, 'exotico' => false],
            ['nome' => 'Azeite de oliva', 'unidade_medida' => 'L', 'preco_medio' => 25.00, 'exotico' => false],
            ['nome' => 'Óleo de coco', 'unidade_medida' => 'L', 'preco_medio' => 35.00, 'exotico' => true],

            // Frutas
            ['nome' => 'Limão', 'unidade_medida' => 'kg', 'preco_medio' => 5.00, 'exotico' => false],
            ['nome' => 'Banana', 'unidade_medida' => 'kg', 'preco_medio' => 4.00, 'exotico' => false],
            ['nome' => 'Maçã', 'unidade_medida' => 'kg', 'preco_medio' => 7.00, 'exotico' => false],
            ['nome' => 'Abacate', 'unidade_medida' => 'kg', 'preco_medio' => 8.00, 'exotico' => false],
            ['nome' => 'Manga', 'unidade_medida' => 'kg', 'preco_medio' => 6.00, 'exotico' => false],

            // Ingredientes exóticos
            ['nome' => 'Trufa negra', 'unidade_medida' => 'g', 'preco_medio' => 50.00, 'exotico' => true],
            ['nome' => 'Caviar', 'unidade_medida' => 'g', 'preco_medio' => 80.00, 'exotico' => true],
            ['nome' => 'Foie gras', 'unidade_medida' => 'g', 'preco_medio' => 15.00, 'exotico' => true],
            ['nome' => 'Flor de sal', 'unidade_medida' => 'g', 'preco_medio' => 0.50, 'exotico' => true],
            ['nome' => 'Wasabi', 'unidade_medida' => 'g', 'preco_medio' => 3.00, 'exotico' => true],
            ['nome' => 'Molho de soja', 'unidade_medida' => 'mL', 'preco_medio' => 0.02, 'exotico' => false],
            ['nome' => 'Miso', 'unidade_medida' => 'g', 'preco_medio' => 0.15, 'exotico' => true],

            // Outros ingredientes
            ['nome' => 'Açúcar cristal', 'unidade_medida' => 'kg', 'preco_medio' => 3.50, 'exotico' => false],
            ['nome' => 'Açúcar mascavo', 'unidade_medida' => 'kg', 'preco_medio' => 8.00, 'exotico' => false],
            ['nome' => 'Mel', 'unidade_medida' => 'kg', 'preco_medio' => 25.00, 'exotico' => false],
            ['nome' => 'Vinagre balsâmico', 'unidade_medida' => 'mL', 'preco_medio' => 0.08, 'exotico' => false],
            ['nome' => 'Vinho branco', 'unidade_medida' => 'mL', 'preco_medio' => 0.03, 'exotico' => false],
            ['nome' => 'Caldo de legumes', 'unidade_medida' => 'mL', 'preco_medio' => 0.01, 'exotico' => false],
        ];

        foreach ($ingredientes as $ingrediente) {
            Ingrediente::create($ingrediente);
        }
    }
}
