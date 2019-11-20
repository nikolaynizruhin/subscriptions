<?php

namespace Tests\Feature\Settings;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function guest_cant_update_profile()
    {
        $this->putJson(route('user.update'))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_update_own_profile()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('user.update'), $userData = [
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
            ])->assertSuccessful()
            ->assertJson($user->toArray());

        $this->assertDatabaseHas('users', $userData);
    }

    /** @test */
    public function name_is_required()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('user.update'), [
                'email' => $this->faker->unique()->safeEmail,
            ])->assertJsonValidationErrors('name');
    }

    /** @test */
    public function name_should_be_a_string()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('user.update'), [
                'name' => 1,
                'email' => $this->faker->unique()->safeEmail,
            ])->assertJsonValidationErrors('name');
    }

    /** @test */
    public function name_should_be_less_than_255_chars()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('user.update'), [
                'name' => str_repeat('a', 256),
                'email' => $this->faker->unique()->safeEmail,
            ])->assertJsonValidationErrors('name');
    }

    /** @test */
    public function email_is_required()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('user.update'), [
                'name' => $this->faker->name,
            ])->assertJsonValidationErrors('email');
    }

    /** @test */
    public function email_should_be_valid()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('user.update'), [
                'name' => $this->faker->name,
                'email' => 'invalid',
            ])->assertJsonValidationErrors('email');
    }

    /** @test */
    public function email_should_be_a_string()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('user.update'), [
                'name' => $this->faker->name,
                'email' => 1,
            ])->assertJsonValidationErrors('email');
    }

    /** @test */
    public function email_should_be_less_than_255_chars()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('user.update'), [
                'name' => $this->faker->name,
                'email' => str_repeat('a', 256),
            ])->assertJsonValidationErrors('email');
    }

    /** @test */
    public function email_should_be_unique()
    {
        $user = factory(User::class)->create();
        $existingUser = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('user.update'), [
                'email' => $existingUser->email,
            ])->assertJsonValidationErrors('email');
    }

    /** @test */
    public function email_should_be_unique_except_itself()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('user.update'), [
                'name' => $user->name,
                'email' => $user->email,
            ])->assertSuccessful();
    }
}
