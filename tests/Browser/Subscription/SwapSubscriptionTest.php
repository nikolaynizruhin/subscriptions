<?php

namespace Tests\Browser\Subscription;

use App\Plan;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SwapSubscriptionTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_swap_subscription_plan()
    {
        $user = factory(User::class)->create();

        [$pro, $basic] = Plan::all();

        $user->createAsStripeCustomer();

        $paymentMethod = $user->updateDefaultPaymentMethod('pm_card_visa');

        $user->newSubscription($basic->id)->create($paymentMethod->id);

        $this->browse(function (Browser $browser) use ($user, $basic, $pro) {
            $browser->actingAs($user)
                ->visit('/settings/subscription')
                ->waitForText($basic->nickname, 10)
                ->waitForText($pro->nickname, 10)
                ->assertSee($basic->nickname)
                ->assertSee($pro->nickname)
                ->assertRadioSelected('plan', $basic->id)
                ->assertRadioNotSelected('plan', $pro->id)
                ->radio('plan', $pro->id)
                ->press('Update')
                ->waitForText('Subscription updated successfully!', 10)
                ->assertSee('Subscription updated successfully!')
                ->signOut();
        });
    }
}
