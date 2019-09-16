<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Passport\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Auto generate uuid when creating new oauth client
        Client::creating(function (Client $client) {
            $client->incrementing = false;
            $client->id = Str::uuid();
        });

        // Turn off auto incrementing for passport clients
        Client::retrieved(function (Client $client) {
            $client->incrementing = false;
        });
    }
}
