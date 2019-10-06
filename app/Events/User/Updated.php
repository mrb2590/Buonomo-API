<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Updated
{
    use Dispatchable, SerializesModels;

    /**
     * The user that was updated.
     * 
     * @var \App\Models\User
     */
    public $user;

    /**
     * The user who updated the user.
     * 
     * @var \App\Models\User
     */
    public $updatedBy;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, User $updatedBy = null)
    {
        $this->user = $user;
        $this->updatedBy = $updatedBy;
    }
}
