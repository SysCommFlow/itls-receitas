<?php
// app/Policies/CategoriaPolicy.php
namespace App\Policies;

use App\Models\Categoria;
use App\Models\User;

class CategoriaPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Todos podem ver categorias
    }

    public function view(User $user, Categoria $categoria): bool
    {
        return true; // Todos podem ver uma categoria específica
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Categoria $categoria): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Categoria $categoria): bool
    {
        return $user->isAdmin();
    }
}
