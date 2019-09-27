<?php

namespace App\Http\Controllers;

use App\Http\Resources\Role as RoleResource;
use App\Models\Role;

class RoleController extends Controller
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
     * Fetch roles.
     *
     * @return \App\Http\Resources\Role
     */
    public function index()
    {
        return RoleResource::collection(Role::paginate(10));
    }

    /**
     * Fetch one role.
     *
     * @param  \App\Models\Role  $role
     * @return \App\Http\Resources\Role
     */
    public function show(Role $role)
    {
        return new RoleResource($role);
    }
}
