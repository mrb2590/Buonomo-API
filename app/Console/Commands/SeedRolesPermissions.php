<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeedRolesPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:roles-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed all default roles and permissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $permissions = json_decode(Storage::disk('data')->get('permissions.json'));
        $roles = json_decode(Storage::disk('data')->get('roles.json'));

        foreach ($permissions as $permission) {
            $name = Str::slug($permission->name);

            if (Permission::where('name', $name)->exists()) {
                $this->line('Permission "'.$permission->name.'" already exists');

                continue;
            }

            Permission::create([
                'name' => $name,
                'display_name' => $permission->name,
                'description' => $permission->description,
            ]);

            $this->line('Created new permission "'.$permission->name.'"');
        }

        foreach ($roles as $role) {
            $name = Str::slug($role->name);
            $existingRole = Role::where('name', $name)->first();

            if (is_null($existingRole)) {
                $existingRole = Role::create([
                    'name' => $name,
                    'display_name' => $role->name,
                    'description' => $role->description,
                ]);

                $this->line('Created new role "'.$role->name.'"');
            } else {
                $this->line('Role "'.$role->name.'" already exists');
            }

            foreach ($role->permissions as $permissionName) {
                if ($existingRole->hasPermissionTo($permissionName)) {
                    $this->line('Role "'.$existingRole->display_name.'" already has permission to "'.$permissionName.'"');

                    continue;
                }

                $existingRole->givePermissionTo($permissionName);

                $this->line('Gave permission "'.$permissionName.'" to the role "'.$existingRole->name.'"');
            }
        }
    }
}
