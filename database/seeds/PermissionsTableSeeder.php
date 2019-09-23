<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create user permissions
        Permission::create([
            'name' => 'create-users',
            'display_name' => 'Create users',
            'description' => 'Able to create new users.',
        ]);
        Permission::create([
            'name' => 'read-users',
            'display_name' => 'Read users',
            'description' => 'Able to read any user.',
        ]);
        Permission::create([
            'name' => 'update-users',
            'display_name' => 'Update users',
            'description' => 'Able to update any user.',
        ]);
        Permission::create([
            'name' => 'trash-users',
            'display_name' => 'Trash users',
            'description' => 'Able to trash any user.',
        ]);
        Permission::create([
            'name' => 'restore-users',
            'display_name' => 'Restore users',
            'description' => 'Able to restore any user.',
        ]);
        Permission::create([
            'name' => 'delete-users',
            'display_name' => 'Delete users',
            'description' => 'Able to delete any user.',
        ]);

        // Create admin permissions
        Permission::create([
            'name' => 'access-admin-dashboard',
            'display_name' => 'Access admin dashboard',
            'description' => 'Able to access the admin dashboard.',
        ]);

        // Create notification permissions
        Permission::create([
            'name' => 'recieve-admin-user-notifications',
            'display_name' => 'Recieve admin user notifications',
            'description' => 'Able to recieve admin notifications regarding users.',
        ]);

        // Create roles and assign existing permissions
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Able to access the admin dashboard.',
        ]);
        $admin->givePermissionTo('access-admin-dashboard');

        $userManager = Role::create([
            'name' => 'user-manager',
            'display_name' => 'User Manager',
            'description' => 'Able to create, read, update, trash, and delete users.',
        ]);
        $userManager->givePermissionTo('create-users');
        $userManager->givePermissionTo('read-users');
        $userManager->givePermissionTo('update-users');
        $userManager->givePermissionTo('trash-users');
        $userManager->givePermissionTo('restore-users');
        $userManager->givePermissionTo('delete-users');
        $userManager->givePermissionTo('recieve-admin-user-notifications');
    }
}
