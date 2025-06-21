<?php
// app/Policies/EditorPolicy.php
namespace App\Policies;

use App\Models\Editor;
use App\Models\User;

class EditorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Editor $editor): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Editor $editor): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Editor $editor): bool
    {
        return $user->isAdmin();
    }
}
