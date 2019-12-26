<?php

namespace Tests\Browser\Subscription;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Cashier\Cashier;
use Laravel\Dusk\Browser;
use Stripe\Plan;
use Tests\DuskTestCase;

class CreateSubscriptionTest extends DuskTestCase
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
    public function user_can_create_subscription()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $user->updateDefaultPaymentMethod('pm_card_visa');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->actingAs($user)
                ->visit('/settings/subscription')
                ->waitForText($this->plans[0]->nickname, 10)
                ->assertSee($this->plans[0]->nickname)
                ->waitForText($this->plans[1]->nickname, 10)
                ->assertSee($this->plans[1]->nickname)
                ->radio('plan', $this->plans[0]->id)
                ->press('Update')
                ->waitForText('Subscription updated successfully!', 10)
                ->assertSee('Subscription updated successfully!')
                ->signOut();
        });
    }
}
