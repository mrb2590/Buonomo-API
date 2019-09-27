<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Role as RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RoleController extends Controller
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
     * Fetch roles.
     *
     * @return \App\Http\Resources\Role
     */
    public function index()
    {
        $this->authorize('read', Role::class);

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
        $this->authorize('read', Role::class);

        return new RoleResource($role);
    }

    /**
     * Create a new role.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\Role
     */
    public function store(Request $request)
    {
        $this->authorize('create', Role::class);
        $this->validator($request->all(), true)->validate();

        $role = Role::make(array_merge($request->merge([
            'guard_name' => 'web',
        ])->all()));
        $role->created_by_id = $request->user()->id;
        $role->updated_by_id = $request->user()->id;
        $role->save();

        return new RoleResource($role);
    }

    /**
     * Update a role.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \App\Http\Resources\Role
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('update', Role::class);
        $this->validator($request->all(), false, $role)->validate();

        $role->fill($request->all());
        $role->updated_by_id = $request->user()->id;
        $role->save();

        return new RoleResource($role);
    }

    /**
     * Delete a role.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', Role::class);

        $role->delete();

        return response(null, 204);
    }

    /**
     * Validate the request.
     * 
     * @param  array  $data
     * @param  boolean  $required
     * @param  \App\Models\Role  $role
     * @return void
     */
    private function validator(array $data, bool $required, Role $role = null)
    {
        $required = $required ? 'required' : 'nullable';

        return Validator::make($data, [
            'name' => [
                $required,
                'string',
                'max:255',
                Rule::unique('roles')->ignore($role),
            ],
            'display_name' => [$required, 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
        ]);
    }
}
