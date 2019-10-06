<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // User events
        \Illuminate\Auth\Events\Registered::class => [
            \App\Listeners\Log\User\Registered::class,
            \App\Listeners\Notify\Admin\User\SendUserRegisteredNotification::class,
        ],
        \App\Events\User\Verified::class => [
            \App\Listeners\Log\User\Verified::class,
            \App\Listeners\Notify\Admin\User\SendUserVerifiedNotification::class,
        ],
        \Illuminate\Auth\Events\PasswordReset::class => [
            \App\Listeners\Log\User\PasswordReset::class,
            \App\Listeners\Notify\Admin\User\SendPasswordResetNotification::class,
        ],
        \Illuminate\Auth\Events\Attempting::class => [
            \App\Listeners\Log\User\AttemptedLogin::class,
        ],
        // \Illuminate\Auth\Events\Authenticated::class => [
        //     \App\Listeners\Log\User\Authenticated::class,
        // ],
        \Illuminate\Auth\Events\Login::class => [
            \App\Listeners\Log\User\Login::class,
        ],
        \Illuminate\Auth\Events\Failed::class => [
            \App\Listeners\Log\User\FailedLogin::class,
        ],
        \Illuminate\Auth\Events\Logout::class => [
            \App\Listeners\Log\User\Logout::class,
        ],
        \Illuminate\Auth\Events\Lockout::class => [
            \App\Listeners\Log\User\Lockout::class,
        ],

        // User model events
        \App\Events\User\Created::class => [
            \App\Listeners\Log\User\Created::class,
        ],
        \App\Events\User\Updated::class => [
            \App\Listeners\Log\User\Updated::class,
        ],
        \App\Events\User\Deleted::class => [
            \App\Listeners\Log\User\Deleted::class,
        ],
        \App\Events\User\Trashed::class => [
            \App\Listeners\Log\User\Trashed::class,
        ],
        \App\Events\User\Restored::class => [
            \App\Listeners\Log\User\Restored::class,
        ],

        // Role model events
        \App\Events\Role\Created::class => [
            \App\Listeners\Log\Role\Created::class,
        ],
        \App\Events\Role\Updated::class => [
            \App\Listeners\Log\Role\Updated::class,
        ],
        \App\Events\Role\Deleted::class => [
            \App\Listeners\Log\Role\Deleted::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
