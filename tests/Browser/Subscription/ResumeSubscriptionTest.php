<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Cashier\Cashier;
use Laravel\Dusk\Browser;
use Stripe\Plan;
use Tests\DuskTestCase;

class ResumeSubscriptionTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $plans;

    protected function setUp(): void
    {
        parent::setUp();

        $product = config('subscription.product');

        $plans = Plan::all(['product' => $product], Cashier::stripeOptions());

        $this->plans = $plans->data;
    }

    /** @test */
    public function user_can_resume_canceled_subscription()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $paymentMethod = $user->updateDefaultPaymentMethod('pm_card_visa');

        $user->newSubscription(config('subscription.product'), $this->plans[0]->id)
            ->create($paymentMethod->id);

        $user->fresh()->subscription(config('subscription.product'))->cancel();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/settings/subscription')
                ->waitForText($this->plans[0]->nickname, 10)
                ->assertSee($this->plans[0]->nickname)
                ->waitForText('You Are On A Grace Period')
                ->assertSee('You Are On A Grace Period')
                ->waitForText('Resume', 10)
                ->press('Resume')
                ->waitForText('Subscription updated successfully!', 10)
                ->assertSee('Subscription updated successfully!')
                ->signOut();
        });
    }
}
