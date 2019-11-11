<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_login()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('Login')
                ->waitForLocation('/')
                ->assertPathIs('/')
                ->signOut($user);
        });
    }

    /** @test */
    public function guest_cant_visit_dashboard()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitForLocation('/login')
                ->assertPathIs('/login');
        });
    }

    /** @test */
    public function auth_user_cant_visit_login()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/login')
                ->waitForLocation('/')
                ->assertPathIs('/')
                ->signOut($user);
        });
    }

    /** @test */
    public function user_can_logout()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->click('@account-button')
                ->clickLink('Logout')
                ->assertPathIs('/login');
        });
    }
}
