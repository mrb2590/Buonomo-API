<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Verified
{
    use Dispatchable, SerializesModels;

    /**
     * The user that was verified.
     * 
     * @var \App\Models\User
     */
    public $user;

    /**
     * The user who verified the user.
     * 
     * @var \App\Models\User
     */
    public $verifiedBy;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, User $verifiedBy = null)
    {
        $this->user = $user;
        $this->verifiedBy = $verifiedBy;
    }
}
