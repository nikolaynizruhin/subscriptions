<?php

namespace App\Http\Middleware;

use App\User;
use Illuminate\Auth\Middleware\RequirePassword as RequirePasswordMiddleware;

class RequirePassword extends RequirePasswordMiddleware
{
    /**
     * Determine if the confirmation timeout has expired.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldConfirmPassword($request)
    {
        $confirmedAt = time() - $this->confirmedAt($request->user());

        return $confirmedAt > config('auth.password_timeout', 10800);
    }

    /**
     * Get user password confirmation timestamp.
     *
     * @param  \App\User  $user
     * @return int
     */
    protected function confirmedAt(User $user)
    {
        return $user->password_confirmed_at ? $user->password_confirmed_at->timestamp : 0;
    }
}
