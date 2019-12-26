<?php

namespace Tests\Feature\Subscription;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Cashier\Cashier;
use Stripe\Plan;
use Tests\TestCase;

class CreateSubscriptionTest extends TestCase
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
    public function guest_cant_create_subscription()
    {
        $this->postJson(route('subscription.store'), ['plan' => $this->plan->id])
            ->assertUnauthorized();
    }

    /** @test */
    public function plan_is_required()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->postJson(route('subscription.store'))
            ->assertJsonValidationErrors('plan');
    }

    /** @test */
    public function plan_should_be_a_string()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->postJson(route('subscription.store'), [
                'plan' => 1,
            ])->assertJsonValidationErrors('plan');
    }

    /** @test */
    public function plan_should_be_less_than_255_chars()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->postJson(route('subscription.store'), [
                'plan' => str_repeat('a', 256),
            ])->assertJsonValidationErrors('plan');
    }

    /** @test */
    public function plan_should_exists()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->postJson(route('subscription.store'), [
                'plan' => 'non-exist',
            ])->assertJsonValidationErrors('plan');
    }

    /** @test */
    public function user_can_subscribe_to_a_plan()
    {
        $user = factory(User::class)->create();

        $customer = $user->createAsStripeCustomer();

        $user->updateDefaultPaymentMethod('pm_card_visa');

        $this->assertTrue($user->onGenericTrial());
        $this->assertNull($user->subscription(config('subscription.product')));

        $this->actingAs($user, 'api')
            ->postJson(route('subscription.store'), [
                'plan' => $this->plan->id,
            ])->assertSuccessful()
            ->assertJson([
                'email' => $user->email,
                'stripe_id' => $customer->id,
                'on_trial' => false,
                'subscription' => [
                    'stripe_status' => 'active',
                    'stripe_plan' => $this->plan->id,
                    'on_grace_period' => false,
                ],
            ]);

        tap($user->fresh(), function ($user) {
            $this->assertFalse($user->onGenericTrial());
            $this->assertNotNull($user->subscription(config('subscription.product')));
        });
    }
}
