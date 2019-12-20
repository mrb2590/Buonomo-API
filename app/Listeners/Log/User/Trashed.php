<?php

namespace App\Listeners\Log\User;

use App\Events\User\Trashed as TrashedEvent;

class Trashed
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
     * @param  \App\Events\User\Trashed  $event
     * @return void
     */
    public function handle(TrashedEvent $event)
    {
        $log = 'User "'.$event->user->name.'" was trashed';

        if ($event->trashedBy) {
            $log .= ' by '.$event->trashedBy->name;
        }

        activity('users')
            ->performedOn($event->user)
            ->causedBy($event->trashedBy)
            ->withProperties($event->user->toArray())
            ->log($log);
    }
}
