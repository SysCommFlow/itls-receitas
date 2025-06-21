<?php

// app/Http/Controllers/EditorController.php
namespace App\Http\Controllers;

use App\Models\Editor;
use App\Http\Requests\EditorRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class EditorController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Editor::class);

        $query = Editor::query();

        // Aplicar filtros
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('editora', 'like', "%{$search}%");
            });
        }

        if ($request->has('ativo') && $request->ativo !== '') {
            $query->where('ativo', $request->boolean('ativo'));
        }

        // Ordenação
        $orderBy = $request->get('order_by', 'nome');
        $orderDirection = $request->get('order_direction', 'asc');
        $query->orderBy($orderBy, $orderDirection);

        $editores = $query->withCount('livros')->paginate(15)->withQueryString();

        return Inertia::render('Editores/Index', [
            'editores' => $editores,
            'filters' => $request->only(['search', 'ativo', 'order_by', 'order_direction']),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Editor::class);

        return Inertia::render('Editores/Create');
    }

    public function store(EditorRequest $request): RedirectResponse
    {
        $this->authorize('create', Editor::class);

        Editor::create($request->validated());

        return redirect()->route('editores.index')
            ->with('success', 'Editor criado com sucesso!');
    }

    public function show(Editor $editor): Response
    {
        $this->authorize('view', $editor);

        $editor->load('livros');

        // Estatísticas do editor
        $estatisticas = [
            'total_livros' => $editor->livros()->count(),
            'livros_publicados' => $editor->livros_publicados_count,
            'livros_em_andamento' => $editor->livros_em_andamento_count,
        ];

        return Inertia::render('Editores/Show', [
            'editor' => $editor,
            'estatisticas' => $estatisticas,
        ]);
    }

    public function edit(Editor $editor): Response
    {
        $this->authorize('update', $editor);

        return Inertia::render('Editores/Edit', [
            'editor' => $editor,
        ]);
    }

    public function update(EditorRequest $request, Editor $editor): RedirectResponse
    {
        $this->authorize('update', $editor);

        $editor->update($request->validated());

        return redirect()->route('editores.show', $editor)
            ->with('success', 'Editor atualizado com sucesso!');
    }

    public function destroy(Editor $editor): RedirectResponse
    {
        $this->authorize('delete', $editor);

        if ($editor->livros()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir um editor que possui livros associados.');
        }

        $editor->delete();

        return redirect()->route('editores.index')
            ->with('success', 'Editor excluído com sucesso!');
    }

    public function toggleStatus(Editor $editor): RedirectResponse
    {
        $this->authorize('update', $editor);

        $editor->update(['ativo' => !$editor->ativo]);

        $status = $editor->ativo ? 'ativado' : 'desativado';

        return redirect()->back()
            ->with('success', "Editor {$status} com sucesso!");
    }
}
