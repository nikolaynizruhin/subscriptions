<?php

namespace Tests\Feature\Plan;

use App\Plan;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadPlanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cant_read_plan()
    {
        $this->getJson(route('plans.show', ['plan' => Plan::first()->id]))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_read_plan()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->getJson(route('plans.show', ['plan' => Plan::first()->id]))
            ->assertSuccessful()
            ->assertJson(Plan::first()->toArray());
    }
}
