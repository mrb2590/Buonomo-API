<?php

namespace App\Policies;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can read activity.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Activity  $activity
     * @return boolean
     */
    public function read(User $user, Activity $activity = null)
    {
        if ($user->can('read-activity')) {
            return true;
        }

        if ($activity) {
            if ($user->can('read-'.$activity->subject_type.'-activity')) {
                return true;
            }

            return $user->id === $activity->causer_id || $user->id === $activity->subject_id;
        }

        return false;
    }
}
