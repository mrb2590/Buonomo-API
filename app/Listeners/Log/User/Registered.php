<?php

namespace App\Listeners\Log\User;

use App\Models\User;
use Illuminate\Auth\Events\Registered as RegisteredEvent;

class Registered
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
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(RegisteredEvent $event)
    {
        $user = User::find($event->user->id);

        activity('users')
            ->performedOn($user)
            ->log('User "'.$user->name.'" has registered');
    }
}
