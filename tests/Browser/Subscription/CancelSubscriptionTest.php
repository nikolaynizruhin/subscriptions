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

        $plan = Plan::first();

        $user->createAsStripeCustomer();

        $paymentMethod = $user->updateDefaultPaymentMethod('pm_card_visa');

        $user->newSubscription($plan->id)
            ->create($paymentMethod->id);

        $this->browse(function (Browser $browser) use ($user, $plan) {
            $browser->actingAs($user)
                ->visit('/settings/subscription')
                ->waitForText($plan->nickname, 10)
                ->assertSee($plan->nickname)
                ->press('Cancel Plan')
                ->waitForText('Are you sure you want to cancel '.$plan->nickname.' subscription plan?', 10)
                ->click('@cancel-plan-button')
                ->waitForText('Plan canceled!', 10)
                ->assertSee('Plan canceled!')
                ->signOut();
        });
    }
}
