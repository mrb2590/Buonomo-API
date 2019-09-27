<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class RoleTest extends TestCase
{
    /**
     * Test showing multiple roles.
     *
     * @return void
     */
    public function testIndexRoles()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'read-roles']);

        $roles = factory(Role::class, 5)->create();
        $totalRoles = Role::count();

        $response = $this->actingAs($user, 'api')->get('/v1/admin/roles');

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

        $roles->each(function ($role) {
            $role->delete();
        });

        $user->forceDelete();
    }

    /**
     * Test showing a single role.
     *
     * @return void
     */
    public function testShowRole()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'read-roles']);

        $role = factory(Role::class)->create();

        $response = $this->actingAs($user, 'api')->get('/v1/admin/roles/'.$role->id);

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

        $role->delete();
        $user->forceDelete();
    }

    /**
     * Test creating a new role.
     *
     * @return void
     */
    public function testStoreRole()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'create-roles']);

        $roleName = 'Test Role '.Str::random(5);

        $response = $this->actingAs($user, 'api')
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

        $content = $response->decodeResponseJson();
        $role = Role::findOrFail($content['data']['id']);

        $role->delete();
        $user->forceDelete();
    }

    /**
     * Test updating a role.
     *
     * @return void
     */
    public function testUpdateRole()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'update-roles']);

        $role = factory(Role::class)->create();
        $roleName = 'Test Role '.Str::random(5);

        $response = $this->actingAs($user, 'api')
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

        $role->delete();
        $user->forceDelete();
    }

    /**
     * Test deleting a role.
     *
     * @return void
     */
    public function testDestroyRole()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'delete-roles']);

        $role = factory(Role::class)->create();

        $response = $this->actingAs($user, 'api')->delete('/v1/admin/roles/'.$role->id);

        $response->assertStatus(204);

        $this->assertNull(Role::find($role->id));

        $role->delete();
        $user->forceDelete();
    }
}
