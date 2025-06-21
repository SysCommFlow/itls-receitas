<?php

// =============================================================================
// database/seeders/TesteSeeder.php
// =============================================================================

namespace Database\Seeders;

use App\Models\Teste;
use App\Models\Receita;
use App\Models\Degustador;
use App\Models\Avaliacao;
use Illuminate\Database\Seeder;

class TesteSeeder extends Seeder
{
    public function run(): void
    {
        $receitas = Receita::where('publicada', true)->get();
        $degustadores = Degustador::where('ativo', true)->get();

        if ($receitas->isEmpty() || $degustadores->isEmpty()) {
            $this->command->error('É necessário ter receitas publicadas e degustadores ativos antes de criar testes.');
            return;
        }

        // Criar alguns testes com diferentes status
        $statusList = ['agendado', 'em_andamento', 'concluido', 'cancelado'];

        for ($i = 0; $i < 20; $i++) {
            $receita = $receitas->random();
            $degustador = $degustadores->random();
            $status = $statusList[array_rand($statusList)];

            // Definir data baseada no status
            $dataBase = match($status) {
                'agendado' => now()->addDays(rand(1, 30)),
                'em_andamento' => now()->subDays(rand(1, 3)),
                'concluido' => now()->subDays(rand(1, 60)),
                'cancelado' => now()->subDays(rand(1, 30)),
            };

            $teste = Teste::create([
                'receita_id' => $receita->id,
                'degustador_id' => $degustador->id,
                'data_teste' => $dataBase,
                'status' => $status,
                'observacoes_pre_teste' => $status !== 'agendado' ? 'Teste realizado conforme protocolo padrão.' : 'Aguardando realização do teste.',
                'observacoes_pos_teste' => in_array($status, ['concluido']) ? 'Teste concluído com sucesso. Receita aprovada.' : null,
            ]);

            // Se o teste está concluído, criar avaliação
            if ($status === 'concluido') {
                $notaSabor = rand(60, 100) / 10;
                $notaApresentacao = rand(60, 100) / 10;
                $notaAroma = rand(60, 100) / 10;
                $notaTextura = rand(60, 100) / 10;

                $avaliacao = Avaliacao::create([
                    'teste_id' => $teste->id,
                    'nota_sabor' => $notaSabor,
                    'nota_apresentacao' => $notaApresentacao,
                    'nota_aroma' => $notaAroma,
                    'nota_textura' => $notaTextura,
                    'comentarios' => $this->gerarComentario($notaSabor),
                    'recomenda' => ($notaSabor + $notaApresentacao + $notaAroma + $notaTextura) / 4 >= 7,
                    'sugestoes_melhoria' => $this->gerarSugestoes(),
                ]);

                // Atualizar nota da receita (assumindo que existe método calcularNotaMedia)
                $receita->refresh();
                if (method_exists($receita, 'calcularNotaMedia')) {
                    $receita->calcularNotaMedia();
                }
            }
        }
    }

    private function gerarComentario(float $nota): string
    {
        if ($nota >= 9) {
            return 'Receita excepcional! Sabores muito bem equilibrados e apresentação impecável. Definitivamente recomendo.';
        } elseif ($nota >= 8) {
            return 'Muito boa receita, sabor agradável e bem executada. Pequenos ajustes podem torná-la ainda melhor.';
        } elseif ($nota >= 7) {
            return 'Receita satisfatória com bom potencial. Alguns aspectos podem ser melhorados.';
        } elseif ($nota >= 6) {
            return 'Receita regular, atende as expectativas básicas mas precisa de alguns ajustes.';
        } else {
            return 'Receita precisa de revisão significativa. Vários aspectos podem ser melhorados.';
        }
    }

    private function gerarSugestoes(): array
    {
        $sugestoes = [
            'Ajustar o tempo de cozimento',
            'Reduzir a quantidade de sal',
            'Adicionar mais temperos',
            'Melhorar a apresentação do prato',
            'Usar ingredientes mais frescos',
            'Ajustar a textura',
            'Equilibrar melhor os sabores',
            'Cuidar da temperatura de servir',
        ];

        // Retornar 1-3 sugestões aleatórias
        $numeroSugestoes = rand(1, 3);
        $selecionadas = array_rand(array_flip($sugestoes), $numeroSugestoes);
        return is_array($selecionadas) ? $selecionadas : [$selecionadas];
    }
}
