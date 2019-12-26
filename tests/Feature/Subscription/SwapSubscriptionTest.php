<?php

namespace Tests\Feature\Subscription;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Cashier;
use Stripe\Plan;
use Tests\TestCase;

class SwapSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    protected $plans;

    protected function setUp(): void
    {
        parent::setUp();

        $product = config('subscription.product');

        $plans = Plan::all(['product' => $product], Cashier::stripeOptions());

        $this->plans = $plans->data;
    }

    /** @test */
    public function guest_cant_swap_subscription()
    {
        $this->putJson(route('subscription.update'), ['plan' => $this->plans[0]->id])
            ->assertUnauthorized();
    }

    /** @test */
    public function plan_is_required()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('subscription.update'))
            ->assertJsonValidationErrors('plan');
    }

    /** @test */
    public function plan_should_be_a_string()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('subscription.update'), [
                'plan' => 1,
            ])->assertJsonValidationErrors('plan');
    }

    /** @test */
    public function plan_should_be_less_than_255_chars()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('subscription.update'), [
                'plan' => str_repeat('a', 256),
            ])->assertJsonValidationErrors('plan');
    }

    /** @test */
    public function plan_should_exists()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('subscription.update'), [
                'plan' => 'non-exist',
            ])->assertJsonValidationErrors('plan');
    }

    /** @test */
    public function user_can_swap_a_plan()
    {
        $user = factory(User::class)->create();

        $customer = $user->createAsStripeCustomer();

        $paymentMethod = $user->updateDefaultPaymentMethod('pm_card_visa');

        $user->newSubscription(config('subscription.product'), $this->plans[0]->id)
            ->create($paymentMethod->id);

        $this->actingAs($user, 'api')
            ->putJson(route('subscription.update'), [
                'plan' => $this->plans[1]->id,
            ])->assertSuccessful()
            ->assertJson([
                'email' => $user->email,
                'stripe_id' => $customer->id,
                'on_trial' => false,
                'subscription' => [
                    'stripe_status' => 'active',
                    'stripe_plan' => $this->plans[1]->id,
                    'on_grace_period' => false
                ]
            ]);
    }
}
