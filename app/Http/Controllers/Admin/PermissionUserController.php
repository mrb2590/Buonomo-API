<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;

class PermissionUserController extends Controller
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
     * Give a permission to a user.
     * 
     * @param  \App\Models\User  $user
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, Permission $permission)
    {
        $this->authorize('givePermission', User::class);
        
        $user->givePermissionTo($permission);
        
        return response(null, 204);
    }

    /**
     * Revoke a permission from a user.
     * 
     * @param  \App\Models\User  $user
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Permission $permission)
    {
        $this->authorize('revokePermission', User::class);
        
        $user->givePermissionTo($permission);
        
        return response(null, 204);
    }
}
