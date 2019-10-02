<?php

namespace App\Http\Controllers;

use App\Http\RequestProcessor;
use App\Http\Resources\Role as RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\Role
     */
    public function index(Request $request)
    {
        $processor = new RequestProcessor($request, Role::class);

        return RoleResource::collection($processor->index());
    }

    /**
     * Fetch one role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \App\Http\Resources\Role
     */
    public function show(Request $request, Role $role)
    {
        $processor = new RequestProcessor($request);

        return new RoleResource($processor->show($role));
    }
}
