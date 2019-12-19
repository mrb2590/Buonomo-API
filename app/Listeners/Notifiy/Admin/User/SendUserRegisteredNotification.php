<?php

namespace App\Listeners\Notify\Admin\User;

use App\Models\Permission;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserRegisteredNotification implements ShouldQueue
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
     * Handle the event. Get All users who have the permission directly
     * or via roles and send the notificaion.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $permission = Permission::where('name', 'recieve-admin-user-notifications')->first();
        $users = collect($permission->users);

        $permission->roles->each(function ($role) use (&$users) {
            return $users->push($role->users);
        });

        $users->flatten()->unique('id')->each(function ($user) use ($event) {
            $user->sendAdminRegisteredUserNotification($event->user);
        });
    }
}
