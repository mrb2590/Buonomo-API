<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\User as UserResource;
use App\Models\User;

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
     * @return \App\Http\Resources\User
     */
    public function index()
    {
        $this->authorize('read', User::class);

        return UserResource::collection(User::onlyTrashed()->paginate(10));
    }

    /**
     * Fetch one trashed user.
     *
     * @param  \App\Models\User  $trashedUser
     * @return \App\Http\Resources\User
     */
    public function show(User $trashedUser)
    {
        $this->authorize('read', User::class);

        return new UserResource($trashedUser);
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
