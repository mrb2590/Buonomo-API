<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Trashed
{
    use Dispatchable, SerializesModels;

    /**
     * The user that was trashed.
     * 
     * @var \App\Models\User
     */
    public $user;

    /**
     * The user who trashed the user.
     * 
     * @var \App\Models\User
     */
    public $trashedBy;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, User $trashedBy = null)
    {
        $this->user = $user;
        $this->trashedBy = $trashedBy;
    }
}
