<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Role;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Role::class, function (Faker $faker) {
    $name = Str::random(20);

    return [
        'name' => Str::slug($name),
        'display_name' => $name,
        'description' => $faker->paragraph(),
    ];
});
