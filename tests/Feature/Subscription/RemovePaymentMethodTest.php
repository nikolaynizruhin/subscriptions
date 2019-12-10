<?php

namespace Tests\Feature\Subscription;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RemovePaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cant_remove_payment_method()
    {
        $this->deleteJson(route('payment-methods.destroy', [
            'payment_method' => 'pm_card_visa',
        ]))->assertUnauthorized();
    }

    /** @test */
    public function user_can_remove_payment_method()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $paymentMethod = $user->addPaymentMethod('pm_card_visa');

        $this->assertCount(1, $user->paymentMethods());

        $this->actingAs($user, 'api')
            ->deleteJson(route('payment-methods.destroy', [
                'payment_method' => $paymentMethod->id,
            ]))->assertSuccessful();

        $this->assertCount(0, $user->paymentMethods());
    }
}
