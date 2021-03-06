<?php

namespace Tests\Browser\Card;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RemoveCardTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_remove_card()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $user->addPaymentMethod('pm_card_visa');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/settings/subscription')
                ->waitForText('4242', 10)
                ->assertSee('4242')
                ->click('@remove-card-button')
                ->assertSee('Remove Card')
                ->press('Remove')
                ->waitForText('Card removed!', 10)
                ->assertSee('Card removed!')
                ->assertSee('No credit or debit cards.')
                ->signOut();
        });
    }
}
