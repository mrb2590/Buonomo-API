<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;

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
            $client->id = Str::uuid()->toString();
        });

        // Turn off auto incrementing for passport clients
        Client::retrieved(function (Client $client) {
            $client->incrementing = false;
        });

        // Serialize all dates to ISO 8601 format
        Carbon::serializeUsing(function (Carbon $timestamp) {
            return $timestamp->toIso8601String();
        });

        // Map polymorphic relaitonship class names
        Relation::morphMap([
            'user' => \App\Models\User::class,
            'role' => \App\Models\Role::class,
            'permission' => \App\Models\Permission::class,
        ]);
    }
}
