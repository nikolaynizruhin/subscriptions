<?php

namespace App;

use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'password_confirmed_at', 'trial_ends_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'subscriptions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_confirmed_at' => 'datetime',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'trial_ends_at',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * Reset the password confirmation timeout.
     *
     * @return bool
     */
    public function resetPasswordConfirmationTimeout()
    {
        return $this->update(['password_confirmed_at' => now()]);
    }

    /**
     * Determine if the confirmation timeout has expired.
     *
     * @return bool
     */
    public function shouldConfirmPassword()
    {
        $confirmedAt = time() - $this->password_confirmed_timestamp;

        return $confirmedAt > config('auth.password_timeout', 10800);
    }

    /**
     * Get the user's password confirmed timestamp.
     *
     * @return int
     */
    public function getPasswordConfirmedTimestampAttribute()
    {
        return $this->password_confirmed_at ? $this->password_confirmed_at->timestamp : 0;
    }
}
