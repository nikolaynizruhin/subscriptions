<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;

class DuskServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register the Dusk's browser macros.
     *
     * @return void
     */
    public function boot()
    {
        Browser::macro('actingAs', function (User $user) {
            $this->visit('/login')
                ->waitForLocation('/login')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('Login')
                ->waitForLocation('/');

            return $this;
        });

        Browser::macro('signOut', function (User $user) {
            $this->visit('/')
                ->waitForLocation('/')
                ->press($user->name)
                ->clickLink('Logout')
                ->assertPathIs('/login');

            return $this;
        });
    }
}
