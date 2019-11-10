<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guest_can_register()
    {
        $user = factory(User::class)->make();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/register')
                ->type('name', $user->name)
                ->type('email', $user->email)
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->press('Register')
                ->waitForLocation('/')
                ->assertPathIs('/')
                ->signOut($user);
        });
    }
}
