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
                'first_name' => 'First',
                'last_name' => 'Last',
                'username' => 'test-username',
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
                'first_name' => 'First',
                'last_name' => 'Last',
                'username' => 'test-username',
            ]);

        $user->forceDelete();
    }


    /**
     * Test trashing the current user.
     *
     * @return void
     */
    public function testTrashingCurrentUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->delete('/v1/user');

        $response->assertStatus(204);

        $user->forceDelete();
    }
}
