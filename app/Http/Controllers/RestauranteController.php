<?php

// app/Http/Controllers/RestauranteController.php
namespace App\Http\Controllers;

use App\Models\Restaurante;
use App\Models\Receita;
use App\Http\Requests\RestauranteRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RestauranteController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Restaurante::class);

        $query = Restaurante::query();

        // Aplicar filtros
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('endereco', 'like', "%{$search}%")
                  ->orWhere('tipo_cozinha', 'like', "%{$search}%");
            });
        }

        if ($request->has('ativo') && $request->ativo !== '') {
            $query->where('ativo', $request->boolean('ativo'));
        }

        if ($request->has('tipo_cozinha') && $request->tipo_cozinha) {
            $query->porTipoCozinha($request->tipo_cozinha);
        }

        // Ordenação
        $orderBy = $request->get('order_by', 'nome');
        $orderDirection = $request->get('order_direction', 'asc');
        $query->orderBy($orderBy, $orderDirection);

        $restaurantes = $query->paginate(15)->withQueryString();

        // Tipos de cozinha disponíveis
        $tiposCozinha = Restaurante::distinct()->pluck('tipo_cozinha')->sort();

        return Inertia::render('Restaurantes/Index', [
            'restaurantes' => $restaurantes,
            'tiposCozinha' => $tiposCozinha,
            'filters' => $request->only(['search', 'ativo', 'tipo_cozinha', 'order_by', 'order_direction']),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Restaurante::class);

        return Inertia::render('Restaurantes/Create');
    }

    public function store(RestauranteRequest $request): RedirectResponse
    {
        $this->authorize('create', Restaurante::class);

        Restaurante::create($request->validated());

        return redirect()->route('restaurantes.index')
            ->with('success', 'Restaurante criado com sucesso!');
    }

    public function show(Restaurante $restaurante): Response
    {
        $this->authorize('view', $restaurante);

        $pratos = $restaurante->pratosConfeccionados();

        return Inertia::render('Restaurantes/Show', [
            'restaurante' => $restaurante,
            'pratos' => $pratos,
        ]);
    }

    public function edit(Restaurante $restaurante): Response
    {
        $this->authorize('update', $restaurante);

        return Inertia::render('Restaurantes/Edit', [
            'restaurante' => $restaurante,
        ]);
    }

    public function update(RestauranteRequest $request, Restaurante $restaurante): RedirectResponse
    {
        $this->authorize('update', $restaurante);

        $restaurante->update($request->validated());

        return redirect()->route('restaurantes.show', $restaurante)
            ->with('success', 'Restaurante atualizado com sucesso!');
    }

    public function destroy(Restaurante $restaurante): RedirectResponse
    {
        $this->authorize('delete', $restaurante);

        $restaurante->delete();

        return redirect()->route('restaurantes.index')
            ->with('success', 'Restaurante excluído com sucesso!');
    }

    public function toggleStatus(Restaurante $restaurante): RedirectResponse
    {
        $this->authorize('update', $restaurante);

        $restaurante->update(['ativo' => !$restaurante->ativo]);

        $status = $restaurante->ativo ? 'ativado' : 'desativado';

        return redirect()->back()
            ->with('success', "Restaurante {$status} com sucesso!");
    }

    public function adicionarPrato(Request $request, Restaurante $restaurante): RedirectResponse
    {
        $this->authorize('update', $restaurante);

        $request->validate([
            'receita_id' => 'required|exists:receitas,id'
        ]);

        $receita = Receita::findOrFail($request->receita_id);
        $restaurante->adicionarPrato($receita);

        return redirect()->back()
            ->with('success', 'Prato adicionado ao restaurante com sucesso!');
    }

    public function removerPrato(Restaurante $restaurante, Receita $receita): RedirectResponse
    {
        $this->authorize('update', $restaurante);

        $restaurante->removerPrato($receita);

        return redirect()->back()
            ->with('success', 'Prato removido do restaurante com sucesso!');
    }
}
