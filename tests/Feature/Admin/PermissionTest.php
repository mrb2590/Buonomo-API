<?php

namespace Tests\Feature\Admin;

use App\Models\Permission;
use App\Models\User;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    /**
     * Test showing multiple permissions.
     *
     * @return void
     */
    public function testIndexPermissions()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'read-permissions']);

        $totalPermissions = Permission::count();

        $response = $this->actingAs($user, 'api')->get('/v1/admin/permissions');

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

        $user->forceDelete();
    }

    /**
     * Test showing a single permission.
     *
     * @return void
     */
    public function testShowPermission()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'read-permissions']);

        $permission = Permission::first();

        $response = $this->actingAs($user, 'api')->get('/v1/admin/permissions/'.$permission->id);

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

        $user->forceDelete();
    }
}
