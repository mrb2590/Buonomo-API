<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create([
            'email' => config('logging.email'),
        ]);

        Role::each(function ($role) use ($user) {
            $user->assignRole($role);
        });

        $this->call(UsersTableSeeder::class);
    }
}
