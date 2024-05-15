<?php

namespace App\Policies;

use App\Models\BorrowingBook;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BorrowingBookPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('borrowing_book.view_any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BorrowingBook $borrowingBook): bool
    {
        return $user->can('borrowing_book.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('borrowing_book.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BorrowingBook $borrowingBook): bool
    {
        return $user->can('borrowing_book.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BorrowingBook $borrowingBook): bool
    {
        return $user->can('borrowing_book.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BorrowingBook $borrowingBook): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BorrowingBook $borrowingBook): bool
    {
        return false;
    }
}
