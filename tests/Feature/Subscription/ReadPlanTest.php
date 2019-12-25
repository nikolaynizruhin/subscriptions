<?php

namespace Tests\Feature\Subscription;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Cashier\Cashier;
use Stripe\Plan;
use Tests\TestCase;

class ReadPlanTest extends TestCase
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
    public function guest_cant_read_plan()
    {
        $this->getJson(route('plans.show', ['plan' => $this->plan->id]))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_read_plan()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->getJson(route('plans.show', ['plan' => $this->plan->id]))
            ->assertSuccessful()
            ->assertJson($this->plan->toArray());
    }
}
