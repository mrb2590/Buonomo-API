<?php

namespace App\Models;

use App\Traits\HasUuid;
use Exception;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasUuid;

    /**
     * The searchable columns.
     * 
     * @var array
     */
    public static $searchableColumns = ['name', 'description'];

    /**
     * The sortable columns.
     * 
     * @var array
     */
    public static $sortableColumns = ['name'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'description', 'guard_name'];

    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete()
    {
        throw new Exception('Permissions can not be deleted.');
    }
}
