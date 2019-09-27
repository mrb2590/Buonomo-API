<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test showing the current user.
     *
     * @return void
     */
    public function testShowCurrentUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->get('/v1/user');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'username',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJsonFragment([
                'email' => $user->email,
            ]);

        $user->forceDelete();
    }

    /**
     * Test updating the current user.
     *
     * @return void
     */
    public function testUpdateCurrentUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')
            ->json('PATCH', '/v1/user', [
                'first_name' => 'first',
                'last_name' => 'last',
                'username' => 'username',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'username',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJsonFragment([
                'first_name' => 'first',
                'last_name' => 'last',
                'username' => 'username',
            ]);

        $user->forceDelete();
    }

    /**
     * Test deleting the current user.
     *
     * @return void
     */
    public function testDestroyCurrentUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->delete('/v1/user');

        $response->assertStatus(204);

        $this->assertNull(User::find($user->id));

        $user->forceDelete();
    }
}
