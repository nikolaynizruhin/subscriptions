<?php

namespace Tests\Feature\Subscription;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatePaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cant_add_payment_method()
    {
        $this->postJson(route('customer-payment-method.store'))
            ->assertUnauthorized();
    }

    /** @test */
    public function customer_can_add_payment_method()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $this->actingAs($user, 'api')
            ->postJson(route('customer-payment-method.store'), [
                'payment_method' => 'pm_card_visa',
            ])->assertSuccessful()
            ->assertJson([
                'object' => 'payment_method',
                'card' => [
                    'brand' => 'visa',
                    'last4' => '4242',
                ],
                'customer' => $user->stripe_id,
                'type' => 'card',
            ]);
    }
}
