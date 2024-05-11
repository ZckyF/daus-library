<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    
    public function viewAny(User $user) : bool
    {
        return $user->can('user.view_any');
    }

    public function view(User $user, User $targetUser) : bool
    {
        return $user->id == $targetUser->id && $user->can('user.view');
    }

    public function create(User $user) : bool
    {
       return $user->can('user.create');
    }

    public function update(User $user, User $targetUser) : bool
    {
        return $user->id == $targetUser->id && $user->can('user.update');
    }

    public function delete(User $user, User $targetUser) : bool
    {
        return  $user->can('user.create');
    }

    public function restore(User $user, User $targetUser): bool
    {
        return false;
    }

    public function forceDelete(User $user, User $targetUser): bool
    {
        return false;
    }
}
