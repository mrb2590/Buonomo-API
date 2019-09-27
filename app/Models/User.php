<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasRoles, HasUuid, Notifiable, SoftDeletes;

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
    protected $fillable = [
        'first_name', 'last_name', 'email', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at', 'email_verified_at',
    ];

    /**
     * Get the user who created this.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_by()
    {
        return $this->belongsTo(Folder::class);
    }

    /**
     * Get the user who last updated this.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updated_by()
    {
        return $this->belongsTo(Folder::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmail);
    }

    /**
     * Send the admin user registered notification.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function sendAdminPasswordResetNotification(User $user)
    {
        $this->notify(new \App\Notifications\Admin\PasswordReset($user));
    }

    /**
     * Send the admin user registered notification.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function sendAdminRegisteredUserNotification(User $user)
    {
        $this->notify(new \App\Notifications\Admin\UserRegistered($user));
    }

    /**
     * Send the admin user registered notification.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function sendAdminVerifiedUserNotification(User $user)
    {
        $this->notify(new \App\Notifications\Admin\UserVerified($user));
    }

    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete()
    {
        // Revoke all permissions and remove all roles
        $this->syncRoles();
        $this->syncPermissions();

        return parent::delete();
    }
}
