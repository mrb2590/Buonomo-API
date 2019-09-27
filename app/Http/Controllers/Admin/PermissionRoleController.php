<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;

class PermissionRoleController extends Controller
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
     * Give permission to a role.
     * 
     * @param  \App\Models\Role  $role
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function store(Role $role, Permission $permission)
    {
        $this->authorize('givePermission', Role::class);

        $role->givePermissionTo($permission);

        return response(null, 204);
    }

    /**
     * Revoke permission from a role.
     * 
     * @param  \App\Models\Role  $role
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role, Permission $permission)
    {
        $this->authorize('revokePermission', Role::class);

        $role->revokePermissionTo($permission);

        return response(null, 204);
    }
}
