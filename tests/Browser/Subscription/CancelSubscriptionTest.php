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

    protected $plans;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plans = Plan::all();
    }

    /** @test */
    public function user_can_cancel_subscription()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $paymentMethod = $user->updateDefaultPaymentMethod('pm_card_visa');

        $user->newSubscription(config('subscription.product'), $this->plans->first()->id)
            ->create($paymentMethod->id);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/settings/subscription')
                ->waitForText($this->plans->first()->nickname, 10)
                ->assertSee($this->plans->first()->nickname)
                ->press('Cancel Plan')
                ->waitForText('Are you sure you want to cancel '.$this->plans->first()->nickname.' subscription plan?', 10)
                ->click('@cancel-plan-button')
                ->waitForText('Plan canceled!', 10)
                ->assertSee('Plan canceled!')
                ->signOut();
        });
    }
}
