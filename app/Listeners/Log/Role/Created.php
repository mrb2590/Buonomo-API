<?php

namespace App\Listeners\Log\Role;

use App\Events\Role\Created as CreatedEvent;
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
     * @param  \App\Events\Role\Created  $event
     * @return void
     */
    public function handle(CreatedEvent $event)
    {
        $log = 'Role "'.$event->role->display_name.'" was created';

        if ($event->createdBy) {
            $log .= ' by '.$event->createdBy->name;
        }

        activity('roles')
            ->performedOn($event->role)
            ->causedBy($event->createdBy)
            ->withProperties($event->role->toArray())
            ->log($log);
    }
}
