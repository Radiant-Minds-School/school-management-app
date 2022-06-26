<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword as PasswordsCanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable, PasswordsCanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'last_seen',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->user_type == 'admin';
    }

    /**
     * Check if user is a master
     *
     * @return bool
     */
    public function isMaster()
    {
        return $this->user_type == 'master';
    }

    /**
     * Check if user is a developer
     *
     * @return bool
     */
    public function isDev()
    {
        return $this->is_dev == true;
    }

    /**
     * Check if user is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->is_active == true;
    }

    /**
     * Check if user is verified
     *
     * @return bool
     */
    public function isVerified()
    {
        return $this->is_verified == true;
    }

    /**
     * Check if user is the HOS
     *
     * @return bool
     */
    public function isHos()
    {
        return $this->is_hos == true;
    }

    /**
     * Get HOS
     *
     * @return User
     */
    public static function getHos()
    {
        return User::where('is_hos', true)->first();
    }
}
