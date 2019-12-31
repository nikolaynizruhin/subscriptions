<?php

namespace App\Http\Middleware;

use App\Exceptions\MissingPaymentMethodException;
use Closure;

class RequirePaymentMethod
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user()->defaultPaymentMethod()) {
            throw new MissingPaymentMethodException;
        }

        return $next($request);
    }
}
