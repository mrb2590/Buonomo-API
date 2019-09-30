<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTrashTest extends TestCase
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
            'read-users',
            'trash-users',
            'restore-users',
            'delete-users',
        ]);
    }

    /**
     * Test showing multiple trashed users.
     *
     * @return void
     */
    public function test_admin_can_fetch_trashed_users()
    {
        $user = factory(User::class)->create();
        $user->delete();
        $totalUsers = User::onlyTrashed()->count();

        $response = $this->actingAs($this->admin, 'api')->get('/v1/admin/trash/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                        'email_verified_at',
                        'username',
                        'created_by_id',
                        'updated_by_id',
                        'created_at',
                        'updated_at',
                        'deleted_at',
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
            ->assertJsonCount($totalUsers > 10 ? 10 : $totalUsers, 'data');
    }

    /**
     * Test showing a single trashed user.
     *
     * @return void
     */
    public function test_admin_can_fetch_single_trashed_user()
    {
        $user = factory(User::class)->create();
        $user->delete();

        $response = $this->actingAs($this->admin, 'api')->get('/v1/admin/trash/users/'.$user->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'email_verified_at',
                    'username',
                    'created_by_id',
                    'updated_by_id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
            ])
            ->assertJsonFragment([
                'email' => $user->email,
            ]);
    }

    /**
     * Test trashing a user.
     *
     * @return void
     */
    public function test_admin_can_trash_user()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($this->admin, 'api')->put('/v1/admin/trash/users/'.$user->id);

        $response->assertStatus(200);

        $this->assertNotNull(User::onlyTrashed()->find($user->id));
    }

    /**
     * Test Restoring a trashed user.
     *
     * @return void
     */
    public function test_admin_can_restore_user()
    {
        $user = factory(User::class)->create();
        $user->delete();

        $response = $this->actingAs($this->admin, 'api')->post('/v1/admin/trash/users/'.$user->id.'/restore');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'email_verified_at',
                    'username',
                    'created_by_id',
                    'updated_by_id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
            ])
            ->assertJsonFragment([
                'email' => $user->email,
            ]);

        $this->assertNotNull(User::withoutTrashed()->find($user->id));
    }

    /**
     * Test deleting a trashed user.
     *
     * @return void
     */
    public function test_admin_can_delete_trashed_user()
    {
        $user = factory(User::class)->create();
        $user->delete();

        $response = $this->actingAs($this->admin, 'api')->delete('/v1/admin/trash/users/'.$user->id);

        $response->assertStatus(204);

        $this->assertNull(User::withTrashed()->find($user->id));
    }
}
