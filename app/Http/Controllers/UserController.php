<?php

namespace App\Http\Controllers;

use App\Http\RequestProcessor;
use App\Http\Resources\User as UserResource;
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
        $this->middleware(['auth:api', 'verified']);
    }

    /**
     * Fetch the current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\User
     */
    public function show(Request $request)
    {
        $this->authorize('read', $request->user());

        $processor = new RequestProcessor($request);

        return new UserResource($processor->show($request->user()));
    }

    /**
     * Update the current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\User
     */
    public function update(Request $request)
    {
        $this->authorize('update', $request->user());

        $request->validate([
            'first_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($request->user()),
            ],
            'username' => [
                'nullable',
                'string',
                'max:30',
                'regex:/^[a-zA-Z0-9]+([-_.]?[a-zA-Z0-9])+$/',
                Rule::unique('users')->ignore($request->user()),
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
     * Delete the current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\User
     */
    public function destroy(Request $request)
    {
        $this->authorize('delete', $request->user());

        $request->user()->forceDelete();

        return response(null, 204);
    }
}
