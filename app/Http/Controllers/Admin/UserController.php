<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\User as UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\User
     */
    public function fetch(Request $request, User $user = null)
    {
        $this->authorize('read', $user ?? User::class);

        if ($user) {
            return new UserResource($user);
        }

        return UserResource::collection(User::paginate(10));
    }

    /**
     * Update a user.
     *
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\User
     */
    public function store(Request $request, User $user = null)
    {
        if ($request->getMethod() == 'POST') {
            $this->authorize('create', User::class);
        } else {
            $this->authorize('update', $user);
        }

        $fieldRequired = Rule::requiredIf($request->getMethod() == 'POST');

        $request->validate([
            'first_name' => [$fieldRequired, 'string', 'max:50'],
            'last_name' => [$fieldRequired, 'string', 'max:100'],
            'email' => [
                $fieldRequired,
                'string',
                'email',
                'max:255',
                'unique:users'.($user ? ',email,'.$user->email : '')
            ],
            'username' => [
                $fieldRequired,
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9]+([-_.]?[a-zA-Z0-9])+$/',
                'unique:users'.($user ? ',id,'.$user->id : ''),
            ],
            'password' => [$fieldRequired, 'string', 'min:8', 'confirmed'],
            'email_verified' => ['required_with:email', 'boolean'],
        ]);

        if (!$user) {
            $user = new User;
            $user->created_by_id = $request->user()->id;
        }

        $user->fill($request->all());
        $user->updated_by_id = $request->user()->id;

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('email')) {
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
    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete', $user);

        $user->forceDelete();

        return response(null, 204);
    }
}
