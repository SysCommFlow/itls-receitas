<?php
// app/Policies/RestaurantePolicy.php
namespace App\Policies;

use App\Models\Restaurante;
use App\Models\User;

class RestaurantePolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Todos podem ver restaurantes
    }

    public function view(User $user, Restaurante $restaurante): bool
    {
        return true; // Todos podem ver um restaurante específico
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Restaurante $restaurante): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Restaurante $restaurante): bool
    {
        return $user->isAdmin();
    }
}
