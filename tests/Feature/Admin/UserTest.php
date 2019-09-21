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
        $user->givePermissionTo(['access-admin-dashboard', 'read-users']);

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
            ])
            ->assertJsonFragment([
                'email' => $user->email,
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
        $user->givePermissionTo(['access-admin-dashboard', 'read-users']);
        $totalUsers = User::count();

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
            ->assertJsonCount($totalUsers > 10 ? 10 : $totalUsers, 'data');

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
        $user->givePermissionTo(['access-admin-dashboard', 'create-users']);

        $response = $this->actingAs($user, 'api')
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
        $user->givePermissionTo(['access-admin-dashboard', 'update-users']);
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
     * Test deleting a user.
     *
     * @return void
     */
    public function testDeletingUser()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'delete-users']);
        $userToDelete = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->delete('/v1/admin/users/'.$userToDelete->id);

        $response->assertStatus(204);

        $this->assertNull(User::withTrashed()->find($userToDelete->id));

        $user->forceDelete();
        $userToDelete->forceDelete();
    }

    /**
     * Test trashing a user.
     *
     * @return void
     */
    public function testTrashingUser()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'trash-users']);
        $userToTrash = factory(User::class)->create();

        $response = $this->actingAs($user, 'api')->put('/v1/admin/trash/users/'.$userToTrash->id);

        $response->assertStatus(200);

        $this->assertNotNull(User::onlyTrashed()->find($userToTrash->id));

        $user->forceDelete();
        $userToTrash->forceDelete();
    }

    /**
     * Test Restoring a trashed user.
     *
     * @return void
     */
    public function testRestoringUser()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'restore-users']);
        $userToRestore = factory(User::class)->create();
        $userToRestore->delete();

        $response = $this->actingAs($user, 'api')->post('/v1/admin/trash/users/'.$userToRestore->id.'/restore');

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
                'email' => $userToRestore->email,
            ]);

        $this->assertNotNull(User::withoutTrashed()->find($userToRestore->id));

        $user->forceDelete();
        $userToRestore->forceDelete();
    }
    /**
     * Test fetching a single trashed user.
     *
     * @return void
     */
    public function testFetchingTrashedUser()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'read-users']);
        $trashedUser = factory(User::class)->create();
        $trashedUser->delete();

        $response = $this->actingAs($user, 'api')->get('/v1/admin/trash/users/'.$trashedUser->id);

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
                'email' => $trashedUser->email,
            ]);

        $trashedUser->forceDelete();
        $user->forceDelete();
    }

    /**
     * Test fetching multiple trashed users.
     *
     * @return void
     */
    public function testFetchingMultipleTrashedUsers()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'read-users']);
        $trashedUser = factory(User::class)->create();
        $trashedUser->delete();
        $totalUsers = User::onlyTrashed()->count();

        $response = $this->actingAs($user, 'api')->get('/v1/admin/trash/users');

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

        $trashedUser->forceDelete();
        $user->forceDelete();
    }

    /**
     * Test deleting a trashed user.
     *
     * @return void
     */
    public function testDeletingTrashedUser()
    {
        $user = factory(User::class)->create();
        $user->givePermissionTo(['access-admin-dashboard', 'delete-users']);
        $userToDelete = factory(User::class)->create();
        $userToDelete->delete();

        $response = $this->actingAs($user, 'api')->delete('/v1/admin/trash/users/'.$userToDelete->id);

        $response->assertStatus(204);

        $this->assertNull(User::withTrashed()->find($userToDelete->id));

        $user->forceDelete();
        $userToDelete->forceDelete();
    }
}
