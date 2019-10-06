<?php

namespace App\Events\Role;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Updated
{
    use Dispatchable, SerializesModels;

    /**
     * The role that was updated.
     * 
     * @var \App\Models\Role
     */
    public $role;

    /**
     * The user who updated the role.
     * 
     * @var \App\Models\User
     */
    public $updatedBy;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Role $role, User $updatedBy = null)
    {
        $this->role = $role;
        $this->updatedBy = $updatedBy;
    }
}
