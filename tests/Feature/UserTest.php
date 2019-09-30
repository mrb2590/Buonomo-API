<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
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
     * Test showing the current user.
     *
     * @return void
     */
    public function test_member_can_fetch_theirself()
    {
        $response = $this->actingAs($this->member, 'api')->get('/v1/user');

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
                'email' => $this->member->email,
            ]);
    }

    /**
     * Test updating the current user.
     *
     * @return void
     */
    public function test_member_can_update_theirself()
    {
        $response = $this->actingAs($this->member, 'api')
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
    }

    /**
     * Test deleting the current user.
     *
     * @return void
     */
    public function test_member_can_delete_theirself()
    {
        $response = $this->actingAs($this->member, 'api')->delete('/v1/user');

        $response->assertStatus(204);

        $this->assertNull(User::find($this->member->id));
    }
}
