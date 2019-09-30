<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
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
     * Test showing multiple permissions.
     *
     * @return void
     */
    public function test_member_can_fetch_permissions()
    {
        $totalPermissions = Permission::count();

        $response = $this->actingAs($this->member, 'api')->get('/v1/permissions');

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
    }

    /**
     * Test showing a single permission.
     *
     * @return void
     */
    public function test_member_can_fetch_single_permission()
    {
        $permission = Permission::first();

        $response = $this->actingAs($this->member, 'api')->get('/v1/permissions/'.$permission->id);

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
