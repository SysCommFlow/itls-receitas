<?php

// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Receita;
use App\Models\Teste;
use App\Models\Avaliacao;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Degustador;
use App\Models\Restaurante;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();

        // Estatísticas gerais
        $stats = [
            'total_receitas' => Receita::count(),
            'receitas_publicadas' => Receita::where('publicada', true)->count(),
            'receitas_testadas' => Receita::where('testada', true)->count(),
            'testes_pendentes' => Teste::where('status', 'agendado')->count(),
            'testes_concluidos' => Teste::where('status', 'concluido')->count(),
            'total_usuarios' => User::count(),
            'total_degustadores' => Degustador::where('ativo', true)->count(),
            'total_restaurantes' => Restaurante::where('ativo', true)->count(),
        ];

        // Estatísticas específicas por tipo de usuário
        if ($user->isCozinheiro()) {
            $stats['minhas_receitas'] = $user->receitas()->count();
            $stats['receitas_publicadas_minhas'] = $user->receitas()->where('publicada', true)->count();
            $stats['nota_media_minhas'] = $user->nota_media_receitas;
        }

        // Receitas recentes
        $receitasRecentes = Receita::with(['user', 'categoria'])
            ->when($user->isCozinheiro(), function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();

        // Testes recentes
        $testesRecentes = Teste::with(['receita.user', 'receita.categoria', 'degustador', 'avaliacao'])
            ->when($user->isCozinheiro(), function ($query) use ($user) {
                return $query->whereHas('receita', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->latest()
            ->take(5)
            ->get();

        // Gráfico de receitas por categoria
        $receitasPorCategoria = Categoria::withCount('receitas')
            ->where('ativo', true)
            ->get()
            ->map(function ($categoria) {
                return [
                    'nome' => $categoria->nome,
                    'total' => $categoria->receitas_count
                ];
            });
        // Evolução mensal de receitas
        $evolucaoReceitas = Receita::selectRaw('YEAR(created_at) as ano, MONTH(created_at) as mes, COUNT(*) as total')
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('ano', 'mes')
            ->orderBy('ano') // Ordena primeiro por ano
            ->orderBy('mes') // Depois por mês
            ->get()
            ->map(function ($item) {
                return [
                    'periodo' => sprintf('%04d-%02d', $item->ano, $item->mes),
                    'total' => $item->total
                ];
            });

        // Top receitas mais bem avaliadas
        $topReceitas = Receita::with(['user', 'categoria'])
            ->whereNotNull('nota_media')
            ->orderBy('nota_media', 'desc')
            ->take(5)
            ->get();

        return Inertia::render('Dashboard/Index', [
            'stats' => $stats,
            'receitasRecentes' => $receitasRecentes,
            'testesRecentes' => $testesRecentes,
            'receitasPorCategoria' => $receitasPorCategoria,
            'evolucaoReceitas' => $evolucaoReceitas,
            'topReceitas' => $topReceitas,
        ]);
    }
}
