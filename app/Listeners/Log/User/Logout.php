<?php

namespace App\Listeners\Log\User;

use App\Models\User;
use Illuminate\Auth\Events\Logout as LogoutEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class Logout implements ShouldQueue
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
     * @param  \Illuminate\Auth\Events\Logout  $event
     * @return void
     */
    public function handle(LogoutEvent $event)
    {
        $user = User::find($event->user->id);

        activity('users')
            ->performedOn($user)
            ->log('User "'.$user->name.'" has logged out');
    }
}
