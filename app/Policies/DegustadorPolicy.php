<?php
// app/Policies/DegustadorPolicy.php
namespace App\Policies;

use App\Models\Degustador;
use App\Models\User;

class DegustadorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isCozinheiro() || $user->isAdmin();
    }

    public function view(User $user, Degustador $degustador): bool
    {
        return $user->isCozinheiro() || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Degustador $degustador): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Degustador $degustador): bool
    {
        return $user->isAdmin();
    }
}
