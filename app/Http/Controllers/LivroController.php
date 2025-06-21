<?php

// app/Http/Controllers/LivroController.php
namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Editor;
use App\Models\Receita;
use App\Http\Requests\LivroRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LivroController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Livro::class);

        $query = Livro::with('editor');

        // Aplicar filtros
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('editor') && $request->editor) {
            $query->where('editor_id', $request->editor);
        }

        // Ordenação
        $orderBy = $request->get('order_by', 'created_at');
        $orderDirection = $request->get('order_direction', 'desc');
        $query->orderBy($orderBy, $orderDirection);

        $livros = $query->paginate(15)->withQueryString();
        $editores = Editor::ativo()->get();

        return Inertia::render('Livros/Index', [
            'livros' => $livros,
            'editores' => $editores,
            'filters' => $request->only(['search', 'status', 'editor', 'order_by', 'order_direction']),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Livro::class);

        $editores = Editor::ativo()->get();
        $receitas = Receita::publicadas()->with('user')->get();

        return Inertia::render('Livros/Create', [
            'editores' => $editores,
            'receitas' => $receitas,
        ]);
    }

    public function store(LivroRequest $request): RedirectResponse
    {
        $this->authorize('create', Livro::class);

        $livro = Livro::create($request->validated());

        // Processar upload da capa
        if ($request->hasFile('capa')) {
            $livro->addMediaFromRequest('capa')
                ->toMediaCollection('capa');
        }

        return redirect()->route('livros.index')
            ->with('success', 'Livro criado com sucesso!');
    }

    public function show(Livro $livro): Response
    {
        $this->authorize('view', $livro);

        $livro->load('editor');
        $receitas = $livro->getReceitasIncluidas();

        // Carregar capa
        $livro->capa_media = $livro->getFirstMedia('capa');

        return Inertia::render('Livros/Show', [
            'livro' => $livro,
            'receitas' => $receitas,
        ]);
    }

    public function edit(Livro $livro): Response
    {
        $this->authorize('update', $livro);

        $editores = Editor::ativo()->get();
        $receitas = Receita::publicadas()->with('user')->get();
        $receitasIncluidas = $livro->getReceitasIncluidas();

        // Carregar capa existente
        $livro->capa_existente = $livro->getFirstMedia('capa');

        return Inertia::render('Livros/Edit', [
            'livro' => $livro,
            'editores' => $editores,
            'receitas' => $receitas,
            'receitasIncluidas' => $receitasIncluidas,
        ]);
    }

    public function update(LivroRequest $request, Livro $livro): RedirectResponse
    {
        $this->authorize('update', $livro);

        $livro->update($request->validated());

        // Processar nova capa
        if ($request->hasFile('capa')) {
            $livro->clearMediaCollection('capa');
            $livro->addMediaFromRequest('capa')
                ->toMediaCollection('capa');
        }

        return redirect()->route('livros.show', $livro)
            ->with('success', 'Livro atualizado com sucesso!');
    }

    public function destroy(Livro $livro): RedirectResponse
    {
        $this->authorize('delete', $livro);

        $livro->delete();

        return redirect()->route('livros.index')
            ->with('success', 'Livro excluído com sucesso!');
    }

    public function updateStatus(Request $request, Livro $livro): RedirectResponse
    {
        $this->authorize('update', $livro);

        $request->validate([
            'status' => 'required|in:rascunho,em_revisao,publicado'
        ]);

        $livro->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Status do livro atualizado com sucesso!');
    }

    public function adicionarReceita(Request $request, Livro $livro): RedirectResponse
    {
        $this->authorize('update', $livro);

        $request->validate([
            'receita_id' => 'required|exists:receitas,id'
        ]);

        $receita = Receita::findOrFail($request->receita_id);
        $livro->adicionarReceita($receita);

        return redirect()->back()
            ->with('success', 'Receita adicionada ao livro com sucesso!');
    }

    public function removerReceita(Livro $livro, Receita $receita): RedirectResponse
    {
        $this->authorize('update', $livro);

        $livro->removerReceita($receita);

        return redirect()->back()
            ->with('success', 'Receita removida do livro com sucesso!');
    }
}
