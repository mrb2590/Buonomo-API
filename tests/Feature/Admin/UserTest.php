<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test fetching a single user.
     *
     * @return void
     */
    public function testFetchingUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->get('/v1/admin/users/'.$user->id);

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

        $user->forceDelete();
    }

    /**
     * Test fetching multiple users.
     *
     * @return void
     */
    public function testFetchingMultipleUsers()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->get('/v1/admin/users');

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
            ->assertJsonCount(10, 'data');

        $user->forceDelete();
    }

    /**
     * Test creating a user.
     *
     * @return void
     */
    public function testCreatingUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('POST', '/v1/admin/users', [
                'first_name' => 'First',
                'last_name' => 'Last',
                'email' => 'test@example.com',
                'username' => 'test-username',
                'password' => 'password',
                'password_confirmation' => 'password',
                'email_verified' => false,
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
                'email_verified_at' => null,
            ]);

        $user->forceDelete();

        if ($user = User::where('email', 'test@example.com')->first()) {
            $user->forceDelete();
        }

    }

    /**
     * Test updating a user.
     *
     * @return void
     */
    public function testUpdatingUser()
    {
        $user = factory(User::class)->create();
        $userToUpdate = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('PATCH', '/v1/admin/users/'.$userToUpdate->id, [
                'first_name' => 'First1',
                'last_name' => 'Last1',
                'email' => 'test1@emaple.com',
                'username' => 'test-username1',
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
                'email' => 'test1@emaple.com',
                'username' => 'test-username1',
                'email_verified_at' => null,
            ]);

        $user->forceDelete();
        $userToUpdate->forceDelete();
    }


    /**
     * Test trashing a user.
     *
     * @return void
     */
    public function testTrashingUser()
    {
        $user = factory(User::class)->create();
        $userToTrash = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->delete('/v1/admin/users/'.$userToTrash->id);

        $response->assertStatus(204);

        $user->forceDelete();
        $userToTrash->forceDelete();
    }
}
