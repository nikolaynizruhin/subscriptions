<?php

namespace Tests\Browser\Auth;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ConfirmPasswordTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_with_confirmed_password_can_visit_requires_confirmation_page()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/settings/security')
                ->assertPathIs('/settings/security')
                ->signOut();
        });
    }

    /** @test */
    public function user_with_unconfirmed_password_cant_visit_requires_confirmation_page()
    {
        $user = factory(User::class)->states('unconfirmed_password')->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/settings/security')
                ->assertQueryStringHas('redirect', '/settings/security')
                ->assertPathIs('/password/confirm')
                ->signOut();
        });
    }
}
