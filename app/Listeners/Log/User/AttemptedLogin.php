<?php

namespace App\Listeners\Log\User;

use App\Models\User;
use Illuminate\Auth\Events\Attempting as AttemptedLoginEvent;

class AttemptedLogin
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
     * @param  \Illuminate\Auth\Events\Attempting  $event
     * @return void
     */
    public function handle(AttemptedLoginEvent $event)
    {
        $user = User::where('email', $event->credentials['email'])->first();

        activity('users')
            ->performedOn($user)
            ->withProperties($event->credentials['email'])
            ->log('Login Attempt for email '.$event->credentials['email']);
    }
}
