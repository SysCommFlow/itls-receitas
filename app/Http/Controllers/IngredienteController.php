<?php

// app/Http/Controllers/IngredienteController.php
namespace App\Http\Controllers;

use App\Models\Ingrediente;
use App\Http\Requests\IngredienteRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

class IngredienteController extends BaseController
{
    use AuthorizesRequests;

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Ingrediente::class);

        $query = Ingrediente::query();

        // Aplicar filtros
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        if ($request->has('exotico') && $request->exotico !== '') {
            $query->where('exotico', $request->boolean('exotico'));
        }

        if ($request->has('unidade') && $request->unidade) {
            $query->where('unidade_medida', $request->unidade);
        }

        // Ordenação
        $orderBy = $request->get('order_by', 'nome');
        $orderDirection = $request->get('order_direction', 'asc');
        $query->orderBy($orderBy, $orderDirection);

        $ingredientes = $query->withCount('receitas')->paginate(20)->withQueryString();

        // Unidades de medida disponíveis
        $unidades = Ingrediente::distinct()->pluck('unidade_medida')->sort();

        return Inertia::render('Ingredientes/Index', [
            'ingredientes' => $ingredientes,
            'unidades' => $unidades,
            'filters' => $request->only(['search', 'exotico', 'unidade', 'order_by', 'order_direction']),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Ingrediente::class);

        return Inertia::render('Ingredientes/Create');
    }

    public function store(IngredienteRequest $request): RedirectResponse
    {
        $this->authorize('create', Ingrediente::class);

        Ingrediente::create($request->validated());

        return redirect()->route('ingredientes.index')
            ->with('success', 'Ingrediente criado com sucesso!');
    }

    public function show(Ingrediente $ingrediente): Response
    {
        $this->authorize('view', $ingrediente);

        $ingrediente->load(['receitas.user', 'receitas.categoria']);

        return Inertia::render('Ingredientes/Show', [
            'ingrediente' => $ingrediente,
        ]);
    }

    public function edit(Ingrediente $ingrediente): Response
    {
        $this->authorize('update', $ingrediente);

        return Inertia::render('Ingredientes/Edit', [
            'ingrediente' => $ingrediente,
        ]);
    }

    public function update(IngredienteRequest $request, Ingrediente $ingrediente): RedirectResponse
    {
        $this->authorize('update', $ingrediente);

        $ingrediente->update($request->validated());

        return redirect()->route('ingredientes.show', $ingrediente)
            ->with('success', 'Ingrediente atualizado com sucesso!');
    }

    public function destroy(Ingrediente $ingrediente): RedirectResponse
    {
        $this->authorize('delete', $ingrediente);

        if ($ingrediente->receitas()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir um ingrediente que está sendo usado em receitas.');
        }

        $ingrediente->delete();

        return redirect()->route('ingredientes.index')
            ->with('success', 'Ingrediente excluído com sucesso!');
    }
}
