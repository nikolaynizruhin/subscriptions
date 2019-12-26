<?php

namespace Tests\Feature\Subscription;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Cashier;
use Stripe\Plan;
use Tests\TestCase;

class CancelSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $product = config('subscription.product');

        $plans = Plan::all(['product' => $product], Cashier::stripeOptions());

        $this->plan = $plans->data[0];
    }

    /** @test */
    public function user_can_cancel_subscription()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $paymentMethod = $user->updateDefaultPaymentMethod('pm_card_visa');

        $user->newSubscription(config('subscription.product'), $this->plan->id)
            ->create($paymentMethod->id);

        $this->actingAs($user, 'api')
            ->deleteJson(route('subscription.destroy'))
            ->assertSuccessful();

        $subscription = $user->fresh()->subscription(config('subscription.product'));

        $this->assertTrue($subscription->onGracePeriod());
    }
}
