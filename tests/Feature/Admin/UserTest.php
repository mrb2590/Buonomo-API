<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserTest extends TestCase
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
            'create-users',
            'update-users',
            'delete-users',
        ]);
    }

    /**
     * Test showing multiple users.
     *
     * @return void
     */
    public function test_admin_can_fetch_users()
    {
        factory(User::class, 5)->create();
        $totalUsers = User::count();

        $response = $this->actingAs($this->admin, 'api')->get('/v1/admin/users');

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
     * Test showing a single user.
     *
     * @return void
     */
    public function test_admin_can_fetch_single_user()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($this->admin, 'api')->get('/v1/admin/users/'.$user->id);

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
            ]);
    }

    /**
     * Test creating a new user.
     *
     * @return void
     */
    public function test_admin_can_create_user()
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('POST', '/v1/admin/users', [
                'first_name' => 'First',
                'last_name' => 'Last',
                'email' => 'test@example.com',
                'username' => 'test-username',
                'password' => 'password',
                'password_confirmation' => 'password',
                'email_verified' => true,
            ]);

        $response->assertStatus(201)
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
                'first_name' => 'First',
                'last_name' => 'Last',
                'email' => 'test@example.com',
                'username' => 'test-username',
            ])
            ->assertJsonMissing([
                'email_verified_at' => null,
            ]);

    }

    /**
     * Test updating a user.
     *
     * @return void
     */
    public function test_admin_can_update_user()
    {
        $user = factory(User::class)->create();

        $email = 'test'.Str::random(5).'@example.com';
        $username = Str::random(5);

        $response = $this->actingAs($this->admin, 'api')
            ->json('PATCH', '/v1/admin/users/'.$user->id, [
                'first_name' => 'First1',
                'last_name' => 'Last1',
                'email' => $email,
                'username' => $username,
                'password' => 'password',
                'password_confirmation' => 'password',
                'email_verified' => false,
            ]);

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
                'first_name' => 'First1',
                'last_name' => 'Last1',
                'email' => $email,
                'username' => $username,
                'email_verified_at' => null,
            ]);
    }

    /**
     * Test deleting a user.
     *
     * @return void
     */
    public function test_admin_can_delete_user()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($this->admin, 'api')->delete('/v1/admin/users/'.$user->id);

        $response->assertStatus(204);

        $this->assertNull(User::withTrashed()->find($user->id));
    }
}
