<?php

namespace App\Models;

use App\Traits\HasUuid;
use Spatie\Activitylog\Models\Activity as BaseActivity;

class Activity extends BaseActivity
{
    use HasUuid;

    /**
     * The searchable columns.
     * 
     * @var array
     */
    public static $searchableColumns = ['description'];

    /**
     * The sortable columns.
     * 
     * @var array
     */
    public static $sortableColumns = ['created_at', 'updated_at'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
}
