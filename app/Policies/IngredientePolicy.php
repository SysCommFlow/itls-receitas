<?php
// app/Policies/IngredientePolicy.php
namespace App\Policies;

use App\Models\Ingrediente;
use App\Models\User;

class IngredientePolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Todos podem ver ingredientes
    }

    public function view(User $user, Ingrediente $ingrediente): bool
    {
        return true; // Todos podem ver um ingrediente específico
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Ingrediente $ingrediente): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Ingrediente $ingrediente): bool
    {
        return $user->isAdmin();
    }
}
