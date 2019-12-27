<?php

namespace Tests\Feature\Plan;

use App\Plan;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadPlansTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cant_read_plans()
    {
        $this->getJson(route('plans.index'))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_read_plans()
    {
        $user = factory(User::class)->create();

        [$pro, $basic] = Plan::all();

        $this->actingAs($user, 'api')
            ->getJson(route('plans.index'))
            ->assertSuccessful()
            ->assertJsonFragment($basic->toArray())
            ->assertJsonFragment($pro->toArray());
    }
}
