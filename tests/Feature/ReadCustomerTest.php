<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadCustomerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cant_read_customer()
    {
        $this->getJson(route('customer.index'))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_read_customer()
    {
        $user = factory(User::class)->create();

        $user->createAsStripeCustomer(['name' => $user->name]);

        $this->actingAs($user, 'api')
            ->getJson(route('customer.index'))
            ->assertSuccessful()
            ->assertJson([
                'object' => 'customer',
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }
}
