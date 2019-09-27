<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\User as UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
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
     * Fetch users.
     *
     * @return \App\Http\Resources\User
     */
    public function index()
    {
        $this->authorize('read', User::class);

        return UserResource::collection(User::paginate(10));
    }

    /**
     * Fetch one user.
     *
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\User
     */
    public function show(User $user)
    {
        $this->authorize('read', User::class);

        return new UserResource($user);
    }

    /**
     * Create a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\User
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        $this->validator($request->all(), true)->validate();

        $user = new User;
        $user->fill($request->all());
        $user->created_by_id = $request->user()->id;
        $user->updated_by_id = $request->user()->id;
        $user->password = Hash::make($request->password);

        if ($request->email_verified) {
            $user->email_verified_at = now();
        } else {
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        $user->save();

        return new UserResource($user);
    }

    /**
     * Update a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\User
     */
    public function update(Request $request, User $user)
    {
        $this->authorize($user ? 'update' : 'create', User::class);
        $this->validator($request->all(), false, $user)->validate();

        $user->fill($request->all());
        $user->updated_by_id = $request->user()->id;

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('email_verified')) {
            if ($request->email_verified) {
                $user->email_verified_at = now();
            } else {
                $user->email_verified_at = null;
                $user->sendEmailVerificationNotification();
            }
        }

        $user->save();

        return new UserResource($user);
    }

    /**
     * Delete a user.
     *
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\User
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', User::class);

        $user->forceDelete();

        return response(null, 204);
    }

    /**
     * Validate the request.
     * 
     * @param  array  $data
     * @param  boolean  $required
     * @param  \App\Models\User  $user
     * @return void
     */
    private function validator(array $data, bool $required, User $user = null)
    {
        $required = $required ? 'required' : 'nullable';

        return Validator::make($data, [
            'first_name' => [$required, 'string', 'max:50'],
            'last_name' => [$required, 'string', 'max:100'],
            'email' => [
                $required,
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user),
            ],
            'username' => [
                $required,
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9]+([-_.]?[a-zA-Z0-9])+$/',
                Rule::unique('users')->ignore($user),
            ],
            'password' => [$required, 'string', 'min:8', 'confirmed'],
            'email_verified' => ['required_with:email', 'boolean'],
        ]);
    }
}
