<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
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
        $roles = factory(Role::class, 5)->create();
        $totalRoles = Role::count();

        $response = $this->actingAs($user, 'api')->get('/v1/roles');

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
            ->assertJsonCount($totalRoles > 10 ? 10 : $totalRoles, 'data');

        $roles->each(function ($role) {
            $role->delete();
        });
        $user->delete();
    }

    /**
     * Test showing a single role.
     *
     * @return void
     */
    public function testShowRole()
    {
        $role = factory(Role::class)->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->get('/v1/roles/'.$role->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "id",
                    "name",
                    "display_name",
                    "description",
                ],
            ]);

        $role->delete();
        $user->delete();
    }
}
