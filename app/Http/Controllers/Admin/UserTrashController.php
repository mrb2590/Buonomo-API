<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
     * @param  \App\Models\User  $trashedUser
     * @return \App\Http\Resources\User
     */
    public function fetch(Request $request, User $trashedUser = null)
    {
        $this->authorize('read', $trashedUser ?? User::class);

        if ($trashedUser) {
            return new UserResource($trashedUser);
        }

        return UserResource::collection(User::onlyTrashed()->paginate(10));
    }

    /**
     * Trash a user.
     *
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\User
     */
    public function store(Request $request, User $user = null)
    {
        $this->authorize('trash', $user);

        $user->delete();

        return new UserResource($user);
    }

    /**
     * Restore a trashed user.
     *
     * @param  \App\Models\User  $trashedUser
     * @return \App\Http\Resources\User
     */
    public function restore(Request $request, User $trashedUser = null)
    {
        $this->authorize('restore', $trashedUser);

        $trashedUser->restore();

        return new UserResource($trashedUser);
    }

    /**
     * Delete the a trashed user.
     *
     * @param  \App\Models\User  $trashedUser
     * @return \App\Http\Resources\User
     */
    public function destroy(Request $request, User $trashedUser)
    {
        $this->authorize('delete', $trashedUser);

        $trashedUser->forceDelete();

        return response(null, 204);
    }
}
