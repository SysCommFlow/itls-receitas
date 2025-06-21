<?php
// app/Policies/TestePolicy.php
namespace App\Policies;

use App\Models\Teste;
use App\Models\User;

class TestePolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Todos podem ver a lista de testes
    }

    public function view(User $user, Teste $teste): bool
    {
        // Admin pode ver qualquer teste
        if ($user->isAdmin()) {
            return true;
        }

        // Cozinheiro pode ver testes de suas receitas
        if ($user->isCozinheiro()) {
            return $teste->receita->user_id === $user->id;
        }

        // Degustadores podem ver todos os testes
        return $user->isDegustador();
    }

    public function create(User $user): bool
    {
        return $user->isCozinheiro() || $user->isAdmin();
    }

    public function update(User $user, Teste $teste): bool
    {
        // Admin pode editar qualquer teste
        if ($user->isAdmin()) {
            return true;
        }

        // Cozinheiro pode editar testes de suas receitas (apenas se agendado)
        return $user->isCozinheiro() &&
               $teste->receita->user_id === $user->id &&
               $teste->status === 'agendado';
    }

    public function delete(User $user, Teste $teste): bool
    {
        // Admin pode excluir qualquer teste
        if ($user->isAdmin()) {
            return true;
        }

        // Cozinheiro pode excluir testes de suas receitas (apenas se agendado)
        return $user->isCozinheiro() &&
               $teste->receita->user_id === $user->id &&
               $teste->status === 'agendado';
    }

    public function avaliar(User $user, Teste $teste): bool
    {
        // Admin pode avaliar qualquer teste
        if ($user->isAdmin()) {
            return true;
        }

        // Apenas o degustador designado pode avaliar
        return $user->isDegustador() && $teste->degustador->email === $user->email;
    }
}
