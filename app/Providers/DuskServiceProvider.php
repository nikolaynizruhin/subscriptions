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
            $this->visit('/login');
            $this->script("localStorage.token = '{$user->api_token}'");
            $this->visit('/');

            return $this;
        });

        Browser::macro('signOut', function () {
            $this->visit('/')
                ->click('@account-button')
                ->clickLink('Logout')
                ->assertPathIs('/login');

            return $this;
        });
    }
}
