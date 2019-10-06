<?php

namespace App\Listeners\Log\User;

use App\Models\User;
use Illuminate\Auth\Events\Authenticated as AuthenticatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class Authenticated implements ShouldQueue
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
     * @param  \Illuminate\Auth\Events\Authenticated  $event
     * @return void
     */
    public function handle(AuthenticatedEvent $event)
    {
        $user = User::find($event->user->id);

        activity('users')
            ->performedOn($user)
            ->log($user->name.' was successfully authenticated');
    }
}
