<?php

namespace App\Listeners\Log\User;

use App\Models\User;
use Illuminate\Auth\Events\Lockout as LockoutEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class Lockout implements ShouldQueue
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
     * @param  \Illuminate\Auth\Events\Lockout  $event
     * @return void
     */
    public function handle(LockoutEvent $event)
    {
        $user = User::where('email', $event->request->email)->first();

        activity('users')
            ->performedOn($user)
            ->withProperties(['email' => $event->request->email])
            ->log('Lockout for email '.$event->request->email);
    }
}
