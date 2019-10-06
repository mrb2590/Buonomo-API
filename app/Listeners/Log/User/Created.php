<?php

namespace App\Listeners\Log\User;

use App\Events\User\Created as CreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class Created implements ShouldQueue
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
     * @param  \App\Events\User\Created  $event
     * @return void
     */
    public function handle(CreatedEvent $event)
    {
        $log = 'User "'.$event->user->name.'" was created';

        if ($event->createdBy) {
            $log .= ' by '.$event->createdBy->name;
        }

        activity('users')
            ->performedOn($event->user)
            ->causedBy($event->createdBy)
            ->withProperties($event->user->toArray())
            ->log($log);
    }
}
