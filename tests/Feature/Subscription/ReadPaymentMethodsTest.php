<?php

namespace Tests\Feature\Subscription;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadPaymentMethodsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cant_read_payment_methods()
    {
        $this->getJson(route('payment-methods.index'))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_read_payment_methods()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $user->addPaymentMethod('pm_card_visa');
        $user->addPaymentMethod('pm_card_mastercard');

        $this->actingAs($user, 'api')
            ->getJson(route('payment-methods.index'))
            ->assertSuccessful()
            ->assertJson([
                [
                    'object' => 'payment_method',
                    'card' => [
                        'brand' => 'mastercard',
                        'last4' => '4444',
                    ],
                    'customer' => $user->stripe_id,
                    'type' => 'card',
                ],
                [
                    'object' => 'payment_method',
                    'card' => [
                        'brand' => 'visa',
                        'last4' => '4242',
                    ],
                    'customer' => $user->stripe_id,
                    'type' => 'card',
                ],
            ]);

        $this->assertCount(2, $user->paymentMethods());
    }
}
