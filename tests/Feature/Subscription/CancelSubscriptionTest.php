<?php

namespace Tests\Feature\Subscription;

use App\Plan;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CancelSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plan = Plan::all()->first();
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

        $subscription = $user->fresh()->subscription();

        $this->assertTrue($subscription->onGracePeriod());
    }
}
