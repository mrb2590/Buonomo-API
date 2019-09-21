<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can read all users.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $anyUser
     * @return boolean
     */
    public function read(User $user, User $anyUser = null)
    {
        if ($user->can('read-users')) {
            return true;
        }

        // Users can read themselves.
        if ($anyUser) {
            return $user->id === $anyUser->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create new users.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function create(User $user)
    {
        if ($user->can('create-users')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update users.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $anyUser
     * @return boolean
     */
    public function update(User $user, User $anyUser)
    {
        if ($user->can('update-users')) {
            return true;
        }

        // Users can update themselves.
        return $user->id === $anyUser->id;
    }

    /**
     * Determine whether the user can trash users.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $anyUser
     * @return boolean
     */
    public function trash(User $user, User $anyUser)
    {
        if ($user->can('trash-users')) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can restore users.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $anyUser
     * @return boolean
     */
    public function restore(User $user, User $anyUser)
    {
        if ($user->can('restore-users')) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete users.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $anyUser
     * @return boolean
     */
    public function delete(User $user, User $anyUser)
    {
        if ($user->can('delete-users')) {
            return true;
        }

        // Users can delete themselves.
        return $user->id === $anyUser->id;
    }
}
