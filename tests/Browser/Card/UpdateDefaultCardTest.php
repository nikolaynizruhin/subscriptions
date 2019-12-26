<?php

namespace Tests\Browser\Card;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UpdateDefaultCardTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_update_default_card()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $user->addPaymentMethod('pm_card_visa');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/settings/subscription')
                ->waitForText('4242', 10)
                ->assertSee('4242')
                ->assertDontSee('Default')
                ->click('@card-actions-button')
                ->assertSee('Set As Default')
                ->press('Set As Default')
                ->waitForText('Default payment method updated!')
                ->assertSee('Default payment method updated!')
                ->with('@card-list', function ($cards) {
                    $cards->assertSee('Default');
                })->signOut();
        });
    }
}
