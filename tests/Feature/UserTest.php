<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test fetching the current user.
     *
     * @return void
     */
    public function testFetchingCurrentUser()
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
    public function testUpdatingCurrentUser()
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
    public function testDeletingCurrentUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->delete('/v1/user');

        $response->assertStatus(204);

        $this->assertNull(User::find($user->id));

        $user->forceDelete();
    }
}
