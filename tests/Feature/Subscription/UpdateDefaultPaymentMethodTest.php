<?php

namespace Tests\Feature\Subscription;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateDefaultPaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cant_update_default_payment_method()
    {
        $this->putJson(route('default-payment-method.update', [
            'payment_method' => 'pm_card_visa',
        ]))->assertUnauthorized();
    }

    /** @test */
    public function customer_can_update_default_payment_method()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $paymentMethod = $user->addPaymentMethod('pm_card_visa');

        $this->assertNull($user->card_last_four);
        $this->assertNull($user->card_brand);

        $this->actingAs($user, 'api')
            ->putJson(route('default-payment-method.update'), [
                'payment_method' => $paymentMethod->id,
            ])->assertSuccessful()
            ->assertJson([
                'object' => 'customer',
                'email' => $user->email,
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethod->id,
                ],
            ]);

        $this->assertEquals('4242', $user->card_last_four);
        $this->assertEquals('visa', $user->card_brand);
    }
}
