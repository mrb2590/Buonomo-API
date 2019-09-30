<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The admin user.
     * 
     * @var \App\Models\User
     */
    protected $admin;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->admin = factory(User::class)->create();
        $this->admin->givePermissionTo([
            'access-admin-dashboard',
            'assign-user-roles',
            'remove-user-roles',
        ]);
    }

    /**
     * Test assigning a role to a user.
     *
     * @return void
     */
    public function test_admin_can_assign_user_role()
    {
        $role = factory(Role::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($this->admin, 'api')
            ->post('/v1/admin/users/'.$user->id.'/roles/'.$role->id);

        $response->assertStatus(204);
    }

    /**
     * Test removing a role from a user.
     *
     * @return void
     */
    public function testDestroyUserRole()
    {
        $role = factory(Role::class)->create();
        $user = factory(User::class)->create();
        $user->assignRole($role);

        $response = $this->actingAs($this->admin, 'api')
            ->delete('/v1/admin/users/'.$user->id.'/roles/'.$role->id);

        $response->assertStatus(204);
    }
}
