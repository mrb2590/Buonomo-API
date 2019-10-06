<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Restored
{
    use Dispatchable, SerializesModels;

    /**
     * The user that was restored.
     * 
     * @var \App\Models\User
     */
    public $user;

    /**
     * The user who restored the user.
     * 
     * @var \App\Models\User
     */
    public $restoredBy;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, User $restoredBy = null)
    {
        $this->user = $user;
        $this->restoredBy = $restoredBy;
    }
}
