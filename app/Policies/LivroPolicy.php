<?php
// app/Policies/LivroPolicy.php
namespace App\Policies;

use App\Models\Livro;
use App\Models\User;

class LivroPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Todos podem ver livros publicados
    }

    public function view(User $user, Livro $livro): bool
    {
        // Admin pode ver qualquer livro
        if ($user->isAdmin()) {
            return true;
        }

        // Outros usuários podem ver apenas livros publicados
        return $livro->status === 'publicado';
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Livro $livro): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Livro $livro): bool
    {
        return $user->isAdmin();
    }

    public function publish(User $user, Livro $livro): bool
    {
        return $user->isAdmin();
    }
}
