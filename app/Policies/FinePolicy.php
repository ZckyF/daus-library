<?php

namespace App\Policies;

use App\Models\Fine;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FinePolicy
{
    /**
     * Determine whether the user can view any models.
     * 
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('fine.view_any');
    }

    /**
     * Determine whether the user can view the model.
     * 
     * @return bool
     */
    public function view(User $user, Fine $fine): bool
    {
        return $user->can('fine.view');
    }

    /**
     * Determine whether the user can create models.
     * 
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('fine.create');
    }

    /**
     * Determine whether the user can update the model.
     * 
     * @return bool
     */
    public function update(User $user, Fine $fine): bool
    {
        return $user->can('fine.update');
    }

    /**
     * Determine whether the user can delete the model.
     * 
     * @return bool
     */
    public function delete(User $user, Fine $fine): bool
    {
        return $user->can('fine.delete');
    }

    /**
     * Determine whether the user can restore the model.
     * 
     * @return bool
     */
    public function restore(User $user, Fine $fine): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     * 
     * @return bool
     */
    public function forceDelete(User $user, Fine $fine): bool
    {
        return false;
    }
}
