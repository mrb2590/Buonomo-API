<?php

namespace App\Models;

use App\Traits\HasUuid;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasUuid;

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
        // Revoke all permissions
        $this->syncPermissions();

        // Remove this role from all users
        $this->users()->each(function ($user) {
            $user->removeRole($this);
        });

        return parent::delete();
    }
}
