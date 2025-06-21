<?php

// app/Http/Controllers/TesteController.php
namespace App\Http\Controllers;

use App\Models\Teste;
use App\Models\Receita;
use App\Models\Degustador;
use App\Models\Avaliacao;
use App\Http\Requests\TesteRequest;
use App\Http\Requests\AvaliacaoRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

class TesteController extends BaseController
{
    use AuthorizesRequests;
    
    public function index(Request $request): Response
    {
        $query = Teste::with(['receita.user', 'receita.categoria', 'degustador', 'avaliacao']);

        // Aplicar filtros
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('degustador') && $request->degustador) {
            $query->where('degustador_id', $request->degustador);
        }

        if ($request->has('data_inicio') && $request->has('data_fim')) {
            $query->porPeriodo($request->data_inicio, $request->data_fim);
        }

        if ($request->has('receita') && $request->receita) {
            $query->where('receita_id', $request->receita);
        }

        // Filtro por cozinheiro (se não for admin)
        if ($request->user()->isCozinheiro()) {
            $query->whereHas('receita', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            });
        }

        // Ordenação
        $orderBy = $request->get('order_by', 'data_teste');
        $orderDirection = $request->get('order_direction', 'desc');
        $query->orderBy($orderBy, $orderDirection);

        $testes = $query->paginate(15)->withQueryString();
        $degustadores = Degustador::ativo()->get();
        $receitas = Receita::publicadas()->with('user')->get();

        return Inertia::render('Testes/Index', [
            'testes' => $testes,
            'degustadores' => $degustadores,
            'receitas' => $receitas,
            'filters' => $request->only(['status', 'degustador', 'data_inicio', 'data_fim', 'receita', 'order_by', 'order_direction']),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Teste::class);

        $receitas = Receita::publicadas()
            ->with('user', 'categoria')
            ->when(auth()->user()->isCozinheiro(), function ($query) {
                return $query->where('user_id', auth()->id());
            })
            ->get();

        $degustadores = Degustador::ativo()->get();

        return Inertia::render('Testes/Create', [
            'receitas' => $receitas,
            'degustadores' => $degustadores,
        ]);
    }

    public function store(TesteRequest $request): RedirectResponse
    {
        $this->authorize('create', Teste::class);

        $teste = Teste::create($request->validated());

        return redirect()->route('testes.index')
            ->with('success', 'Teste agendado com sucesso!');
    }

    public function show(Teste $teste): Response
    {
        $this->authorize('view', $teste);

        $teste->load([
            'receita.user',
            'receita.categoria',
            'receita.ingredientes',
            'degustador',
            'avaliacao'
        ]);

        // Carregar fotos do teste
        $teste->fotos_media = $teste->getMedia('fotos_teste');

        return Inertia::render('Testes/Show', [
            'teste' => $teste,
        ]);
    }

    public function avaliar(Teste $teste): Response
    {
        $this->authorize('avaliar', $teste);

        if (!$teste->podeSerAvaliado()) {
            return redirect()->route('testes.show', $teste)
                ->with('error', 'Este teste não pode ser avaliado no status atual.');
        }

        $teste->load(['receita.user', 'receita.categoria', 'degustador', 'avaliacao']);

        return Inertia::render('Testes/Avaliar', [
            'teste' => $teste,
        ]);
    }

    public function salvarAvaliacao(AvaliacaoRequest $request, Teste $teste): RedirectResponse
    {
        $this->authorize('avaliar', $teste);

        DB::transaction(function () use ($request, $teste) {
            // Criar ou atualizar avaliação
            $avaliacao = $teste->avaliacao ?: new Avaliacao(['teste_id' => $teste->id]);
            $avaliacao->fill($request->validated());
            $avaliacao->save();

            // Atualizar observações pós-teste
            $teste->update([
                'observacoes_pos_teste' => $request->input('observacoes_pos_teste'),
                'status' => 'concluido'
            ]);

            // Processar upload de fotos do teste
            if ($request->hasFile('fotos_teste')) {
                foreach ($request->file('fotos_teste') as $foto) {
                    $teste->addMediaFromRequest('fotos_teste')
                        ->each(function ($fileAdder) {
                            $fileAdder->toMediaCollection('fotos_teste');
                        });
                }
            }
        });

        return redirect()->route('testes.show', $teste)
            ->with('success', 'Avaliação salva com sucesso!');
    }

    public function updateStatus(Request $request, Teste $teste): RedirectResponse
    {
        $this->authorize('update', $teste);

        $request->validate([
            'status' => 'required|in:agendado,em_andamento,concluido,cancelado',
        ]);

        $teste->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Status do teste atualizado com sucesso!');
    }

    public function destroy(Teste $teste): RedirectResponse
    {
        $this->authorize('delete', $teste);

        $teste->delete();

        return redirect()->route('testes.index')
            ->with('success', 'Teste excluído com sucesso!');
    }
}
