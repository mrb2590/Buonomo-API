<?php

namespace App\Events\Role;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Created
{
    use Dispatchable, SerializesModels;

    /**
     * The role that was created.
     * 
     * @var \App\Models\Role
     */
    public $role;

    /**
     * The user who created the role.
     * 
     * @var \App\Models\User
     */
    public $createdBy;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Role $role, User $createdBy = null)
    {
        $this->role = $role;
        $this->createdBy = $createdBy;
    }
}
