<?php

namespace App\Listeners\Log\User;

use App\Events\User\Deleted as DeletedEvent;

class Deleted
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
     * @param  \App\Events\User\Deleted  $event
     * @return void
     */
    public function handle(DeletedEvent $event)
    {
        $log = 'User "'.$event->user->name.'" was deleted';

        if ($event->deletedBy) {
            $log .= ' by '.$event->deletedBy->name;
        }

        activity('users')
            ->performedOn($event->user)
            ->causedBy($event->deletedBy)
            ->withProperties($event->user->toArray())
            ->log($log);
    }
}
