<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;

class RoleUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api', 'verified', 'permission:access-admin-dashboard']);
    }

    /**
     * Assign a role to a user.
     * 
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, Role $role)
    {
        $this->authorize('assignRole', User::class);

        $user->assignRole($role);

        return response(null, 204);
    }

    /**
     * Remove a role from a user.
     * 
     * @param  \App\Models\User  $user
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Role $role)
    {
        $this->authorize('removeRole', User::class);

        $user->removeRole($role);

        return response(null, 204);
    }
}
