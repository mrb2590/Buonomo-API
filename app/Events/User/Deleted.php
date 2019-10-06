<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Deleted
{
    use Dispatchable, SerializesModels;

    /**
     * The user that was deleted.
     * 
     * @var \App\Models\User
     */
    public $user;

    /**
     * The user who deleted the user.
     * 
     * @var \App\Models\User
     */
    public $deletedBy;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, User $deletedBy = null)
    {
        $this->user = $user;
        $this->deletedBy = $deletedBy;
    }
}
