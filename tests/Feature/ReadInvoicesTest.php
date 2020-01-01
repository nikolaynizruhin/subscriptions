<?php

namespace Tests\Feature;

use App\Plan;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadInvoicesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cant_read_customer()
    {
        $this->getJson(route('invoices.index'))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_read_invoices()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $paymentMethod = $user->updateDefaultPaymentMethod('pm_card_visa');

        $user->newSubscription(Plan::first()->id)->create($paymentMethod->id);

        $this->actingAs($user, 'api')
            ->getJson(route('invoices.index'))
            ->assertSuccessful()
            ->assertJson($user->stripeInvoices()->all());

        $this->assertCount(1, $user->stripeInvoices());
    }
}
