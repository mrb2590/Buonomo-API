<?php

namespace App\Listeners\Notify\Admin\User;

use App\Models\Permission;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserVerifiedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Verified $event)
    {
        $permission = Permission::where('name', 'recieve-admin-user-notifications')->first();
        $users = collect($permission->users);

        $permission->roles->each(function ($role) use (&$users) {
            return $users->push($role->users);
        });

        $users->flatten()->unique('id')->each(function ($user) use ($event) {
            $user->sendAdminVerifiedUserNotification($event->user);
        });
    }
}
