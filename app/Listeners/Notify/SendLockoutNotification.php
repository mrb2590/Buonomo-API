<?php

namespace App\Listeners\Notify;

use App\Models\User;
use App\Notifications\Lockout;

class SendLockoutNotification
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = User::where('email', $event->request->email)->first();

        if ($user) {
            $user->notify(new Lockout);
        }
    }
}
