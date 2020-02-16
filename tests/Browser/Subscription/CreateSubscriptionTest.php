<?php

namespace Tests\Browser\Subscription;

use App\Plan;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateSubscriptionTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_create_subscription()
    {
        $user = factory(User::class)->create();

        [$pro, $basic] = Plan::all();

        $user->createAsStripeCustomer();

        $user->updateDefaultPaymentMethod('pm_card_visa');

        $this->browse(function (Browser $browser) use ($user, $pro, $basic) {
            $browser->actingAs($user)
                ->visit('/settings/subscription')
                ->waitForText($basic->nickname, 10)
                ->waitForText($pro->nickname, 10)
                ->assertRadioNotSelected('plan', $basic->id)
                ->assertRadioNotSelected('plan', $pro->id)
                ->radio('plan', $basic->id)
                ->press('Subscribe')
                ->waitForText('Subscription updated successfully!', 20)
                ->assertSee('Subscription updated successfully!')
                ->signOut();
        });
    }
}
