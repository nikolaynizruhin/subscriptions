<?php

namespace Tests\Browser\Auth;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class VerificationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function unverified_user_should_see_alert()
    {
        $user = factory(User::class)->states('unverified')->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/')
                ->waitForText('Verify Your Email Address')
                ->assertSee('Verify Your Email Address')
                ->signOut();
        });
    }

    /** @test */
    public function unverified_user_cant_visit_verification_protected_route()
    {
        $user = factory(User::class)->states('unverified')->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/settings')
                ->waitForLocation('/')
                ->assertPathIs('/')
                ->signOut();
        });
    }

    /** @test */
    public function verified_user_should_not_see_verification_alert()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/')
                ->assertDontSee('Verify Your Email Address')
                ->signOut();
        });
    }
}
