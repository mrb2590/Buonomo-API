<?php

namespace App\Listeners\Log\Role;

use App\Events\Role\Updated as UpdatedEvent;

class Updated
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
     * @param  \App\Events\Role\Updated  $event
     * @return void
     */
    public function handle(UpdatedEvent $event)
    {
        $log = 'Role "'.$event->role->display_name.'" was updated';

        if ($event->updatedBy) {
            $log .= ' by '.$event->updatedBy->name;
        }

        activity('roles')
            ->performedOn($event->role)
            ->causedBy($event->updatedBy)
            ->withProperties($event->role->toArray())
            ->log($log);
    }
}
