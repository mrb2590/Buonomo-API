<?php

namespace App\Http\Controllers;

use App\Http\RequestProcessor;
use App\Http\Resources\Permission as PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\Permission
     */
    public function index(Request $request)
    {
        $processor = new RequestProcessor($request, Permission::class);

        return PermissionResource::collection($processor->index());
    }

    /**
     * Fetch one permission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \App\Http\Resources\Permission
     */
    public function show(Request $request, Permission $permission)
    {
        $processor = new RequestProcessor($request);

        return new PermissionResource($processor->show($permission));
    }
}
