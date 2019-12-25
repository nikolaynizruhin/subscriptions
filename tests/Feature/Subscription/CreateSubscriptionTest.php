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
}
