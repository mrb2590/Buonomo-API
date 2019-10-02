<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\RequestProcessor;
use App\Http\Resources\Admin\User as UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserTrashController extends Controller
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
     * Fetch trashed users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\User
     */
    public function index(Request $request)
    {
        $this->authorize('read', User::class);

        $processor = new RequestProcessor($request, User::class);

        return UserResource::collection($processor->index(User::onlyTrashed()));
    }

    /**
     * Fetch one trashed user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $trashedUser
     * @return \App\Http\Resources\User
     */
    public function show(Request $request, User $trashedUser)
    {
        $this->authorize('read', User::class);

        $processor = new RequestProcessor($request);

        return new UserResource($processor->show($trashedUser));
    }

    /**
     * Trash a user.
     *
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\User
     */
    public function store(User $user)
    {
        $this->authorize('trash', User::class);

        $user->delete();

        return new UserResource($user);
    }

    /**
     * Restore a trashed user.
     *
     * @param  \App\Models\User  $trashedUser
     * @return \App\Http\Resources\User
     */
    public function restore(User $trashedUser)
    {
        $this->authorize('restore', User::class);

        $trashedUser->restore();

        return new UserResource($trashedUser);
    }

    /**
     * Delete a trashed user.
     *
     * @param  \App\Models\User  $trashedUser
     * @return \App\Http\Resources\User
     */
    public function destroy(User $trashedUser)
    {
        $this->authorize('delete', User::class);

        $trashedUser->forceDelete();

        return response(null, 204);
    }
}
