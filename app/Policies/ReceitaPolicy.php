<?php
// app/Policies/ReceitaPolicy.php
namespace App\Policies;

use App\Models\Receita;
use App\Models\User;

class ReceitaPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Todos podem ver a lista de receitas
    }

    public function view(User $user, Receita $receita): bool
    {
        // Admin pode ver qualquer receita
        if ($user->isAdmin()) {
            return true;
        }

        // Cozinheiro pode ver suas próprias receitas ou receitas publicadas
        if ($user->isCozinheiro()) {
            return $receita->user_id === $user->id || $receita->publicada;
        }

        // Degustadores podem ver apenas receitas publicadas
        return $receita->publicada;
    }

    public function create(User $user): bool
    {
        return $user->isCozinheiro() || $user->isAdmin();
    }

    public function update(User $user, Receita $receita): bool
    {
        // Admin pode editar qualquer receita
        if ($user->isAdmin()) {
            return true;
        }

        // Cozinheiro pode editar apenas suas próprias receitas
        return $user->isCozinheiro() && $receita->user_id === $user->id;
    }

    public function delete(User $user, Receita $receita): bool
    {
        // Admin pode excluir qualquer receita
        if ($user->isAdmin()) {
            return true;
        }

        // Cozinheiro pode excluir apenas suas próprias receitas que não foram testadas
        return $user->isCozinheiro() &&
               $receita->user_id === $user->id &&
               !$receita->testada;
    }

    public function publish(User $user, Receita $receita): bool
    {
        // Admin pode publicar qualquer receita
        if ($user->isAdmin()) {
            return true;
        }

        // Cozinheiro pode publicar apenas suas próprias receitas
        return $user->isCozinheiro() && $receita->user_id === $user->id;
    }
}
