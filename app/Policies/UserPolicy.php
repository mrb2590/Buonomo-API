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
     * @param  \App\Models\User $userToRead
     * @return boolean
     */
    public function read(User $user, User $userToRead = null)
    {
        if ($user->can('read-users')) {
            return true;
        }

        if ($userToRead) {
            return $user->id === $userToRead->id;
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
        return $user->can('create-users');
    }

    /**
     * Determine whether the user can update users.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $userToUpdate
     * @return boolean
     */
    public function update(User $user, User $userToUpdate = null)
    {
        if ($user->can('update-users')) {
            return true;
        }

        if ($userToUpdate) {
            return $user->id === $userToUpdate->id;
        }
    }

    /**
     * Determine whether the user can trash users.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function trash(User $user)
    {
        return $user->can('trash-users');
    }

    /**
     * Determine whether the user can restore users.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function restore(User $user)
    {
        return $user->can('restore-users');
    }

    /**
     * Determine whether the user can delete users.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function delete(User $user, User $userToDelete = null)
    {
        if ($user->can('delete-users')) {
            return true;
        }

        if ($userToDelete) {
            return $user->id === $userToDelete->id;
        }
    }

    /**
     * Determine whether the user can assign user roles.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function assignRole(User $user)
    {
        return $user->can('assign-user-roles');
    }

    /**
     * Determine whether the user can remove user roles.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function removeRole(User $user)
    {
        return $user->can('remove-user-roles');
    }

    /**
     * Determine whether the user can give user permissions.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function givePermission(User $user)
    {
        return $user->can('give-user-permissions');
    }

    /**
     * Determine whether the user can revoke user permissions.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function revokePermission(User $user)
    {
        return $user->can('revoke-user-permissions');
    }
}
