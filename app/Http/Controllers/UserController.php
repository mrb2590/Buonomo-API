<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api', 'verified']);
    }

    /**
     * Fetch the current user.
     *
     * @return \App\Http\Resources\User
     */
    public function fetch(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * Update the current user.
     *
     * @return \App\Http\Resources\User
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'username' => [
                'nullable',
                'string',
                'max:30',
                'regex:/^[a-zA-Z0-9]+([-_.]?[a-zA-Z0-9])+$/',
                'unique:users,id,'.$request->user()->id,
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->fill($request->all());
        $request->user()->updated_by_id = $request->user()->id;

        if ($request->has('password')) {
            $request->user()->password = Hash::make($request->password);
        }

        if ($request->has('email')) {
            $request->user()->email_verified_at = null;
            $request->user()->sendEmailVerificationNotification();
        }

        $request->user()->save();

        return new UserResource($request->user());
    }

    /**
     * Trash the current user.
     *
     * @return \App\Http\Resources\User
     */
    public function destroy(Request $request)
    {
        $request->user()->delete();

        return response(null, 204);
    }
}
