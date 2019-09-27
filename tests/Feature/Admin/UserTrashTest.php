<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;

class UserTrashTest extends TestCase
{

    /**
     * Test showing multiple trashed users.
     *
     * @return void
     */
    public function testIndexTrashedUsers()
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
     * Test showing a single trashed user.
     *
     * @return void
     */
    public function testShowTrashedUser()
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
     * Test trashing a user.
     *
     * @return void
     */
    public function testStoreTrashedUser()
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
    public function testRestoreTrashedUser()
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
     * Test deleting a trashed user.
     *
     * @return void
     */
    public function testDestroyTrashedUser()
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
