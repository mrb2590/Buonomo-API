<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The member user.
     * 
     * @var \App\Models\User
     */
    protected $member;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->member = factory(User::class)->create();
    }

    /**
     * Test showing multiple roles.
     *
     * @return void
     */
    public function test_member_can_fetch_roles()
    {
        $roles = factory(Role::class, 5)->create();
        $totalRoles = Role::count();

        $response = $this->actingAs($this->member, 'api')->get('/v1/roles');

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
    }

    /**
     * Test showing a single role.
     *
     * @return void
     */
    public function test_member_can_fetch_single_role()
    {
        $role = factory(Role::class)->create();

        $response = $this->actingAs($this->member, 'api')->get('/v1/roles/'.$role->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    "id",
                    "name",
                    "display_name",
                    "description",
                ],
            ]);
    }
}
