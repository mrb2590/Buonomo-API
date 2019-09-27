<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
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
     * Determine whether the user can read roles.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function read(User $user)
    {
        return $user->can('read-roles');
    }

    /**
     * Determine whether the user can create new roles.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function create(User $user)
    {
        return $user->can('create-roles');
    }

    /**
     * Determine whether the user can update roles.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function update(User $user)
    {
        return $user->can('update-roles');
    }

    /**
     * Determine whether the user can delete roles.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function delete(User $user)
    {
        return $user->can('delete-roles');
    }

    /**
     * Determine whether the user can give role permissions.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function givePermission(User $user)
    {
        return $user->can('give-role-permissions');
    }

    /**
     * Determine whether the user can revoke role permissions.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function revokePermission(User $user)
    {
        return $user->can('revoke-role-permissions');
    }
}
