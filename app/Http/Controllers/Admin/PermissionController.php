<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Permission as PermissionResource;
use App\Models\Permission;

class PermissionController extends Controller
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
     * Fetch permissions.
     *
     * @return \App\Http\Resources\Permission
     */
    public function index()
    {
        $this->authorize('read', Permission::class);

        return PermissionResource::collection(Permission::paginate(10));
    }

    /**
     * Fetch one permission.
     *
     * @param  \App\Models\Permission  $permission
     * @return \App\Http\Resources\Permission
     */
    public function show(Permission $permission)
    {
        $this->authorize('read', Permission::class);

        return new PermissionResource($permission);
    }
}
