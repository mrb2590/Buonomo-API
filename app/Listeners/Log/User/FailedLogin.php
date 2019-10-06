<?php

namespace App\Listeners\Log\User;

use App\Models\User;
use Illuminate\Auth\Events\Failed as FailedLoginEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class FailedLogin implements ShouldQueue
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
     * @param  \Illuminate\Auth\Events\Failed  $event
     * @return void
     */
    public function handle(FailedLoginEvent $event)
    {
        $user = User::where('email', $event->credentials['email'])->first();

        activity('users')
            ->performedOn($user)
            ->withProperties($event->credentials['email'])
            ->log('Failed Login Attempt for email '.$event->credentials['email']);
    }
}
