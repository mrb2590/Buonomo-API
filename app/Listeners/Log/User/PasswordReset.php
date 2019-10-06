<?php

namespace App\Listeners\Log\User;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset as PasswordResetEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordReset implements ShouldQueue
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
     * @param  \Illuminate\Auth\Events\PasswordReset  $event
     * @return void
     */
    public function handle(PasswordResetEvent $event)
    {
        $user = User::find($event->user->id);

        activity('users')
            ->performedOn($user)
            ->log('User "'.$user->name.'" has request their password');
    }
}
