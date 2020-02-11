<?php

namespace Tests\Browser\Subscription;

use App\Plan;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CancelSubscriptionTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_cancel_subscription()
    {
        $user = factory(User::class)->create();

        [$pro, $basic] = Plan::all();

        $user->createAsStripeCustomer();

        $paymentMethod = $user->updateDefaultPaymentMethod('pm_card_visa');

        $user->newSubscription($pro->id)->create($paymentMethod->id);

        $this->browse(function (Browser $browser) use ($user, $pro, $basic) {
            $browser->actingAs($user)
                ->visit('/settings/subscription')
                ->waitForText($pro->nickname, 10)
                ->waitForText($basic->nickname, 10)
                ->assertRadioSelected('plan', $pro->id)
                ->press('Cancel Plan')
                ->waitForText('Are you sure you want to cancel '.$pro->nickname.' subscription plan?', 10)
                ->click('@cancel-plan-button')
                ->waitForText('Plan canceled!', 10)
                ->assertSee('Plan canceled!')
                ->signOut();
        });
    }
}
