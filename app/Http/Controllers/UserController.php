<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show the landing page.
     *
     * @return \App\Http\Resources\User
     */
    public function fetch(Request $request, User $user = null)
    {
        if ($user) {
            return new UserResource($user);
        }

        return UserResource::collection(User::all());
    }
}
