<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    
       /**
     * Determine whether the user can view any models.
     * 
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('user.view_any');
    }

    /**
     * Determine whether the user can view the model.
     * 
     * @return bool
     */
    public function view(User $user, User $targetUser): bool
    {
        return  $user->can('user.view');
    }

    public function profile(User $user, User $targetUser): bool
    {
        return $user->id == $targetUser->id && $user->can('user.profile');
    }

    /**
     * Determine whether the user can create models.
     * 
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('user.create');
    }

    /**
     * Determine whether the user can update the model.
     * 
     * @return bool
     */
    public function update(User $user, User $targetUser): bool
    {
        return $user->can('user.update');
    }

    /**
     * Determine whether the user can inActive the model.
     * 
     * @return bool
     */
    public function inActive(User $user, User $targetUser): bool
    {
        return $user->can('user.is_active') && !$targetUser->hasRole('admin') && !$targetUser->hasRole('super_admin');
    }

    /**
     * Determine whether the user can delete the model.
     * 
     * @return bool
     */
    public function delete(User $user, User $targetUser): bool
    {
        return ($user->can('user.delete')) && !$targetUser->hasRole('admin') && !$targetUser->hasRole('super_admin');
    }

    /**
     * Determine whether the user can restore the model.
     * 
     * @return bool
     */
    public function restore(User $user, User $targetUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     * 
     * @return bool
     */
    public function forceDelete(User $user, User $targetUser): bool
    {
        return false;
    }
}
