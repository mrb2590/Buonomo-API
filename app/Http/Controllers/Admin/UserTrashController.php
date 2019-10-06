<?php

namespace App\Http\Controllers\Admin;

use App\Events\User\Deleted;
use App\Events\User\Restored;
use App\Events\User\Trashed;
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
     * @return \App\Http\Resources\Admin\User
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
     * @return \App\Http\Resources\Admin\User
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \App\Http\Resources\Admin\User
     */
    public function store(Request $request, User $user)
    {
        $this->authorize('trash', User::class);

        $user->delete();

        event(new Trashed($user, $request->user()));

        return new UserResource($user);
    }

    /**
     * Restore a trashed user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $trashedUser
     * @return \App\Http\Resources\Admin\User
     */
    public function restore(Request $request, User $trashedUser)
    {
        $this->authorize('restore', User::class);

        $trashedUser->restore();

        event(new Restored($trashedUser, $request->user()));

        return new UserResource($trashedUser);
    }

    /**
     * Delete a trashed user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $trashedUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $trashedUser)
    {
        $this->authorize('delete', User::class);

        $trashedUser->forceDelete();

        event(new Deleted($trashedUser, $request->user()));

        return response(null, 204);
    }
}
