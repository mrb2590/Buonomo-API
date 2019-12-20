<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
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

        // Create role permissions
        Permission::create([
            'name' => 'create-roles',
            'display_name' => 'Create roles',
            'description' => 'Able to create new roles.',
        ]);
        Permission::create([
            'name' => 'read-roles',
            'display_name' => 'Read roles',
            'description' => 'Able to read roles.',
        ]);
        Permission::create([
            'name' => 'update-roles',
            'display_name' => 'Update roles',
            'description' => 'Able to update roles.',
        ]);
        Permission::create([
            'name' => 'delete-roles',
            'display_name' => 'Delete roles',
            'description' => 'Able to delete roles.',
        ]);

        // Create permission permissions
        Permission::create([
            'name' => 'read-permissions',
            'display_name' => 'Read permissions',
            'description' => 'Able to read permissions.',
        ]);

        // Create user role permissions
        Permission::create([
            'name' => 'assign-user-roles',
            'display_name' => 'Assign user roles',
            'description' => 'Able to assign roles to users.',
        ]);
        Permission::create([
            'name' => 'remove-user-roles',
            'display_name' => 'Remove user roles',
            'description' => 'Able to remove roles from users.',
        ]);

        // Create user permission permissions
        Permission::create([
            'name' => 'give-user-permissions',
            'display_name' => 'Give user permissions',
            'description' => 'Able to give permissions to users.',
        ]);
        Permission::create([
            'name' => 'revoke-user-permissions',
            'display_name' => 'Revoke user permissions',
            'description' => 'Able to revoke permissions from users.',
        ]);

        // Create role permission permissions
        Permission::create([
            'name' => 'give-role-permissions',
            'display_name' => 'Give role permissions',
            'description' => 'Able to give permissions to roles.',
        ]);
        Permission::create([
            'name' => 'revoke-role-permissions',
            'display_name' => 'Revoke role permissions',
            'description' => 'Able to revoke permissions from roles.',
        ]);

        // Create notification permissions
        Permission::create([
            'name' => 'recieve-admin-user-notifications',
            'display_name' => 'Recieve admin user notifications',
            'description' => 'Able to recieve admin notifications regarding users.',
        ]);

        // Create admin dashboard permissions
        Permission::create([
            'name' => 'access-admin-dashboard',
            'display_name' => 'Access admin dashboard',
            'description' => 'Able to access the admin dashboard.',
        ]);

        // Create activity permissions
        Permission::create([
            'name' => 'read-activity',
            'display_name' => 'Read activity',
            'description' => 'Able to read activity for anything.',
        ]);
        Permission::create([
            'name' => 'read-user-activity',
            'display_name' => 'Read user activity',
            'description' => 'Able to read activity for a user.',
        ]);
        Permission::create([
            'name' => 'read-role-activity',
            'display_name' => 'Read role activity',
            'description' => 'Able to read activity for a role.',
        ]);

        // Create roles and give permissions
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Able to access the admin dashboard.',
        ]);
        $admin->givePermissionTo('access-admin-dashboard');

        $userManager = Role::create([
            'name' => 'user-manager',
            'display_name' => 'User Manager',
            'description' => 'Able to read and modify users.',
        ]);
        $userManager->givePermissionTo('create-users');
        $userManager->givePermissionTo('read-users');
        $userManager->givePermissionTo('update-users');
        $userManager->givePermissionTo('trash-users');
        $userManager->givePermissionTo('restore-users');
        $userManager->givePermissionTo('delete-users');
        $userManager->givePermissionTo('assign-user-roles');
        $userManager->givePermissionTo('remove-user-roles');
        $userManager->givePermissionTo('give-user-permissions');
        $userManager->givePermissionTo('revoke-user-permissions');
        $userManager->givePermissionTo('recieve-admin-user-notifications');
        $userManager->givePermissionTo('read-user-activity');

        $roleManager = Role::create([
            'name' => 'role-manager',
            'display_name' => 'Role Manager',
            'description' => 'Able to read and modify roles.',
        ]);
        $roleManager->givePermissionTo('create-roles');
        $roleManager->givePermissionTo('read-roles');
        $roleManager->givePermissionTo('update-roles');
        $roleManager->givePermissionTo('delete-roles');
        $roleManager->givePermissionTo('read-permissions');
        $roleManager->givePermissionTo('give-role-permissions');
        $roleManager->givePermissionTo('revoke-role-permissions');
        $roleManager->givePermissionTo('read-role-activity');

        $activityManager = Role::create([
            'name' => 'activity-manager',
            'display_name' => 'Activity Manager',
            'description' => 'Able to read all activity.',
        ]);
        $activityManager->givePermissionTo('read-activity');
        $activityManager->givePermissionTo('read-user-activity');
        $activityManager->givePermissionTo('read-role-activity');
    }
}
