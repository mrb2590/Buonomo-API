<?php

namespace App\Listeners\Log\User;

use App\Events\User\Verified as VerifiedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class Verified implements ShouldQueue
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
     * @param  \App\Events\User\Verified  $event
     * @return void
     */
    public function handle(VerifiedEvent $event)
    {
        $log = 'User "'.$event->user->name.'" was verified';

        if ($event->verifiedBy) {
            $log .= ' by '.$event->verifiedBy->name;
        }

        activity('users')
            ->performedOn($event->user)
            ->causedBy($event->verifiedBy)
            ->withProperties($event->user->toArray())
            ->log($log);
    }
}
