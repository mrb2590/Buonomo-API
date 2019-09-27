<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
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
     * Determine whether the user can read permissions.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function read(User $user)
    {
        return $user->can('read-permissions');
    }
}
