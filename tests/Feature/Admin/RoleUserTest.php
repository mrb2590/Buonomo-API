<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class RoleUserTest extends TestCase
{
    /**
     * Test assigning a role to a user.
     *
     * @return void
     */
    public function testStoreUserRole()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'assign-user-roles']);

        $userToAssignRole = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $response = $this->actingAs($user, 'api')
            ->post('/v1/admin/users/'.$userToAssignRole->id.'/roles/'.$role->id);

        $response->assertStatus(204);

        $role->delete();
        $userToAssignRole->forceDelete();
        $user->forceDelete();
    }

    /**
     * Test removing a role from a user.
     *
     * @return void
     */
    public function testDestroyUserRole()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'remove-user-roles']);

        $userToRemoveRole = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $user->assignRole($role);

        $response = $this->actingAs($user, 'api')
            ->delete('/v1/admin/users/'.$userToRemoveRole->id.'/roles/'.$role->id);

        $response->assertStatus(204);

        $role->delete();
        $userToRemoveRole->forceDelete();
        $user->forceDelete();
    }
}
