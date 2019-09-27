<?php

namespace Tests\Feature\Admin;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class PermissionRoleTest extends TestCase
{
    /**
     * Test giving a permission to a role.
     *
     * @return void
     */
    public function testStoreRolePermission()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'give-role-permissions']);

        $role = factory(Role::class)->create();
        $permission = Permission::first();

        $response = $this->actingAs($user, 'api')
            ->post('/v1/admin/roles/'.$role->id.'/permissions/'.$permission->id);

        $response->assertStatus(204);

        $role->delete();
        $user->forceDelete();
    }

    /**
     * Test revoking a permission from a role.
     *
     * @return void
     */
    public function testDestroyRolePermission()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'revoke-role-permissions']);

        $role = factory(Role::class)->create();
        $permission = Permission::first();
        $role->givePermissionTo($permission);

        $response = $this->actingAs($user, 'api')
            ->delete('/v1/admin/roles/'.$role->id.'/permissions/'.$permission->id);

        $response->assertStatus(204);

        $role->delete();
        $user->forceDelete();
    }
}
