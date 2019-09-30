<?php

namespace Tests\Feature\Admin;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
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
            'read-permissions',
        ]);
    }

    /**
     * Test showing multiple permissions.
     *
     * @return void
     */
    public function test_admin_can_fetch_permissions()
    {
        $totalPermissions = Permission::count();

        $response = $this->actingAs($this->admin, 'api')->get('/v1/admin/permissions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        "id",
                        "name",
                        "display_name",
                        "description",
                        "created_at",
                        "updated_at",
                    ],
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'to',
                    'total',
                ],
            ])
            ->assertJsonCount($totalPermissions > 10 ? 10 : $totalPermissions, 'data');
    }

    /**
     * Test showing a single permission.
     *
     * @return void
     */
    public function test_admin_can_fetch_single_permission()
    {
        $permission = Permission::first();

        $response = $this->actingAs($this->admin, 'api')->get('/v1/admin/permissions/'.$permission->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "id",
                    "name",
                    "display_name",
                    "description",
                    "created_at",
                    "updated_at",
                ],
            ]);
    }
}
