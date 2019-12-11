<?php

namespace Tests\Browser\Auth;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Password;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ResetPasswordTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_send_password_reset_link()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/password/reset')
                ->type('email', $user->email)
                ->press('Send Password Reset Link')
                ->waitForText('We have e-mailed your password reset link!')
                ->assertSee('We have e-mailed your password reset link!');
        });
    }

    /** @test */
    public function user_can_reset_password()
    {
        $user = factory(User::class)->create();

        $token = Password::createToken($user);

        $this->browse(function (Browser $browser) use ($user, $token) {
            $browser->visit('/password/reset/'.$token.'?email='.$user->email)
                ->assertInputValue('email', $user->email)
                ->type('password', 'new_password')
                ->type('password_confirmation', 'new_password')
                ->press('Reset Password')
                ->waitForLocation('/')
                ->assertPathIs('/')
                ->signOut();
        });
    }
}
