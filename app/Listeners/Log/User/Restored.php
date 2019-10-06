<?php

namespace App\Listeners\Log\User;

use App\Events\User\Restored as RestoredEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class Restored implements ShouldQueue
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
     * @param  \App\Events\User\Restored  $event
     * @return void
     */
    public function handle(RestoredEvent $event)
    {
        $log = 'User "'.$event->user->name.'" was restored';

        if ($event->restoredBy) {
            $log .= ' by '.$event->restoredBy->name;
        }

        activity('users')
            ->performedOn($event->user)
            ->causedBy($event->restoredBy)
            ->withProperties($event->user->toArray())
            ->log($log);
    }
}
