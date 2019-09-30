<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class RoleTest extends TestCase
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
            'read-roles',
            'create-roles',
            'update-roles',
            'delete-roles',
        ]);
    }

    /**
     * Test showing multiple roles.
     *
     * @return void
     */
    public function test_admin_can_fetch_roles()
    {
        $roles = factory(Role::class, 5)->create();
        $totalRoles = Role::count();

        $response = $this->actingAs($this->admin, 'api')->get('/v1/admin/roles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        "id",
                        "name",
                        "display_name",
                        "description",
                        "created_by_id",
                        "updated_by_id",
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
            ->assertJsonCount($totalRoles > 10 ? 10 : $totalRoles, 'data');
    }

    /**
     * Test showing a single role.
     *
     * @return void
     */
    public function test_admin_can_fetch_single_role()
    {
        $role = factory(Role::class)->create();

        $response = $this->actingAs($this->admin, 'api')->get('/v1/admin/roles/'.$role->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "id",
                    "name",
                    "display_name",
                    "description",
                    "created_by_id",
                    "updated_by_id",
                    "created_at",
                    "updated_at",
                ],
            ]);
    }

    /**
     * Test creating a new role.
     *
     * @return void
     */
    public function test_admin_can_create_role()
    {
        $roleName = 'Test Role '.Str::random(5);

        $response = $this->actingAs($this->admin, 'api')
            ->json('POST', '/v1/admin/roles', [
                'name' => Str::slug($roleName),
                'display_name' => $roleName,
                'description' => 'This is a test role.',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    "id",
                    "name",
                    "display_name",
                    "description",
                    "created_by_id",
                    "updated_by_id",
                    "created_at",
                    "updated_at",
                ],
            ])
            ->assertJsonFragment([
                'name' => Str::slug($roleName),
                'display_name' => $roleName,
                'description' => 'This is a test role.',
            ]);
    }

    /**
     * Test updating a role.
     *
     * @return void
     */
    public function test_admin_can_update_role()
    {
        $role = factory(Role::class)->create();
        $roleName = 'Test Role '.Str::random(5);

        $response = $this->actingAs($this->admin, 'api')
            ->json('PATCH', '/v1/admin/roles/'.$role->id, [
                'name' => Str::slug($roleName),
                'display_name' => $roleName,
                'description' => 'This is a test role.',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "id",
                    "name",
                    "display_name",
                    "description",
                    "created_by_id",
                    "updated_by_id",
                    "created_at",
                    "updated_at",
                ],
            ])
            ->assertJsonFragment([
                'name' => Str::slug($roleName),
                'display_name' => $roleName,
                'description' => 'This is a test role.',
            ]);
    }

    /**
     * Test deleting a role.
     *
     * @return void
     */
    public function test_admin_can_delete_role()
    {
        $role = factory(Role::class)->create();

        $response = $this->actingAs($this->admin, 'api')->delete('/v1/admin/roles/'.$role->id);

        $response->assertStatus(204);

        $this->assertNull(Role::find($role->id));
    }
}
