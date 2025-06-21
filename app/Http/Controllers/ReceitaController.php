<?php

namespace App\Http\Controllers;

use App\Models\Receita;
use App\Models\Categoria;
use App\Models\Ingrediente;
use App\Http\Requests\ReceitaRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

class ReceitaController extends BaseController
{
    use AuthorizesRequests;

    public function index(Request $request): Response
    {
        $query = Receita::with(['user', 'categoria', 'ingredientes']);

        // Aplicar filtros
        if ($request->has('categoria') && $request->categoria) {
            $query->where('categoria_id', $request->categoria);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->buscar($search);
        }

        if ($request->has('status') && $request->status) {
            switch ($request->status) {
                case 'publicadas':
                    $query->publicadas();
                    break;
                case 'testadas':
                    $query->testadas();
                    break;
                case 'pendentes':
                    $query->where('testada', false);
                    break;
            }
        }

        // Filtro por cozinheiro (se não for admin)
        if ($request->user()->isCozinheiro()) {
            $query->where('user_id', $request->user()->id);
        }

        // Ordenação
        $orderBy = $request->get('order_by', 'created_at');
        $orderDirection = $request->get('order_direction', 'desc');
        $query->orderBy($orderBy, $orderDirection);

        $receitas = $query->paginate(12)->withQueryString();

        // Carregar imagens para cada receita
        $receitas->getCollection()->transform(function ($receita) {
            $receita->imagens_urls = $receita->getMedia('imagens')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'thumb' => $media->getUrl('thumb'),
                    'preview' => $media->getUrl('preview'),
                ];
            });
            return $receita;
        });

        $categorias = Categoria::ativo()->get();

        return Inertia::render('Receitas/Index', [
            'receitas' => $receitas,
            'categorias' => $categorias,
            'filters' => $request->only(['categoria', 'search', 'status', 'order_by', 'order_direction']),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Receita::class);

        $categorias = Categoria::ativo()->get();
        $ingredientes = Ingrediente::orderBy('nome')->get();

        return Inertia::render('Receitas/Create', [
            'categorias' => $categorias,
            'ingredientes' => $ingredientes,
        ]);
    }

    public function store(ReceitaRequest $request): RedirectResponse
    {
        $this->authorize('create', Receita::class);

        $receita = DB::transaction(function () use ($request) {
            // Criar a receita
            $receita = new Receita($request->validated());
            $receita->user_id = $request->user()->id;
            $receita->save();

            // Processar upload de imagens
            if ($request->hasFile('imagens')) {
                $imagens = $request->file('imagens');

                if (is_array($imagens)) {
                    foreach ($imagens as $index => $imagem) {
                        if ($imagem && $imagem->isValid()) {
                            $receita->addMedia($imagem)
                                ->withCustomProperties(['ordem' => $index + 1])
                                ->toMediaCollection('imagens');
                        }
                    }
                } else {
                    if ($imagens && $imagens->isValid()) {
                        $receita->addMedia($imagens)
                            ->withCustomProperties(['ordem' => 1])
                            ->toMediaCollection('imagens');
                    }
                }
            }

            // Anexar ingredientes
            $ingredientes = $request->input('ingredientes', []);
            foreach ($ingredientes as $ingrediente) {
                if (!empty($ingrediente['id']) && !empty($ingrediente['quantidade'])) {
                    $receita->ingredientes()->attach($ingrediente['id'], [
                        'quantidade' => $ingrediente['quantidade'],
                        'unidade' => $ingrediente['unidade'] ?? '',
                        'observacoes' => $ingrediente['observacoes'] ?? null,
                    ]);
                }
            }

            return $receita;
        });

        return redirect()->route('receitas.index')
            ->with('success', 'Receita criada com sucesso!');
    }

    public function show(Receita $receita): Response
    {
        $this->authorize('view', $receita);

        $receita->load([
            'user',
            'categoria',
            'ingredientes',
            'testes.degustador',
            'testes.avaliacao',
            'avaliacoes'
        ]);

        // Carregar imagens do Spatie Media Library
        $receita->imagens_media = $receita->getMedia('imagens')->map(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
                'preview' => $media->getUrl('preview'),
                'name' => $media->name,
                'file_name' => $media->file_name,
            ];
        });

        // Estatísticas da receita
        $estatisticas = [
            'total_testes' => $receita->testes()->count(),
            'testes_concluidos' => $receita->testes()->concluidos()->count(),
            'nota_media' => $receita->nota_media,
            'total_avaliacoes' => $receita->avaliacoes()->count(),
            'percentual_recomendacao' => $receita->avaliacoes()->count() > 0
                ? ($receita->avaliacoes()->recomendadas()->count() / $receita->avaliacoes()->count()) * 100
                : 0
        ];

        return Inertia::render('Receitas/Show', [
            'receita' => $receita,
            'estatisticas' => $estatisticas,
        ]);
    }

    public function edit(Receita $receita): Response
    {
        $this->authorize('update', $receita);

        $receita->load('ingredientes');
        $categorias = Categoria::ativo()->get();
        $ingredientes = Ingrediente::orderBy('nome')->get();

        // Carregar imagens existentes
        $receita->imagens_existentes = $receita->getMedia('imagens')->map(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
                'preview' => $media->getUrl('preview'),
                'name' => $media->name,
                'file_name' => $media->file_name,
            ];
        });

        return Inertia::render('Receitas/Edit', [
            'receita' => $receita,
            'categorias' => $categorias,
            'ingredientes' => $ingredientes,
        ]);
    }

    public function update(ReceitaRequest $request, Receita $receita): RedirectResponse
    {
        $this->authorize('update', $receita);

        DB::transaction(function () use ($request, $receita) {
            $receita->update($request->validated());

            // Processar novas imagens
            if ($request->hasFile('novas_imagens')) {
                $novasImagens = $request->file('novas_imagens');

                if (is_array($novasImagens)) {
                    foreach ($novasImagens as $index => $imagem) {
                        if ($imagem && $imagem->isValid()) {
                            $ordemAtual = $receita->getMedia('imagens')->count();
                            $receita->addMedia($imagem)
                                ->withCustomProperties(['ordem' => $ordemAtual + $index + 1])
                                ->toMediaCollection('imagens');
                        }
                    }
                } else {
                    if ($novasImagens && $novasImagens->isValid()) {
                        $ordemAtual = $receita->getMedia('imagens')->count();
                        $receita->addMedia($novasImagens)
                            ->withCustomProperties(['ordem' => $ordemAtual + 1])
                            ->toMediaCollection('imagens');
                    }
                }
            }

            // Remover imagens marcadas para exclusão
            $imagensParaRemover = $request->input('imagens_remover', []);
            foreach ($imagensParaRemover as $mediaId) {
                $media = $receita->getMedia('imagens')->where('id', $mediaId)->first();
                if ($media) {
                    $media->delete();
                }
            }

            // Sincronizar ingredientes
            $receita->ingredientes()->detach();
            $ingredientes = $request->input('ingredientes', []);
            foreach ($ingredientes as $ingrediente) {
                if (!empty($ingrediente['id']) && !empty($ingrediente['quantidade'])) {
                    $receita->ingredientes()->attach($ingrediente['id'], [
                        'quantidade' => $ingrediente['quantidade'],
                        'unidade' => $ingrediente['unidade'] ?? '',
                        'observacoes' => $ingrediente['observacoes'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('receitas.show', $receita)
            ->with('success', 'Receita atualizada com sucesso!');
    }

    public function destroy(Receita $receita): RedirectResponse
    {
        $this->authorize('delete', $receita);

        // Remover todas as imagens antes de deletar a receita
        $receita->clearMediaCollection('imagens');

        $receita->delete();

        return redirect()->route('receitas.index')
            ->with('success', 'Receita excluída com sucesso!');
    }

    public function togglePublicacao(Receita $receita): RedirectResponse
    {
        $this->authorize('update', $receita);

        $receita->update(['publicada' => !$receita->publicada]);

        $status = $receita->publicada ? 'publicada' : 'despublicada';

        return redirect()->back()
            ->with('success', "Receita {$status} com sucesso!");
    }
}
