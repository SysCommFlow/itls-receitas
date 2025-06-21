<?php

// app/Http/Controllers/RelatorioController.php
namespace App\Http\Controllers;

use App\Models\Receita;
use App\Models\Teste;
use App\Models\User;
use App\Models\Restaurante;
use App\Models\Categoria;
use App\Models\Avaliacao;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Relatorios/Index');
    }

    public function receitasPorCozinheiro(Request $request): JsonResponse
    {
        $query = User::where('tipo_usuario', 'cozinheiro')
            ->withCount([
                'receitas',
                'receitas as receitas_publicadas_count' => function ($q) {
                    $q->where('publicada', true);
                },
                'receitas as receitas_testadas_count' => function ($q) {
                    $q->where('testada', true);
                }
            ])
            ->with(['receitas' => function ($q) {
                $q->select('id', 'user_id', 'nome', 'nota_media', 'created_at')
                  ->orderBy('created_at', 'desc')
                  ->limit(5);
            }]);

        if ($request->has('periodo')) {
            $periodo = $request->periodo;
            $dataInicio = match($periodo) {
                '7d' => now()->subDays(7),
                '30d' => now()->subDays(30),
                '90d' => now()->subDays(90),
                '1y' => now()->subYear(),
                default => null
            };

            if ($dataInicio) {
                $query->whereHas('receitas', function ($q) use ($dataInicio) {
                    $q->where('created_at', '>=', $dataInicio);
                });
            }
        }

        $cozinheiros = $query->get();

        return response()->json([
            'data' => $cozinheiros,
            'estatisticas' => [
                'total_cozinheiros' => $cozinheiros->count(),
                'total_receitas' => $cozinheiros->sum('receitas_count'),
                'media_receitas_por_cozinheiro' => $cozinheiros->avg('receitas_count'),
                'cozinheiro_mais_produtivo' => $cozinheiros->sortByDesc('receitas_count')->first()
            ]
        ]);
    }

    public function testesPorPeriodo(Request $request): JsonResponse
    {
        $dataInicio = $request->data_inicio ? date('Y-m-d', strtotime($request->data_inicio)) : now()->subMonth()->format('Y-m-d');
        $dataFim = $request->data_fim ? date('Y-m-d', strtotime($request->data_fim)) : now()->format('Y-m-d');

        $testes = Teste::with(['receita.user', 'degustador', 'avaliacao'])
            ->whereBetween('data_teste', [$dataInicio, $dataFim])
            ->get();

        $estatisticas = [
            'total_testes' => $testes->count(),
            'testes_concluidos' => $testes->where('status', 'concluido')->count(),
            'testes_pendentes' => $testes->where('status', 'agendado')->count(),
            'testes_em_andamento' => $testes->where('status', 'em_andamento')->count(),
            'testes_cancelados' => $testes->where('status', 'cancelado')->count(),
            'nota_media_geral' => $testes->whereNotNull('avaliacao')->avg('avaliacao.nota_geral'),
            'receitas_mais_testadas' => $testes->groupBy('receita_id')
                ->map(function ($grupo) {
                    return [
                        'receita' => $grupo->first()->receita->nome,
                        'total_testes' => $grupo->count(),
                        'nota_media' => $grupo->whereNotNull('avaliacao')->avg('avaliacao.nota_geral')
                    ];
                })
                ->sortByDesc('total_testes')
                ->take(5)
                ->values()
        ];

        return response()->json([
            'data' => $testes,
            'estatisticas' => $estatisticas,
            'periodo' => [
                'inicio' => $dataInicio,
                'fim' => $dataFim
            ]
        ]);
    }

    public function restaurantesEPratos(): JsonResponse
    {
        $restaurantes = Restaurante::where('ativo', true)
            ->get()
            ->map(function ($restaurante) {
                $pratos = $restaurante->pratosConfeccionados();
                return [
                    'id' => $restaurante->id,
                    'nome' => $restaurante->nome,
                    'tipo_cozinha' => $restaurante->tipo_cozinha,
                    'endereco' => $restaurante->endereco,
                    'nota_media' => $restaurante->nota_media,
                    'total_pratos' => $pratos->count(),
                    'pratos' => $pratos->map(function ($prato) {
                        return [
                            'nome' => $prato->nome,
                            'categoria' => $prato->categoria->nome,
                            'nota_media' => $prato->nota_media,
                            'testada' => $prato->testada
                        ];
                    })
                ];
            });

        $estatisticas = [
            'total_restaurantes' => $restaurantes->count(),
            'tipos_cozinha' => $restaurantes->groupBy('tipo_cozinha')->map->count(),
            'restaurante_mais_pratos' => $restaurantes->sortByDesc('total_pratos')->first(),
            'media_pratos_por_restaurante' => $restaurantes->avg('total_pratos')
        ];

        return response()->json([
            'data' => $restaurantes,
            'estatisticas' => $estatisticas
        ]);
    }

    public function estatisticasGerais(): JsonResponse
    {
        $totalReceitas = Receita::count();
        $receitasPublicadas = Receita::where('publicada', true)->count();
        $receitasTestadas = Receita::where('testada', true)->count();
        $totalTestes = Teste::count();
        $testesCompletos = Teste::where('status', 'concluido')->count();
        $totalCozinheiros = User::where('tipo_usuario', 'cozinheiro')->count();
        $totalCategorias = Categoria::where('ativo', true)->count();

        // Receitas por categoria
        $receitasPorCategoria = Categoria::withCount('receitas')
            ->where('ativo', true)
            ->get();

        // Evolução mensal de receitas
        $evolucaoReceitas = Receita::selectRaw('YEAR(created_at) as ano, MONTH(created_at) as mes, COUNT(*) as total')
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('ano', 'mes')
            ->orderBy('ano', 'mes')
            ->get();

        // Top 10 receitas mais bem avaliadas
        $topReceitas = Receita::whereNotNull('nota_media')
            ->where('testada', true)
            ->orderBy('nota_media', 'desc')
            ->limit(10)
            ->with(['user', 'categoria'])
            ->get();

        return response()->json([
            'resumo' => [
                'total_receitas' => $totalReceitas,
                'receitas_publicadas' => $receitasPublicadas,
                'receitas_testadas' => $receitasTestadas,
                'total_testes' => $totalTestes,
                'testes_completos' => $testesCompletos,
                'total_cozinheiros' => $totalCozinheiros,
                'total_categorias' => $totalCategorias,
                'taxa_aprovacao' => $testesCompletos > 0 ? ($receitasTestadas / $testesCompletos) * 100 : 0
            ],
            'receitas_por_categoria' => $receitasPorCategoria,
            'evolucao_receitas' => $evolucaoReceitas,
            'top_receitas' => $topReceitas
        ]);
    }

    public function exportarPdf(Request $request)
    {
        $tipo = $request->query('tipo', 'geral');

        switch ($tipo) {
            case 'cozinheiros':
                return $this->exportarRelatorioCozinheiros($request);
            case 'testes':
                return $this->exportarRelatorioTestes($request);
            case 'restaurantes':
                return $this->exportarRelatorioRestaurantes($request);
            default:
                return $this->exportarRelatorioGeral($request);
        }
    }

    private function exportarRelatorioGeral(Request $request)
    {
        $dados = $this->estatisticasGerais()->getData(true);

        $pdf = Pdf::loadView('relatorios.geral', [
            'dados' => $dados,
            'data_geracao' => now()->format('d/m/Y H:i')
        ]);

        return $pdf->download('relatorio-geral-' . now()->format('Y-m-d') . '.pdf');
    }

    private function exportarRelatorioCozinheiros(Request $request)
    {
        $dados = $this->receitasPorCozinheiro($request)->getData(true);

        $pdf = Pdf::loadView('relatorios.cozinheiros', [
            'dados' => $dados,
            'data_geracao' => now()->format('d/m/Y H:i')
        ]);

        return $pdf->download('relatorio-cozinheiros-' . now()->format('Y-m-d') . '.pdf');
    }

    private function exportarRelatorioTestes(Request $request)
    {
        $dados = $this->testesPorPeriodo($request)->getData(true);

        $pdf = Pdf::loadView('relatorios.testes', [
            'dados' => $dados,
            'data_geracao' => now()->format('d/m/Y H:i')
        ]);

        return $pdf->download('relatorio-testes-' . now()->format('Y-m-d') . '.pdf');
    }

    private function exportarRelatorioRestaurantes(Request $request)
    {
        $dados = $this->restaurantesEPratos()->getData(true);

        $pdf = Pdf::loadView('relatorios.restaurantes', [
            'dados' => $dados,
            'data_geracao' => now()->format('d/m/Y H:i')
        ]);

        return $pdf->download('relatorio-restaurantes-' . now()->format('Y-m-d') . '.pdf');
    }

    public function dashboard(): JsonResponse
    {
        // Dados específicos para o dashboard
        $hoje = now();
        $semanaPassada = now()->subWeek();
        $mesPassado = now()->subMonth();

        $dadosDashboard = [
            'novos_hoje' => [
                'receitas' => Receita::whereDate('created_at', $hoje)->count(),
                'testes' => Teste::whereDate('created_at', $hoje)->count(),
                'avaliacoes' => Avaliacao::whereDate('created_at', $hoje)->count(),
            ],
            'crescimento_semanal' => [
                'receitas' => $this->calcularCrescimento('receitas', $semanaPassada),
                'testes' => $this->calcularCrescimento('testes', $semanaPassada),
                'usuarios' => $this->calcularCrescimento('users', $semanaPassada),
            ],
            'top_categorias' => Categoria::withCount('receitas')
                ->orderBy('receitas_count', 'desc')
                ->take(5)
                ->get(),
            'atividade_recente' => $this->obterAtividadeRecente(),
            'metricas_qualidade' => [
                'nota_media_geral' => Avaliacao::avg('nota_geral'),
                'taxa_recomendacao' => $this->calcularTaxaRecomendacao(),
                'receitas_5_estrelas' => Receita::where('nota_media', '>=', 9)->count(),
            ]
        ];

        return response()->json($dadosDashboard);
    }

    private function calcularCrescimento(string $tabela, $dataInicio): float
    {
        $total = DB::table($tabela)->count();
        $anteriores = DB::table($tabela)->where('created_at', '<', $dataInicio)->count();

        if ($anteriores == 0) return 100;

        return (($total - $anteriores) / $anteriores) * 100;
    }

    private function calcularTaxaRecomendacao(): float
    {
        $totalAvaliacoes = Avaliacao::count();
        $recomendadas = Avaliacao::where('recomenda', true)->count();

        return $totalAvaliacoes > 0 ? ($recomendadas / $totalAvaliacoes) * 100 : 0;
    }

    private function obterAtividadeRecente(): array
    {
        $atividades = [];

        // Receitas recentes
        $receitasRecentes = Receita::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($receita) {
                return [
                    'tipo' => 'receita_criada',
                    'descricao' => "Nova receita: {$receita->nome}",
                    'usuario' => $receita->user->name,
                    'data' => $receita->created_at,
                    'link' => route('receitas.show', $receita)
                ];
            });

        // Testes recentes
        $testesRecentes = Teste::with(['receita', 'degustador'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($teste) {
                return [
                    'tipo' => 'teste_agendado',
                    'descricao' => "Teste agendado: {$teste->receita->nome}",
                    'usuario' => $teste->degustador->nome,
                    'data' => $teste->created_at,
                    'link' => route('testes.show', $teste)
                ];
            });

        // Avaliações recentes
        $avaliacoesRecentes = Avaliacao::with(['teste.receita', 'teste.degustador'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($avaliacao) {
                return [
                    'tipo' => 'avaliacao_criada',
                    'descricao' => "Avaliação: {$avaliacao->teste->receita->nome} - Nota: {$avaliacao->nota_geral}",
                    'usuario' => $avaliacao->teste->degustador->nome,
                    'data' => $avaliacao->created_at,
                    'link' => route('testes.show', $avaliacao->teste)
                ];
            });

        // Combinar e ordenar por data
        $atividades = collect()
            ->concat($receitasRecentes)
            ->concat($testesRecentes)
            ->concat($avaliacoesRecentes)
            ->sortByDesc('data')
            ->take(10)
            ->values();

        return $atividades->toArray();
    }

    public function analiseDesempenho(Request $request): JsonResponse
    {
        $periodo = $request->get('periodo', '30d');
        $dataInicio = match($periodo) {
            '7d' => now()->subDays(7),
            '30d' => now()->subDays(30),
            '90d' => now()->subDays(90),
            '1y' => now()->subYear(),
            default => now()->subDays(30)
        };

        $analise = [
            'periodo' => $periodo,
            'receitas' => [
                'total' => Receita::where('created_at', '>=', $dataInicio)->count(),
                'publicadas' => Receita::where('created_at', '>=', $dataInicio)->where('publicada', true)->count(),
                'testadas' => Receita::where('created_at', '>=', $dataInicio)->where('testada', true)->count(),
                'nota_media' => Receita::where('created_at', '>=', $dataInicio)->whereNotNull('nota_media')->avg('nota_media'),
            ],
            'testes' => [
                'total' => Teste::where('created_at', '>=', $dataInicio)->count(),
                'concluidos' => Teste::where('created_at', '>=', $dataInicio)->where('status', 'concluido')->count(),
                'taxa_conclusao' => $this->calcularTaxaConclusao($dataInicio),
            ],
            'cozinheiros_ativos' => User::where('tipo_usuario', 'cozinheiro')
                ->whereHas('receitas', function ($q) use ($dataInicio) {
                    $q->where('created_at', '>=', $dataInicio);
                })
                ->count(),
            'tendencias' => $this->analisarTendencias($dataInicio),
        ];

        return response()->json($analise);
    }

    private function calcularTaxaConclusao($dataInicio): float
    {
        $totalTestes = Teste::where('created_at', '>=', $dataInicio)->count();
        $testesCompletos = Teste::where('created_at', '>=', $dataInicio)->where('status', 'concluido')->count();

        return $totalTestes > 0 ? ($testesCompletos / $totalTestes) * 100 : 0;
    }

    private function analisarTendencias($dataInicio): array
    {
        // Receitas por dia
        $receitasPorDia = Receita::selectRaw('DATE(created_at) as data, COUNT(*) as total')
            ->where('created_at', '>=', $dataInicio)
            ->groupBy('data')
            ->orderBy('data')
            ->get();

        // Notas médias por semana
        $notasPorSemana = Avaliacao::selectRaw('YEARWEEK(created_at) as semana, AVG(nota_geral) as nota_media')
            ->where('created_at', '>=', $dataInicio)
            ->groupBy('semana')
            ->orderBy('semana')
            ->get();

        return [
            'receitas_por_dia' => $receitasPorDia,
            'notas_por_semana' => $notasPorSemana,
            'crescimento_usuarios' => $this->analisarCrescimentoUsuarios($dataInicio),
        ];
    }

    private function analisarCrescimentoUsuarios($dataInicio): array
    {
        $usuariosPorMes = User::selectRaw('YEAR(created_at) as ano, MONTH(created_at) as mes, COUNT(*) as total')
            ->where('created_at', '>=', $dataInicio)
            ->groupBy('ano', 'mes')
            ->orderBy('ano', 'mes')
            ->get();

        return $usuariosPorMes->map(function ($item) {
            return [
                'periodo' => sprintf('%04d-%02d', $item->ano, $item->mes),
                'total' => $item->total
            ];
        })->toArray();
    }
}
