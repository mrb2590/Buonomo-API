<?php

namespace App\Traits;

use App\Models\Activity;

trait HasActivity
{
    /**
     * Get the models's activity.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->orderBy('created_at', 'desc');
    }

    /**
     * Get the models's activity they caused.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function caused_activity()
    {
        return $this->morphMany(Activity::class, 'causer')->orderBy('created_at', 'desc');
    }
}
