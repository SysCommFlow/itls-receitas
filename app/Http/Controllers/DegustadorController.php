<?php

// app/Http/Controllers/DegustadorController.php
namespace App\Http\Controllers;

use App\Models\Degustador;
use App\Http\Requests\DegustadorRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DegustadorController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Degustador::class);

        $query = Degustador::query();

        // Aplicar filtros
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('especializacoes', 'like', "%{$search}%");
            });
        }

        if ($request->has('ativo') && $request->ativo !== '') {
            $query->where('ativo', $request->boolean('ativo'));
        }

        if ($request->has('experiencia_min') && $request->experiencia_min) {
            $query->comExperiencia($request->experiencia_min);
        }

        // Ordenação
        $orderBy = $request->get('order_by', 'nome');
        $orderDirection = $request->get('order_direction', 'asc');
        $query->orderBy($orderBy, $orderDirection);

        $degustadores = $query->withCount(['testes', 'avaliacoes'])->paginate(15)->withQueryString();

        return Inertia::render('Degustadores/Index', [
            'degustadores' => $degustadores,
            'filters' => $request->only(['search', 'ativo', 'experiencia_min', 'order_by', 'order_direction']),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Degustador::class);

        return Inertia::render('Degustadores/Create');
    }

    public function store(DegustadorRequest $request): RedirectResponse
    {
        $this->authorize('create', Degustador::class);

        Degustador::create($request->validated());

        return redirect()->route('degustadores.index')
            ->with('success', 'Degustador criado com sucesso!');
    }

    public function show(Degustador $degustador): Response
    {
        $this->authorize('view', $degustador);

        $degustador->load(['testes.receita', 'testes.avaliacao']);

        // Estatísticas do degustador
        $estatisticas = [
            'total_testes' => $degustador->testes()->count(),
            'testes_concluidos' => $degustador->testes_concluidos_count,
            'testes_pendentes' => $degustador->testes_pendentes_count,
            'nota_media' => $degustador->nota_media_avaliacoes,
            'receitas_recomendadas' => $degustador->avaliacoes()->recomendadas()->count(),
        ];

        return Inertia::render('Degustadores/Show', [
            'degustador' => $degustador,
            'estatisticas' => $estatisticas,
        ]);
    }

    public function edit(Degustador $degustador): Response
    {
        $this->authorize('update', $degustador);

        return Inertia::render('Degustadores/Edit', [
            'degustador' => $degustador,
        ]);
    }

    public function update(DegustadorRequest $request, Degustador $degustador): RedirectResponse
    {
        $this->authorize('update', $degustador);

        $degustador->update($request->validated());

        return redirect()->route('degustadores.show', $degustador)
            ->with('success', 'Degustador atualizado com sucesso!');
    }

    public function destroy(Degustador $degustador): RedirectResponse
    {
        $this->authorize('delete', $degustador);

        if ($degustador->testes()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir um degustador que possui testes associados.');
        }

        $degustador->delete();

        return redirect()->route('degustadores.index')
            ->with('success', 'Degustador excluído com sucesso!');
    }

    public function toggleStatus(Degustador $degustador): RedirectResponse
    {
        $this->authorize('update', $degustador);

        $degustador->update(['ativo' => !$degustador->ativo]);

        $status = $degustador->ativo ? 'ativado' : 'desativado';

        return redirect()->back()
            ->with('success', "Degustador {$status} com sucesso!");
    }
}
