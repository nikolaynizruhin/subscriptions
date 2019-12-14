<?php

namespace App\Http\Middleware;

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
        return $request->user()->shouldConfirmPassword();
    }
}
