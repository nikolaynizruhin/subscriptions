<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::share('app', [
            'name' => config('app.name', 'Libra'),
            'password_timeout' => config('auth.password_timeout', 10800),
            'stripe' => [
                'key' => config('cashier.key'),
            ]
        ]);
    }
}
