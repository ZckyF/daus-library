<?php

namespace App\Policies;

use App\Models\BorrowingBook;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BorrowingBookPolicy
{
    /**
     * Determine whether the user can view any models.
     * 
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('borrowing_book.view_any');
    }

    /**
     * Determine whether the user can view the model.
     * 
     * @return bool
     */
    public function view(User $user, BorrowingBook $borrowingBook): bool
    {
        return $user->can('borrowing_book.view');
    }

    /**
     * Determine whether the user can create models.
     * 
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('borrowing_book.create');
    }

    /**
     * Determine whether the user can update the model.
     * 
     * @return bool
     */
    public function update(User $user, BorrowingBook $borrowingBook): bool
    {
        return $user->can('borrowing_book.update');
    }

    /**
     * Determine whether the user can delete the model.
     * 
     * @return bool
     */
    public function delete(User $user, BorrowingBook $borrowingBook): bool
    {
        return $user->can('borrowing_book.delete');
    }

    /**
     * Determine whether the user can restore the model.
     * 
     * @return bool
     */
    public function restore(User $user, BorrowingBook $borrowingBook): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     * 
     * @return bool
     */
    public function forceDelete(User $user, BorrowingBook $borrowingBook): bool
    {
        return false;
    }
}
