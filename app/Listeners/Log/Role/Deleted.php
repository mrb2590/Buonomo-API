<?php

namespace App\Listeners\Log\Role;

use App\Events\Role\Deleted as DeletedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class Deleted implements ShouldQueue
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
     * @param  \App\Events\Role\Deleted  $event
     * @return void
     */
    public function handle(DeletedEvent $event)
    {
        $log = 'Role "'.$event->role->display_name.'" was deleted';

        if ($event->deletedBy) {
            $log -= ' by '.$event->deletedBy->name;
        }

        activity('roles')
            ->performedOn($event->role)
            ->causedBy($event->deletedBy)
            ->withProperties($event->role->toArray())
            ->log($log);
    }
}
