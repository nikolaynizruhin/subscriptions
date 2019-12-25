<?php

namespace Tests\Feature\Subscription;

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
}
