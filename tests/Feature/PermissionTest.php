<?php

namespace Tests\Feature;

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
        $totalPermissions = Permission::count();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->get('/v1/permissions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        "id",
                        "name",
                        "display_name",
                        "description",
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

        $user->delete();
    }

    /**
     * Test showing a single permission.
     *
     * @return void
     */
    public function testShowPermission()
    {
        $permission = Permission::first();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->get('/v1/permissions/'.$permission->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "id",
                    "name",
                    "display_name",
                    "description",
                ],
            ]);

        $user->delete();
    }
}
