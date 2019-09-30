<?php

namespace Tests\Feature\Admin;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PermissionRoleTest extends TestCase
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
            'give-role-permissions',
            'revoke-role-permissions',
        ]);
    }

    /**
     * Test giving a permission to a role.
     *
     * @return void
     */
    public function test_admin_can_give_permission_to_role()
    {
        $role = factory(Role::class)->create();
        $permission = Permission::first();

        $response = $this->actingAs($this->admin, 'api')
            ->post('/v1/admin/roles/'.$role->id.'/permissions/'.$permission->id);

        $response->assertStatus(204);
    }

    /**
     * Test revoking a permission from a role.
     *
     * @return void
     */
    public function test_admin_can_revoke_permission_from_role()
    {
        $role = factory(Role::class)->create();
        $permission = Permission::first();
        $role->givePermissionTo($permission);

        $this->app->make(PermissionRegistrar::class)->registerPermissions();

        $response = $this->actingAs($this->admin, 'api')
            ->delete('/v1/admin/roles/'.$role->id.'/permissions/'.$permission->id);

        $response->assertStatus(204);
    }
}
