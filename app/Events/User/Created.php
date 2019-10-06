<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Created
{
    use Dispatchable, SerializesModels;

    /**
     * The user that was created.
     * 
     * @var \App\Models\User
     */
    public $user;

    /**
     * The user who created the user.
     * 
     * @var \App\Models\User
     */
    public $createdBy;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, User $createdBy = null)
    {
        $this->user = $user;
        $this->createdBy = $createdBy;
    }
}
