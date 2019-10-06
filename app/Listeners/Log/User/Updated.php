<?php

namespace App\Listeners\Log\User;

use App\Events\User\Updated as UpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class Updated implements ShouldQueue
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
     * @param  \App\Events\User\Updated  $event
     * @return void
     */
    public function handle(UpdatedEvent $event)
    {
        $log = 'User "'.$event->user->name.'" was updated';

        if ($event->updatedBy) {
            $log .= ' by '.$event->updatedBy->name;
        }

        activity('users')
            ->performedOn($event->user)
            ->causedBy($event->updatedBy)
            ->withProperties($event->user->toArray())
            ->log($log);
    }
}
