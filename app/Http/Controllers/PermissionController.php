<?php

namespace App\Http\Controllers;

use App\Http\Resources\Permission as PermissionResource;
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
        $this->middleware(['auth:api']);
    }

    /**
     * Fetch permissions.
     *
     * @return \App\Http\Resources\Permission
     */
    public function index()
    {
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
        return new PermissionResource($permission);
    }
}
