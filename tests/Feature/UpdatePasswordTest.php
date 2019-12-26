<?php

namespace Tests\Feature\Settings;

use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function guest_cant_update_password()
    {
        $this->putJson(route('password.update'))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_update_password()
    {
        $user = factory(User::class)->create();
        $oldApiToken = $user->api_token;

        $this->actingAs($user, 'api')
            ->putJson(route('password.update'), [
                'password' => 'password',
                'new_password' => $newPassword = 'new_password',
                'new_password_confirmation' => $newPassword,
            ])->assertSuccessful()
                ->assertJson([
                    'token' => $user->api_token,
                    'user' => (new UserResource($user))->jsonSerialize(),
                ]);

        $this->assertTrue(Hash::check($newPassword, $user->password));
        $this->assertNotEquals($oldApiToken, $user->api_token);
    }

    /** @test */
    public function password_is_required()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('password.update'), [
                'new_password' => $newPassword = $this->faker->password(8),
                'new_password_confirmation' => $newPassword,
            ])->assertJsonValidationErrors('password');
    }

    /** @test */
    public function password_should_be_a_string()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('password.update'), [
                'password' => 1,
                'new_password' => $newPassword = $this->faker->password(8),
                'new_password_confirmation' => $newPassword,
            ])->assertJsonValidationErrors('password');
    }

    /** @test */
    public function password_should_be_less_than_255_chars()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('password.update'), [
                'password' => str_repeat('a', 256),
                'new_password' => $newPassword = $this->faker->password(8),
                'new_password_confirmation' => $newPassword,
            ])->assertJsonValidationErrors('password');
    }

    /** @test */
    public function password_should_be_min_8_chars()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('password.update'), [
                'password' => str_repeat('a', 7),
                'new_password' => $newPassword = $this->faker->password(8),
                'new_password_confirmation' => $newPassword,
            ])->assertJsonValidationErrors('password');
    }

    /** @test */
    public function password_should_match_current()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('password.update'), [
                'password' => 'does_not_match',
                'new_password' => $newPassword = $this->faker->password(8),
                'new_password_confirmation' => $newPassword,
            ])->assertJsonValidationErrors('password');
    }

    /** @test */
    public function new_password_is_required()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('password.update'), ['password' => 'password'])
            ->assertJsonValidationErrors('new_password');
    }

    /** @test */
    public function new_password_should_be_a_string()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('password.update'), [
                'password' => 'password',
                'new_password' => 1,
            ])->assertJsonValidationErrors('new_password');
    }

    /** @test */
    public function new_password_should_be_less_than_255_chars()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('password.update'), [
                'password' => 'password',
                'new_password' => str_repeat('a', 256),
            ])->assertJsonValidationErrors('new_password');
    }

    /** @test */
    public function new_password_should_be_min_8_chars()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('password.update'), [
                'password' => str_repeat('a', 7),
                'new_password' => str_repeat('a', 7),
            ])->assertJsonValidationErrors('new_password');
    }

    /** @test */
    public function new_password_should_be_confirmed()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->putJson(route('password.update'), [
                'password' => 'password',
                'new_password' => $newPassword = $this->faker->password(8),
            ])->assertJsonValidationErrors('new_password');
    }
}
