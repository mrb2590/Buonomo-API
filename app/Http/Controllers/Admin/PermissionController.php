<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\RequestProcessor;
use App\Http\Resources\Admin\Permission as PermissionResource;
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
        $this->middleware(['auth:api', 'verified', 'permission:access-admin-dashboard']);
    }

    /**
     * Fetch permissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\Admin\Permission
     */
    public function index(Request $request)
    {
        $this->authorize('read', Permission::class);

        $processor = new RequestProcessor($request, Permission::class);

        return PermissionResource::collection($processor->index());
    }

    /**
     * Fetch one permission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \App\Http\Resources\Admin\Permission
     */
    public function show(Request $request, Permission $permission)
    {
        $this->authorize('read', Permission::class);

        $processor = new RequestProcessor($request);

        return new PermissionResource($processor->show($permission));
    }
}
