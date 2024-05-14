<?php

namespace App\Policies;

use App\Models\BookCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('book_category.view_any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BookCategory $bookCategory): bool
    {
        return $user->can('book_category.any');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('book_category.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BookCategory $bookCategory): bool
    {
        return $user->can('book_category.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BookCategory $bookCategory): bool
    {
        return $user->can('book_category.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BookCategory $bookCategory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BookCategory $bookCategory): bool
    {
        return false;
    }
}
