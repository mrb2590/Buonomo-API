<?php

namespace App\Providers;

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

        // // Auto generate uuid when creating new permissions
        // Permission::creating(function (Permission $permission) {
        //     $permission->incrementing = false;
        //     $permission->id = Str::uuid()->toString();
        // });

        // // Turn off auto incrementing for permissions
        // Permission::retrieved(function (Permission $permission) {
        //     $permission->incrementing = false;
        // });

        // // Auto generate uuid when creating new roles
        // Role::creating(function (Role $role) {
        //     $role->incrementing = false;
        //     $role->id = Str::uuid()->toString();
        // });

        // // Turn off auto incrementing for roles
        // Role::retrieved(function (Role $role) {
        //     $role->incrementing = false;
        // });

        // Serialize all dates to ISO 8601 format
        Carbon::serializeUsing(function (Carbon $timestamp) {
            return $timestamp->toIso8601String();
        });
    }
}
