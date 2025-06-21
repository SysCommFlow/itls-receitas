<?php

// app/Http/Controllers/CategoriaController.php
namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Http\Requests\CategoriaRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CategoriaController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Categoria::class);

        $query = Categoria::query();

        // Aplicar filtros
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        if ($request->has('ativo') && $request->ativo !== '') {
            $query->where('ativo', $request->boolean('ativo'));
        }

        // Ordenação
        $orderBy = $request->get('order_by', 'nome');
        $orderDirection = $request->get('order_direction', 'asc');
        $query->orderBy($orderBy, $orderDirection);

        $categorias = $query->withCount('receitas')->paginate(15)->withQueryString();

        return Inertia::render('Categorias/Index', [
            'categorias' => $categorias,
            'filters' => $request->only(['search', 'ativo', 'order_by', 'order_direction']),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Categoria::class);

        return Inertia::render('Categorias/Create');
    }

    public function store(CategoriaRequest $request): RedirectResponse
    {
        $this->authorize('create', Categoria::class);

        Categoria::create($request->validated());

        return redirect()->route('categorias.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function show(Categoria $categoria): Response
    {
        $this->authorize('view', $categoria);

        $categoria->load(['receitas.user']);

        return Inertia::render('Categorias/Show', [
            'categoria' => $categoria,
        ]);
    }

    public function edit(Categoria $categoria): Response
    {
        $this->authorize('update', $categoria);

        return Inertia::render('Categorias/Edit', [
            'categoria' => $categoria,
        ]);
    }

    public function update(CategoriaRequest $request, Categoria $categoria): RedirectResponse
    {
        $this->authorize('update', $categoria);

        $categoria->update($request->validated());

        return redirect()->route('categorias.show', $categoria)
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        $this->authorize('delete', $categoria);

        if ($categoria->receitas()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir uma categoria que possui receitas associadas.');
        }

        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoria excluída com sucesso!');
    }

    public function toggleStatus(Categoria $categoria): RedirectResponse
    {
        $this->authorize('update', $categoria);

        $categoria->update(['ativo' => !$categoria->ativo]);

        $status = $categoria->ativo ? 'ativada' : 'desativada';

        return redirect()->back()
            ->with('success', "Categoria {$status} com sucesso!");
    }
}
