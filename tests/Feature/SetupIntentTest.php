<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetupIntentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cant_setup_intent()
    {
        $this->postJson(route('setup-intents.store'))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_read_customer()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer();

        $this->actingAs($user, 'api')
            ->postJson(route('setup-intents.store'))
            ->assertSuccessful()
            ->assertJson([
                'object' => 'setup_intent',
                'status' => 'requires_payment_method',
            ]);
    }
}
