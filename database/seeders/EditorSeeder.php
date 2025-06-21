<?php

// =============================================================================
// database/seeders/EditorSeeder.php
// =============================================================================

namespace Database\Seeders;

use App\Models\Editor;
use Illuminate\Database\Seeder;

class EditorSeeder extends Seeder
{
    public function run(): void
    {
        $editores = [
            [
                'nome' => 'Sandra Publicações',
                'email' => 'sandra@editoragourmet.com',
                'telefone' => '(11) 3333-1111',
                'editora' => 'Editora Gourmet Brasil',
                'especializacoes' => 'Livros de Culinária, Receitas Tradicionais',
                'ativo' => true,
            ],
            [
                'nome' => 'Roberto Editorial',
                'email' => 'roberto@saborlivros.com',
                'telefone' => '(11) 3333-2222',
                'editora' => 'Sabor Livros',
                'especializacoes' => 'Culinária Internacional, Chefs Famosos',
                'ativo' => true,
            ],
            [
                'nome' => 'Mariana Books',
                'email' => 'mariana@cozinhaebooks.com',
                'telefone' => '(11) 3333-3333',
                'editora' => 'Cozinha e Books',
                'especializacoes' => 'E-books, Receitas Digitais, Culinária Moderna',
                'ativo' => true,
            ],
        ];

        foreach ($editores as $editor) {
            Editor::create($editor);
        }
    }
}
