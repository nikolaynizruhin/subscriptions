<?php

namespace Tests\Browser\Subscription;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TrialPeriodTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_on_trial_period_should_see_alert()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/')
                ->waitForText('You Are On A Trial Period')
                ->assertSee('You Are On A Trial Period')
                ->signOut();
        });
    }
}
