<?php

namespace App\Policies;

use App\Models\Bookshelf;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookshelfPolicy
{
    /**
     * Determine whether the user can view any models.
     * 
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('bookshelf.view_any');
    }

    /**
     * Determine whether the user can view the model.
     * 
     * @return bool
     */
    public function view(User $user, Bookshelf $bookshelf): bool
    {
        return $user->can('bookshelf.any');
    }

    /**
     * Determine whether the user can create models.
     * 
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('bookshelf.create');
    }

    /**
     * Determine whether the user can update the model.
     * 
     * @return bool
     */
    public function update(User $user, Bookshelf $bookshelf): bool
    {
        return $user->can('bookshelf.update');
    }

    /**
     * Determine whether the user can delete the model.
     * 
     * @return bool
     */
    public function delete(User $user, Bookshelf $bookshelf): bool
    {
        return $user->can('bookshelf.delete');
    }

    /**
     * Determine whether the user can restore the model.
     * 
     * @return bool
     */
    public function restore(User $user, Bookshelf $bookshelf): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     * 
     * @return bool
     */
    public function forceDelete(User $user, Bookshelf $bookshelf): bool
    {
        return false;
    }
}
