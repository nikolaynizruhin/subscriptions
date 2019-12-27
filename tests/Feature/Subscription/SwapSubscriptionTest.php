<?php

namespace Tests\Feature\Subscription;

use App\Plan;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SwapSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cant_swap_subscription()
    {
        $this->putJson(route('subscription.update'), [
            'plan' => Plan::first()->id,
        ])->assertUnauthorized();
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

        [$pro, $basic] = Plan::all();

        $customer = $user->createAsStripeCustomer();

        $paymentMethod = $user->updateDefaultPaymentMethod('pm_card_visa');

        $user->newSubscription($basic->id)->create($paymentMethod->id);

        $this->actingAs($user, 'api')
            ->putJson(route('subscription.update'), [
                'plan' => $pro->id,
            ])->assertSuccessful()
            ->assertJson([
                'email' => $user->email,
                'stripe_id' => $customer->id,
                'on_trial' => false,
                'subscription' => [
                    'stripe_status' => 'active',
                    'stripe_plan' => $pro->id,
                    'on_grace_period' => false,
                ],
            ]);
    }
}
