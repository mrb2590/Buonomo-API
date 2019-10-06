<?php

namespace App\Events\Role;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Deleted
{
    use Dispatchable, SerializesModels;

    /**
     * The role that was deleted.
     * 
     * @var \App\Models\Role
     */
    public $role;

    /**
     * The user who deleted the role.
     * 
     * @var \App\Models\User
     */
    public $deletedBy;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Role $role, User $deletedBy = null)
    {
        $this->role = $role;
        $this->deletedBy = $deletedBy;
    }
}
