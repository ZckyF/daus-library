<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MemberPolicy
{
    /**
     * Determine whether the user can view any models.
     * 
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('member.view_any');
    }

    /**
     * Determine whether the user can view the model.
     * 
     * @return bool
     */
    public function view(User $user, Member $member): bool
    {
        return $user->can('member.view');
    }

    /**
     * Determine whether the user can create models.
     * 
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('member.create');
    }

    /**
     * Determine whether the user can update the model.
     * 
     * @return bool
     */
    public function update(User $user, Member $member): bool
    {
        return $user->can('member.update');
    }

    /**
     * Determine whether the user can delete the model.
     * 
     * @return bool
     */
    public function delete(User $user, Member $member): bool
    {
        return $user->can('member.delete');
    }

    /**
     * Determine whether the user can restore the model.
     * 
     * @return bool
     */
    public function restore(User $user, Member $member): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     * 
     * @return bool
     */
    public function forceDelete(User $user, Member $member): bool
    {
        return false;
    }
}
