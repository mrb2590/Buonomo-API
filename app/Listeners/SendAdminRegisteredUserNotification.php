<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Permission\Models\Permission;

class SendAdminRegisteredUserNotification implements ShouldQueue
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
     * Handle the event. Get All users who ahve the permission directly
     * or via roles and send the notificaion.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
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
