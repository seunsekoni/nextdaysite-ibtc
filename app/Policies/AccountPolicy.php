<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $cordinator
     * @return mixed
     */
    public function delete(User $user, User $cordinator)
    {
        // if user is a cordinator, do not delete.
        return $cordinator->isCordinator != 1;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $cordinator
     * @return mixed
     */
    public function view(User $cordinator, User $user)
    {
        // Only students(owner of account) and cordinators can view this profile
        return $cordinator->isCordinator == 1 || auth()->user()->id == $user->id;
    }

    /**
     * Determine whether the user can create the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $cordinator
     * @return mixed
     */
    public function store(User $cordinator)
    {
        // Only students(owner of account) and cordinators can view this profile
        return $cordinator->isCordinator == 1;
    }
}
